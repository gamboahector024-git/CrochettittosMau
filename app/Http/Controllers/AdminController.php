<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Visita;
use App\Models\Usuario;
use App\Models\Producto;
use App\Models\Peticion;

class AdminController extends Controller
{
    public function dashboard() {
        // Ventas del mes
        $ventasMes = Pedido::where('estado', 'entregado')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        // Productos vendidos (suma de cantidades) solo en pedidos ENTREGADOS del mes actual
        $productosVendidos = Pedido::where('estado', 'entregado')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->with('detalles')
            ->get()
            ->sum(function($pedido) {
                return $pedido->detalles->sum('cantidad');
            });

        // Usuarios activos (últimos 30 días)
        $usuariosActivos = Usuario::where('fecha_registro', '>=', now()->subDays(30))
            ->count();

        // Pedidos pendientes
        $pedidosPendientes = Pedido::where('estado', 'pendiente')
            ->count();

        // Visitas del sitio (últimos 7 días)
        $visitas = Visita::where('created_at', '>=', now()->subDays(7))
            ->count();

        // Contador de Peticiones
        $peticionesCount = Peticion::count();

        return view('admin.dashboard', [
            'ventasMes' => $ventasMes,
            'productosVendidos' => $productosVendidos,
            'usuariosActivos' => $usuariosActivos,
            'pedidosPendientes' => $pedidosPendientes,
            'visitas' => $visitas,
            'peticionesCount' => $peticionesCount,
        ]);
    }
}
