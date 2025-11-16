<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Carrito;
use App\Models\Producto;
use App\Models\Pedido;
use App\Models\PedidoDetalle;
use App\Models\CarritoDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CarritoController extends Controller
{
    // Obtener carrito del usuario actual
    public function index()
    {
        $userId = Auth::user()->id_usuario;
        
        // Obtener o crear el carrito del usuario (garantizado único por migración)
        $carrito = Carrito::firstOrCreate(
            ['id_usuario' => $userId]
        );

        // Cargar relaciones necesarias
        $carrito->load(['detalles.producto.categoria', 'detalles.producto.promocionActiva']);

        Log::info('Carrito.index', [
            'user_id' => $userId,
            'carrito_id' => $carrito->id_carrito ?? null,
            'detalles_count' => $carrito->detalles->count(),
            'detalle_ids' => $carrito->detalles->pluck('id_detalle')->all(),
            'producto_ids' => $carrito->detalles->pluck('id_producto')->all(),
        ]);

        return view('cliente.carrito', [
            'carrito' => $carrito
        ]);
    }

    // Agregar producto al carrito
    public function store(Request $request)
    {
        $request->validate([
            'id_producto' => 'required|exists:productos,id_producto',
            'cantidad' => 'required|integer|min:1'
        ]);

        try {
            $userId = Auth::user()->id_usuario;
            
            // Obtener o crear el carrito del usuario
            $carrito = Carrito::firstOrCreate(
                ['id_usuario' => $userId]
            );
            
            $producto = Producto::find($request->id_producto);

            if (!$producto) {
                Log::warning('Carrito.store: Producto no encontrado', ['id_producto' => $request->id_producto]);
                return redirect()->back()->with('error', 'Producto no encontrado');
            }

            Log::info('Carrito.store.before', [
                'user_id' => $userId,
                'carrito_id' => $carrito->id_carrito,
                'id_producto' => $request->id_producto,
                'cantidad' => $request->cantidad,
            ]);

            // Actualizar o crear detalle acumulando cantidad si ya existe
            DB::transaction(function () use ($carrito, $request) {
                $detalle = CarritoDetalle::where('id_carrito', $carrito->id_carrito)
                    ->where('id_producto', $request->id_producto)
                    ->lockForUpdate()
                    ->first();

                if ($detalle) {
                    $nuevaCantidad = (int) $detalle->cantidad + (int) $request->cantidad;
                    $detalle->update(['cantidad' => $nuevaCantidad]);
                    Log::info('Carrito.store: Cantidad actualizada', [
                        'id_detalle' => $detalle->id_detalle,
                        'nueva_cantidad' => $nuevaCantidad
                    ]);
                } else {
                    $nuevoDetalle = CarritoDetalle::create([
                        'id_carrito' => $carrito->id_carrito,
                        'id_producto' => $request->id_producto,
                        'cantidad' => (int) $request->cantidad,
                    ]);
                    Log::info('Carrito.store: Detalle creado', [
                        'id_detalle' => $nuevoDetalle->id_detalle,
                        'id_carrito' => $carrito->id_carrito,
                        'id_producto' => $request->id_producto,
                        'cantidad' => $request->cantidad
                    ]);
                }
            });

            $carrito->refresh();
            $carrito->load('detalles');
            
            Log::info('Carrito.store.after', [
                'carrito_id' => $carrito->id_carrito,
                'detalles_count' => $carrito->detalles->count(),
                'producto_ids' => $carrito->detalles->pluck('id_producto')->all(),
            ]);

            return redirect()->route('carrito.index')->with('success', 'Producto agregado al carrito correctamente');
        } catch (\Exception $e) {
            Log::error('Carrito.store: Excepción', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Error al agregar el producto: ' . $e->getMessage());
        }
    }

    // Actualizar cantidad en carrito
    public function update(Request $request, CarritoDetalle $detalle)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1'
        ]);

        try {
            // Verificar que el detalle pertenece al carrito del usuario autenticado
            if ($detalle->carrito->id_usuario !== Auth::user()->id_usuario) {
                Log::warning('Carrito.update: Intento de actualizar detalle de otro usuario', [
                    'user_id' => Auth::user()->id_usuario,
                    'detalle_user_id' => $detalle->carrito->id_usuario
                ]);
                return redirect()->route('carrito.index')->with('error', 'No tienes permiso para modificar este producto');
            }

            $detalle->update(['cantidad' => $request->cantidad]);

            return redirect()->route('carrito.index')->with('success', 'Cantidad actualizada correctamente');
        } catch (\Exception $e) {
            Log::error('Carrito.update: Excepción', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error al actualizar la cantidad: ' . $e->getMessage());
        }
    }

    // Eliminar producto del carrito
    public function destroy(CarritoDetalle $detalle)
    {
        try {
            // Verificar que el detalle pertenece al carrito del usuario autenticado
            if ($detalle->carrito->id_usuario !== Auth::user()->id_usuario) {
                Log::warning('Carrito.destroy: Intento de eliminar detalle de otro usuario', [
                    'user_id' => Auth::user()->id_usuario,
                    'detalle_user_id' => $detalle->carrito->id_usuario
                ]);
                return redirect()->route('carrito.index')->with('error', 'No tienes permiso para eliminar este producto');
            }

            $detalle->delete();
            // Asegurar que el carrito actualiza su updated_at tras eliminar
            $detalle->carrito->touch();

            return redirect()->route('carrito.index')->with('success', 'Producto eliminado del carrito');
        } catch (\Exception $e) {
            Log::error('Carrito.destroy: Excepción', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error al eliminar el producto: ' . $e->getMessage());
        }
    }

    // Vaciar carrito
    public function clear()
    {
        $carrito = Carrito::firstOrCreate(['id_usuario' => Auth::user()->id_usuario]);
        $carrito->detalles()->delete();
        // Tocar el carrito para reflejar actividad de vaciado
        $carrito->touch();
        
        return response()->json(['message' => 'Carrito vaciado']);
    }

    public function checkout()
    {
        $user = auth()->user();
        
        // Obtener o crear el carrito si no existe
        $carrito = Carrito::firstOrCreate(['id_usuario' => $user->id_usuario]);
        
        // Cargar relaciones necesarias
        $carrito->load(['detalles.producto']);

        if ($carrito->detalles->isEmpty()) {
            return redirect()->route('carrito.index')
                ->with('error', 'No hay productos en tu carrito');
        }

        $total = $carrito->detalles->sum(function($detalle) {
            return $detalle->cantidad * $detalle->producto->precio;
        });

        return view('cliente.carrito.checkout', [
            'items' => $carrito->detalles,
            'total' => $total,
            'usuario' => $user
        ]);
    }

    // En app/Http/Controllers/CarritoController.php
    public function procesarPedido(Request $request)
    {
        $request->validate([
            'calle' => 'required|string|max:255',
            'colonia' => 'required|string|max:255',
            'municipio_ciudad' => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:10',
            'estado' => 'required|string|max:100',
            'metodo_pago' => 'required|string|max:50'
        ]);

        $user = auth()->user();
        $carrito = Carrito::firstOrCreate(['id_usuario' => $user->id_usuario]);

        if ($carrito->detalles->isEmpty()) {
            return redirect()->back()
                ->with('error', 'No hay productos en tu carrito');
        }

        // Calcular total
        $total = $carrito->detalles->sum(function($detalle) {
            return $detalle->cantidad * $detalle->producto->precio;
        });

        // Crear el pedido
        $pedido = Pedido::create([
            'id_usuario' => $user->id_usuario,
            'total' => $total,
            'estado' => 'pendiente',
            'calle' => $request->calle,
            'colonia' => $request->colonia,
            'municipio_ciudad' => $request->municipio_ciudad,
            'codigo_postal' => $request->codigo_postal,
            'estado_direccion' => $request->estado,
            'metodo_pago' => $request->metodo_pago,
            'fecha_pedido' => now() // Asegura que sea DateTime
        ]);

        // Crear detalles del pedido
        foreach ($carrito->detalles as $detalle) {
            PedidoDetalle::create([
                'id_pedido' => $pedido->id_pedido,
                'id_producto' => $detalle->id_producto,
                'cantidad' => $detalle->cantidad,
                'precio_unitario' => $detalle->producto->precio
            ]);
        }

        // Vaciar el carrito
        $carrito->detalles()->delete();

        return redirect()->route('cliente.pedidos.index')
            ->with('success', '¡Pedido realizado con éxito!');
    }
}
?>