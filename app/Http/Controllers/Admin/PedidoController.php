<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pedido;

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
        ]);

        $pedido->update($data);

        return back()->with('success', 'Estado del pedido actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
