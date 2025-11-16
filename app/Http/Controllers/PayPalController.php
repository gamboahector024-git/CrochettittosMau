<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PayPalService;
use App\Models\Carrito;
use App\Models\Pedido;
use App\Models\PedidoDetalle;
use App\Models\Pago;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PayPalController extends Controller
{
    protected $paypal;

    public function __construct(PayPalService $paypal)
    {
        $this->paypal = $paypal;
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

            $total = $carrito->detalles->sum(function ($d) {
                return $d->cantidad * $d->producto->precio;
            });
            $monto = number_format($total, 2, '.', '');

            session([
                'checkout.paypal' => [
                    'calle' => $request->calle,
                    'colonia' => $request->colonia,
                    'municipio_ciudad' => $request->municipio_ciudad,
                    'codigo_postal' => $request->codigo_postal,
                    'estado' => $request->estado,
                    'monto' => $monto,
                ]
            ]);

            $response = $this->paypal->createOrder($monto);
            return response()->json($response->result);
        } catch (\Throwable $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 422);
        }
    }

    public function capturePayment(Request $request)
    {
        $validated = $request->validate([
            'orderId' => 'required|string'
        ]);

        try {
            $response = $this->paypal->captureOrder($validated['orderId']);
            return response()->json($response->result);
        } catch (\Throwable $e) {
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

            $total = $carrito->detalles->sum(function ($d) {
                return $d->cantidad * $d->producto->precio;
            });
            $monto = number_format($total, 2, '.', '');

            $data = session('checkout.paypal', []);

            $captures = $capture->result->purchase_units[0]->payments->captures ?? [];
            $captureId = $captures[0]->id ?? null;
            $captureStatus = $captures[0]->status ?? 'COMPLETED';

            // Verificar si ya existe un pago con esta referencia para evitar duplicados
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

            $pedido = DB::transaction(function () use ($user, $carrito, $monto, $data, $capture) {
                $pedido = Pedido::create([
                    'id_usuario' => $user->id_usuario,
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
                    PedidoDetalle::create([
                        'id_pedido' => $pedido->id_pedido,
                        'id_producto' => $detalle->id_producto,
                        'cantidad' => $detalle->cantidad,
                        'precio_unitario' => $detalle->producto->precio,
                    ]);
                }

                $captures = $capture->result->purchase_units[0]->payments->captures ?? [];
                $captureId = $captures[0]->id ?? null;
                $captureStatus = $captures[0]->status ?? 'COMPLETED';

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
        if ($event === 'PAYMENT.CAPTURE.COMPLETED') {
            $captureId = data_get($request->all(), 'resource.id');
            if ($captureId) {
                $pago = Pago::where('referencia', $captureId)->first();
                if ($pago) {
                    $pago->update(['estado' => 'COMPLETED']);
                }
            }
        }
        return response()->json(['ok' => true]);
    }
}
