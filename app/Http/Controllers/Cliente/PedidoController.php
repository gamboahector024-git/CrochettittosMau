<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    /**
     * Muestra el historial de pedidos del usuario autenticado.
     */
    public function index()
    {        
        $pedidos = Pedido::where('id_usuario', Auth::id())
                    ->orderBy('fecha_pedido', 'desc')
                    ->paginate(10);

        // Agregar número de pedido secuencial para el cliente
        $currentPage = $pedidos->currentPage();
        $perPage = $pedidos->perPage();
        $totalItems = Pedido::where('id_usuario', Auth::id())->count();
        
        $pedidos->getCollection()->transform(function ($pedido, $index) use ($currentPage, $perPage, $totalItems) {
            $pedido->numero_pedido_cliente = $totalItems - ($currentPage - 1) * $perPage - $index;
            return $pedido;
        });

        return view('cliente.pedidos.index', compact('pedidos'));
    }

    /**
     * Muestra el detalle de un pedido específico.
     */
    public function show(Pedido $pedido)
    {
        // Asegurarse de que el usuario solo pueda ver sus propios pedidos.
        if ($pedido->id_usuario !== Auth::id()) {
            abort(404); // O 403, dependiendo de la política de acceso.
        }

        // Calcular número de pedido secuencial para el cliente (mismo criterio que en el índice)
        $userId = Auth::id();

        // Total de pedidos del usuario
        $totalItems = Pedido::where('id_usuario', $userId)->count();

        // Posición del pedido en orden descendente por fecha (y id como desempate)
        $rankDesc = Pedido::where('id_usuario', $userId)
            ->where(function ($q) use ($pedido) {
                $q->where('fecha_pedido', '>', $pedido->fecha_pedido)
                    ->orWhere(function ($q2) use ($pedido) {
                        $q2->where('fecha_pedido', $pedido->fecha_pedido)
                            ->where('id_pedido', '>', $pedido->id_pedido);
                    });
            })
            ->count() + 1;

        // Mismo número que se ve en el listado (1 = primer pedido creado)
        $pedido->numero_pedido_cliente = $totalItems - $rankDesc + 1;

        // Cargar las relaciones para usarlas en la vista
        $pedido->load('detalles.producto', 'peticion');

        return view('cliente.pedidos.show', compact('pedido'));
    }
}