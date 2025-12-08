<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PayPalService;
use App\Models\Carrito;
use App\Models\Pedido;
use App\Models\PedidoDetalle;
use App\Models\Pago;
use App\Models\Producto;
use App\Models\Peticion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PayPalController extends Controller
{
    protected $paypal;

    public function __construct(PayPalService $paypal)
    {
        $this->paypal = $paypal;
    }

    private function mapPayPalStatus(string $status): string
    {
        return match ($status) {
            'COMPLETED' => 'completado',
            'PENDING' => 'pendiente',
            default => 'fallido',
        };
    }

    /**
     * Valida que haya stock suficiente para todos los productos del carrito
     * 
     * @param \App\Models\Carrito $carrito
     * @return array ['valid' => bool, 'errors' => array]
     */
    private function validateStock($carrito): array
    {
        $errors = [];
        
        foreach ($carrito->detalles as $detalle) {
            $producto = $detalle->producto;
            
            if ($producto->stock < $detalle->cantidad) {
                $errors[] = sprintf(
                    'Stock insuficiente para "%s". Disponible: %d, Solicitado: %d',
                    $producto->nombre,
                    $producto->stock,
                    $detalle->cantidad
                );
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    public function createPayment(Request $request)
    {
        $request->validate([
            'calle' => 'required|string|max:255',
            'colonia' => 'required|string|max:255',
            'municipio_ciudad' => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:10',
            'estado' => 'required|string|max:100',
        ]);

        try {
            $user = Auth::user();
            $carrito = Carrito::firstOrCreate(['id_usuario' => $user->id_usuario]);
            $carrito->load('detalles.producto');

            if ($carrito->detalles->isEmpty()) {
                return response()->json(['error' => true, 'message' => 'Tu carrito está vacío'], 422);
            }

            // Validar stock antes de crear la orden
            $stockValidation = $this->validateStock($carrito);
            if (!$stockValidation['valid']) {
                Log::warning('Intento de pago con stock insuficiente', [
                    'user_id' => $user->id_usuario,
                    'errors' => $stockValidation['errors']
                ]);
                return response()->json([
                    'error' => true, 
                    'message' => 'Stock insuficiente para algunos productos',
                    'details' => $stockValidation['errors']
                ], 422);
            }

            $total = $carrito->detalles->sum(function ($d) {
                return $d->cantidad * $d->producto->precio;
            });
            $monto = number_format($total, 2, '.', '');

            // Generar un invoice_id único para vincular con PayPal
            $invoiceId = 'INV-' . Str::uuid()->toString();

            session([
                'checkout.paypal' => [
                    'calle' => $request->calle,
                    'colonia' => $request->colonia,
                    'municipio_ciudad' => $request->municipio_ciudad,
                    'codigo_postal' => $request->codigo_postal,
                    'estado' => $request->estado,
                    'monto' => $monto,
                    'invoice_id' => $invoiceId,
                ]
            ]);

            $response = $this->paypal->createOrder($monto, $invoiceId);
            return response()->json($response->result);
        } catch (\Throwable $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 422);
        }
    }

    /**
     * Lógica centralizada para crear el pedido local tras validación de pago
     */
    private function createLocalOrder($user, $carrito, $monto, $data, $captureId, $captureStatus)
    {
        return DB::transaction(function () use ($user, $carrito, $monto, $data, $captureId, $captureStatus) {
            $pedido = Pedido::create([
                'id_usuario' => $user->id_usuario,
                'invoice_id' => $data['invoice_id'] ?? null,
                'total' => $monto,
                'estado' => 'procesando',
                'calle' => $data['calle'] ?? '',
                'colonia' => $data['colonia'] ?? '',
                'municipio_ciudad' => $data['municipio_ciudad'] ?? '',
                'codigo_postal' => $data['codigo_postal'] ?? '',
                'estado_direccion' => $data['estado'] ?? '',
                'metodo_pago' => 'paypal',
                'fecha_pedido' => now(),
            ]);

            foreach ($carrito->detalles as $detalle) {
                // Crear detalle del pedido
                PedidoDetalle::create([
                    'id_pedido' => $pedido->id_pedido,
                    'id_producto' => $detalle->id_producto,
                    'cantidad' => $detalle->cantidad,
                    'precio_unitario' => $detalle->producto->precio,
                ]);
                
                // Reducir stock del producto
                $producto = Producto::lockForUpdate()->find($detalle->id_producto);
                if ($producto) {
                    $producto->decrement('stock', $detalle->cantidad);
                    Log::info('Stock reducido', [
                        'producto_id' => $producto->id_producto,
                        'cantidad' => $detalle->cantidad,
                        'stock_anterior' => $producto->stock + $detalle->cantidad,
                        'stock_nuevo' => $producto->stock
                    ]);
                }
            }

            Pago::create([
                'id_pedido' => $pedido->id_pedido,
                'metodo' => 'paypal',
                'monto' => $monto,
                'referencia' => $captureId,
                'estado' => $captureStatus,
            ]);

            $carrito->detalles()->delete();

            return $pedido;
        });
    }

    public function capturePayment(Request $request)
    {
        $validated = $request->validate([
            'orderId' => 'required|string'
        ]);
        $orderId = $validated['orderId'];

        try {
            $capture = $this->paypal->captureOrder($orderId);
            
            // Validaciones básicas del resultado
            $status = $capture->result->status ?? null;
            if ($status !== 'COMPLETED') {
                 return response()->json(['error' => true, 'message' => 'El pago no se completó (Status: '.$status.')'], 422);
            }

            $user = Auth::user();
            $carrito = Carrito::firstOrCreate(['id_usuario' => $user->id_usuario]);
            $carrito->load('detalles.producto');

            if ($carrito->detalles->isEmpty()) {
                 return response()->json(['error' => true, 'message' => 'El carrito está vacío.'], 422);
            }

            // Validar stock
            $stockValidation = $this->validateStock($carrito);
            if (!$stockValidation['valid']) {
                 return response()->json([
                     'error' => true, 
                     'message' => 'Stock insuficiente: ' . implode(', ', $stockValidation['errors'])
                 ], 422);
            }

            $total = $carrito->detalles->sum(function ($d) {
                return $d->cantidad * $d->producto->precio;
            });
            $monto = number_format($total, 2, '.', '');
            
            // Recuperar datos de sesión guardados en createPayment
            $data = session('checkout.paypal', []);

            $captures = $capture->result->purchase_units[0]->payments->captures ?? [];
            $captureId = $captures[0]->id ?? null;
            $captureStatus = $this->mapPayPalStatus($captures[0]->status ?? 'COMPLETED');

            // Verificar duplicados
            $pagoExistente = Pago::where('referencia', $captureId)->first();
            if ($pagoExistente) {
                 return response()->json(['error' => true, 'message' => 'Pago ya registrado anteriormente.'], 422);
            }

            // CREAR ORDEN LOCAL
            $pedido = $this->createLocalOrder($user, $carrito, $monto, $data, $captureId, $captureStatus);

            session()->forget('checkout.paypal');
            
            // Retornamos URL de éxito para que el JS redirija
            return response()->json([
                'status' => 'COMPLETED',
                'redirect_url' => route('cliente.pedidos.show', $pedido->id_pedido)
            ]);

        } catch (\Throwable $e) {
            Log::error('PayPal capturePayment error', ['message' => $e->getMessage()]);
            return response()->json(['error' => true, 'message' => $e->getMessage()], 422);
        }
    }

    public function handleReturn(Request $request)
    {
        $orderId = $request->query('token');
        if (!$orderId) {
            return redirect()->route('carrito.checkout')->with('error', 'Falta token de PayPal.');
        }

        try {
            $capture = $this->paypal->captureOrder($orderId);
            $status = $capture->result->status ?? null;
            if ($status !== 'COMPLETED') {
                return redirect()->route('carrito.checkout')->with('error', 'El pago no se completó.');
            }

            $user = Auth::user();
            $carrito = Carrito::firstOrCreate(['id_usuario' => $user->id_usuario]);
            $carrito->load('detalles.producto');

            if ($carrito->detalles->isEmpty()) {
                return redirect()->route('carrito.checkout')->with('error', 'El carrito está vacío.');
            }

            $stockValidation = $this->validateStock($carrito);
            if (!$stockValidation['valid']) {
                return redirect()->route('carrito.checkout')
                    ->with('error', 'Stock insuficiente: ' . implode(', ', $stockValidation['errors']));
            }

            $total = $carrito->detalles->sum(function ($d) {
                return $d->cantidad * $d->producto->precio;
            });
            $monto = number_format($total, 2, '.', '');

            $data = session('checkout.paypal', []);

            $captures = $capture->result->purchase_units[0]->payments->captures ?? [];
            $captureId = $captures[0]->id ?? null;
            $captureStatus = $this->mapPayPalStatus($captures[0]->status ?? 'COMPLETED');

            $pagoExistente = Pago::where('referencia', $captureId)->first();
            if ($pagoExistente) {
                $pedido = Pedido::find($pagoExistente->id_pedido);
                if ($pedido && $pedido->id_usuario === $user->id_usuario) {
                    return redirect()->route('cliente.pedidos.show', $pedido->id_pedido)
                        ->with('success', 'Pago ya procesado anteriormente.');
                } else {
                    return redirect()->route('carrito.checkout')
                        ->with('error', 'Error: Pago ya registrado pero no asociado a tu cuenta.');
                }
            } 

            // USO DEL MÉTODO COMPARTIDO
            $pedido = $this->createLocalOrder($user, $carrito, $monto, $data, $captureId, $captureStatus);

            session()->forget('checkout.paypal');

            return redirect()->route('cliente.pedidos.show', $pedido->id_pedido)
                ->with('success', 'Pago realizado con éxito.');
        } catch (\Throwable $e) {
            Log::error('PayPal handleReturn error', ['message' => $e->getMessage()]);
            return redirect()->route('carrito.checkout')->with('error', 'Error capturando el pago: ' . $e->getMessage());
        }
    }

    public function handleCancel()
    {
        return redirect()->route('carrito.checkout')->with('error', 'Pago cancelado por el usuario.');
    }

    public function webhook(Request $request)
    {
        $event = $request->input('event_type');
        
        Log::info('PayPal Webhook recibido', [
            'event_type' => $event,
            'payload' => $request->all()
        ]);

        // Verificar la firma del webhook
        $headers = $request->headers->all();
        $body = $request->getContent();
        
        if (!$this->paypal->verifyWebhookSignature($headers, $body)) {
            Log::error('Webhook con firma inválida rechazado', [
                'headers' => $headers,
                'ip' => $request->ip()
            ]);
            return response()->json(['error' => 'Invalid signature'], 401);
        }
        
        if ($event === 'PAYMENT.CAPTURE.COMPLETED') {
            $payload = $request->all();
            $captureId = data_get($payload, 'resource.id');
            $statusRaw = data_get($payload, 'resource.status');
            $invoiceId = data_get($payload, 'resource.invoice_id');
            $amount = data_get($payload, 'resource.amount.value');
            
            if (!$captureId) {
                Log::warning('Webhook sin capture_id', ['payload' => $payload]);
                return response()->json(['ok' => false, 'error' => 'Missing capture_id'], 400);
            }
            
            // Verificar si ya existe un pago con esta referencia
            $pago = Pago::where('referencia', $captureId)->first();
            
            if ($pago) {
                // El pago ya existe, solo actualizar estado si cambió
                $nuevoEstado = $this->mapPayPalStatus($statusRaw ?? 'COMPLETED');
                if ($pago->estado !== $nuevoEstado) {
                    $pago->update(['estado' => $nuevoEstado]);
                    Log::info('Pago actualizado via webhook', [
                        'pago_id' => $pago->id_pago,
                        'estado_anterior' => $pago->estado,
                        'estado_nuevo' => $nuevoEstado
                    ]);
                }
                return response()->json(['ok' => true, 'message' => 'Pago actualizado']);
            }
            
            // El pago no existe, buscar pedido por invoice_id
            if ($invoiceId) {
                $pedido = Pedido::where('invoice_id', $invoiceId)->first();
                
                if ($pedido) {
                    // Pedido existe pero sin pago registrado (usuario no regresó)
                    Pago::create([
                        'id_pedido' => $pedido->id_pedido,
                        'metodo' => 'paypal',
                        'monto' => $amount ?? 0,
                        'referencia' => $captureId,
                        'estado' => $this->mapPayPalStatus($statusRaw ?? 'COMPLETED'),
                    ]);
                    
                    // Actualizar estado del pedido a procesando
                    $pedido->update(['estado' => 'procesando']);
                    
                    Log::info('Pago creado via webhook para pedido existente', [
                        'pedido_id' => $pedido->id_pedido,
                        'invoice_id' => $invoiceId,
                        'capture_id' => $captureId
                    ]);
                    
                    return response()->json(['ok' => true, 'message' => 'Pago registrado']);
                }
            }
            
            // No se encontró ni pago ni pedido
            Log::warning('Webhook: No se encontró pedido para el pago', [
                'capture_id' => $captureId,
                'invoice_id' => $invoiceId
            ]);
            
            return response()->json(['ok' => true, 'message' => 'Pedido no encontrado']);
        }
        
        return response()->json(['ok' => true]);
    }

    /**
     * Crear pago para una petición aceptada por el cliente
     */
    public function createPeticionPayment(Request $request, $id_peticion)
    {
        try {
            $user = Auth::user();
            $peticion = Peticion::findOrFail($id_peticion);

            // Verificar que la petición pertenece al usuario
            if ($peticion->id_usuario !== $user->id_usuario) {
                return response()->json(['error' => true, 'message' => 'No autorizado'], 403);
            }

            // Verificar que el admin ya respondió con un precio
            if (!$peticion->precio_propuesto || !$peticion->respuesta_admin) {
                return response()->json(['error' => true, 'message' => 'La petición aún no tiene respuesta del administrador'], 422);
            }

            // Verificar que el cliente no haya respondido aún
            if ($peticion->respuesta_cliente !== 'pendiente') {
                return response()->json(['error' => true, 'message' => 'Ya has respondido a esta petición'], 422);
            }

            $monto = number_format($peticion->precio_propuesto, 2, '.', '');
            $invoiceId = 'PET-' . $peticion->id_peticion . '-' . Str::uuid()->toString();

            // Guardar datos en sesión
            session([
                'checkout.peticion' => [
                    'id_peticion' => $peticion->id_peticion,
                    'monto' => $monto,
                    'invoice_id' => $invoiceId,
                ]
            ]);

            $response = $this->paypal->createOrder($monto, $invoiceId);
            return response()->json($response->result);
        } catch (\Throwable $e) {
            Log::error('Error creando pago de petición', ['error' => $e->getMessage()]);
            return response()->json(['error' => true, 'message' => $e->getMessage()], 422);
        }
    }

    /**
     * Capturar pago de petición y crear pedido
     */
    public function capturePeticionPayment(Request $request)
    {
        $orderId = $request->query('token');
        
        if (!$orderId) {
            return redirect()->route('cliente.peticiones.index')->with('error', 'ID de orden no válido.');
        }

        try {
            $capture = $this->paypal->captureOrder($orderId);
            
            if (!isset($capture->result->status) || $capture->result->status !== 'COMPLETED') {
                return redirect()->route('cliente.peticiones.index')->with('error', 'El pago no se completó correctamente.');
            }

            $user = Auth::user();
            $data = session('checkout.peticion', []);
            
            if (empty($data['id_peticion'])) {
                return redirect()->route('cliente.peticiones.index')->with('error', 'Sesión expirada.');
            }

            $peticion = Peticion::findOrFail($data['id_peticion']);

            // Verificar que pertenece al usuario
            if ($peticion->id_usuario !== $user->id_usuario) {
                return redirect()->route('cliente.peticiones.index')->with('error', 'No autorizado.');
            }

            $captures = $capture->result->purchase_units[0]->payments->captures ?? [];
            $captureId = $captures[0]->id ?? null;
            $captureStatus = $this->mapPayPalStatus($captures[0]->status ?? 'COMPLETED');
            $monto = $data['monto'];

            // Verificar si ya existe un pago con esta referencia
            $pagoExistente = Pago::where('referencia', $captureId)->first();
            if ($pagoExistente) {
                $pedido = Pedido::find($pagoExistente->id_pedido);
                if ($pedido && $pedido->id_usuario === $user->id_usuario) {
                    return redirect()->route('cliente.pedidos.show', $pedido->id_pedido)
                        ->with('success', 'Pago ya procesado anteriormente.');
                }
            }

            $pedido = DB::transaction(function () use ($user, $peticion, $monto, $data, $captureId, $captureStatus) {
                // Crear pedido vinculado a la petición
                $pedido = Pedido::create([
                    'id_usuario' => $user->id_usuario,
                    'id_peticion' => $peticion->id_peticion,
                    'invoice_id' => $data['invoice_id'] ?? null,
                    'total' => $monto,
                    'estado' => 'procesando',
                    'calle' => $peticion->calle,
                    'colonia' => $peticion->colonia,
                    'municipio_ciudad' => $peticion->municipio_ciudad,
                    'codigo_postal' => $peticion->codigo_postal,
                    'estado_direccion' => $peticion->estado_direccion,
                    'metodo_pago' => 'paypal',
                    'fecha_pedido' => now(),
                ]);

                // Crear registro de pago
                Pago::create([
                    'id_pedido' => $pedido->id_pedido,
                    'metodo' => 'paypal',
                    'monto' => $monto,
                    'referencia' => $captureId,
                    'estado' => $captureStatus,
                ]);

                // Actualizar petición: cliente aceptó y se completó
                $peticion->update([
                    'respuesta_cliente' => 'aceptada',
                    'fecha_respuesta_cliente' => now(),
                    'estado' => 'completada'
                ]);

                return $pedido;
            });

            session()->forget('checkout.peticion');

            return redirect()->route('cliente.pedidos.show', $pedido->id_pedido)
                ->with('success', 'Pago realizado con éxito. Tu pedido personalizado está en proceso.');
        } catch (\Throwable $e) {
            Log::error('Error capturando pago de petición', ['error' => $e->getMessage()]);
            return redirect()->route('cliente.peticiones.index')->with('error', 'Error procesando el pago: ' . $e->getMessage());
        }
    }

    /**
     * Cancelar pago de petición
     */
    public function cancelPeticionPayment()
    {
        session()->forget('checkout.peticion');
        return redirect()->route('cliente.peticiones.index')->with('error', 'Pago cancelado.');
    }
}
