<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Producto;
use App\Models\CarritoDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarritoController extends Controller
{
    // Obtener carrito del usuario actual
    public function index()
    {
        $carrito = Carrito::firstOrCreate(['id_usuario' => Auth::id()]);
        return $carrito->load('detalles.producto');
    }

    // Agregar producto al carrito
    public function store(Request $request)
    {
        $request->validate([
            'id_producto' => 'required|exists:productos,id_producto',
            'cantidad' => 'required|integer|min:1'
        ]);

        $carrito = Carrito::firstOrCreate(['id_usuario' => Auth::id()]);
        $producto = Producto::find($request->id_producto);

        // Actualizar o crear detalle
        CarritoDetalle::updateOrCreate(
            ['id_carrito' => $carrito->id_carrito, 'id_producto' => $request->id_producto],
            [
                'cantidad' => $request->cantidad,
                'precio_unitario' => $producto->precio
            ]
        );

        return $carrito->load('detalles.producto');
    }

    // Actualizar cantidad en carrito
    public function update(Request $request, $id_detalle)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1'
        ]);

        $detalle = CarritoDetalle::findOrFail($id_detalle);
        $detalle->update(['cantidad' => $request->cantidad]);

        return $detalle->carrito->load('detalles.producto');
    }

    // Eliminar producto del carrito
    public function destroy($id_detalle)
    {
        $detalle = CarritoDetalle::findOrFail($id_detalle);
        $carrito = $detalle->carrito;
        $detalle->delete();

        return $carrito->load('detalles.producto');
    }

    // Vaciar carrito
    public function clear()
    {
        $carrito = Carrito::firstOrCreate(['id_usuario' => Auth::id()]);
        $carrito->detalles()->delete();
        
        return response()->json(['message' => 'Carrito vaciado']);
    }
}