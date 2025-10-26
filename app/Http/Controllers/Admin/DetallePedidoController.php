<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DetallePedidoController extends Controller
{
    public function index()
    {
        return DetallePedido::with(['pedido', 'producto'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pedido' => 'required|exists:pedidos,id_pedido',
            'id_producto' => 'required|exists:productos,id_producto',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric|min:0'
        ]);

        return DB::transaction(function () use ($validated) {
            return DetallePedido::create($validated);
        });
    }

    public function show(DetallePedido $detalle)
    {
        return $detalle->load(['pedido', 'producto']);
    }

    public function update(Request $request, DetallePedido $detalle)
    {
        $validated = $request->validate([
            'cantidad' => 'sometimes|integer|min:1',
            'precio_unitario' => 'sometimes|numeric|min:0'
        ]);

        $detalle->update($validated);
        return $detalle->fresh();
    }

    public function destroy(DetallePedido $detalle)
    {
        $detalle->delete();
        return response()->noContent();
    }

    // Detalles de un pedido específico
    public function byPedido(Pedido $pedido)
    {
        return $pedido->detalles()->with('producto')->get();
    }
}
