<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PagoController extends Controller
{
    // Obtener todos los pagos
    public function index()
    {
        return Pago::with('pedido')->get();
    }

    // Crear un nuevo pago
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pedido' => 'required|exists:pedidos,id_pedido',
            'metodo' => ['required', Rule::in(['tarjeta', 'paypal', 'transferencia', 'efectivo'])],
            'monto' => 'required|numeric|min:0',
            'estado' => ['nullable', Rule::in(['pendiente', 'completado', 'fallido'])]
        ]);

        return DB::transaction(function () use ($validated) {
            return Pago::create($validated);
        });
    }

    // Obtener un pago específico
    public function show(Pago $pago)
    {
        return $pago->load('pedido');
    }

    // Actualizar un pago
    public function update(Request $request, Pago $pago)
    {
        $validated = $request->validate([
            'metodo' => ['sometimes', Rule::in(['tarjeta', 'paypal', 'transferencia', 'efectivo'])],
            'monto' => 'sometimes|numeric|min:0',
            'estado' => ['sometimes', Rule::in(['pendiente', 'completado', 'fallido'])]
        ]);

        $pago->update($validated);
        return $pago->fresh();
    }

    // Eliminar un pago
    public function destroy(Pago $pago)
    {
        $pago->delete();
        return response()->noContent();
    }

    // Pagos de un pedido específico (endpoint adicional)
    public function byPedido(Pedido $pedido)
    {
        return $pedido->pagos;
    }
}
