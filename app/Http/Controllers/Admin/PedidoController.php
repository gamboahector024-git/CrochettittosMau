<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pedido;
use Illuminate\Support\Facades\Session;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->input('q');
        $estado = $request->input('estado');
        $from = $request->input('from');
        $to = $request->input('to');

        $query = Pedido::with('usuario')->orderByDesc('id_pedido');

        if ($q) {
            $query->where('id_pedido', $q)
                  ->orWhereHas('usuario', function ($sub) use ($q) {
                      $sub->where('nombre', 'like', "%{$q}%")
                          ->orWhere('apellido', 'like', "%{$q}%")
                          ->orWhere('email', 'like', "%{$q}%");
                  });
        }

        if ($estado) {
            $query->where('estado', $estado);
        }

        if ($from) {
            $query->whereDate('fecha_pedido', '>=', $from);
        }
        if ($to) {
            $query->whereDate('fecha_pedido', '<=', $to);
        }

        $pedidos = $query->paginate(10)->withQueryString();

        return view('admin.pedidos.index', compact('pedidos', 'q', 'estado', 'from', 'to'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Pedido $pedido)
    {
        $pedido->load(['usuario', 'detalles.producto']);
        return view('admin.pedidos.show', compact('pedido'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pedido $pedido)
    {
        return redirect()->route('admin.pedidos.show', $pedido);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pedido $pedido)
    {
        $data = $request->validate([
            'estado' => 'required|in:pendiente,procesando,enviado,entregado,cancelado',
            'metodo_pago' => 'nullable|string|max:50',
            'empresa_envio' => 'nullable|string|max:100',
            'codigo_rastreo' => 'nullable|string|max:100',
            'fecha_envio' => 'nullable|date',
            'fecha_entrega_estimada' => 'nullable|date',
        ]);

        $pedido->fill($data);
        $pedido->save();

        Session::flash('success', 'Estado del pedido actualizado correctamente.');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Generalmente los pedidos no se eliminan, se cancelan.
    }

    /**
     * Elimina múltiples pedidos en lote.
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:pedidos,id_pedido'
        ]);

        $deleted = Pedido::whereIn('id_pedido', $validated['ids'])->delete();

        Session::flash('success', "{$deleted} pedidos eliminados correctamente.");
        return back();
    }
}
