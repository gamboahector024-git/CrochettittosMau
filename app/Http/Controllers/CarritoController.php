<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Producto;
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
}