<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Visita;
use App\Models\Usuario;
use App\Models\Producto;
use App\Models\Peticion;
use Carbon\Carbon;

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

        // Usuarios activos (métrica deshabilitada)
        $usuariosActivos = 0;

        // Pedidos pendientes
        $pedidosPendientes = Pedido::where('estado', 'pendiente')
            ->count();

        // Visitas del sitio (últimos 7 días con detalle diario)
        $finRangoDiario = Carbon::now()->startOfDay();
        $inicioRangoDiario = (clone $finRangoDiario)->subDays(6);

        $visitasAgrupadasDiarias = Visita::whereBetween('created_at', [$inicioRangoDiario, (clone $finRangoDiario)->endOfDay()])
            ->selectRaw('DATE(created_at) as fecha, COUNT(*) as total')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->pluck('total', 'fecha');

        $visitasDiariasLabels = [];
        $visitasDiariasData = [];

        for ($dia = $inicioRangoDiario->copy(); $dia->lte($finRangoDiario); $dia->addDay()) {
            $fechaClave = $dia->toDateString();
            $visitasDiariasLabels[] = $dia->locale('es')->isoFormat('DD/MM');
            $visitasDiariasData[] = (int) ($visitasAgrupadasDiarias[$fechaClave] ?? 0);
        }

        $visitas = array_sum($visitasDiariasData);

        // Visitas del sitio (últimos 6 meses con detalle mensual)
        $finRangoMensual = Carbon::now()->startOfMonth();
        $inicioRangoMensual = (clone $finRangoMensual)->subMonths(5);

        $visitasAgrupadasMensuales = Visita::whereBetween('created_at', [$inicioRangoMensual, (clone $finRangoMensual)->endOfMonth()])
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as periodo, COUNT(*) as total")
            ->groupBy('periodo')
            ->orderBy('periodo')
            ->pluck('total', 'periodo');

        $visitasMensualesLabels = [];
        $visitasMensualesData = [];

        for ($mes = $inicioRangoMensual->copy(); $mes->lte($finRangoMensual); $mes->addMonth()) {
            $periodoClave = $mes->format('Y-m');
            $visitasMensualesLabels[] = ucfirst($mes->locale('es')->isoFormat('MMM YYYY'));
            $visitasMensualesData[] = (int) ($visitasAgrupadasMensuales[$periodoClave] ?? 0);
        }

        // Contador de Peticiones
        $peticionesCount = Peticion::count();

        // Productos con stock bajo
        $lowStockProducts = Producto::where('stock', '<', 5)->orderBy('stock')->take(10)->get();

        return view('admin.dashboard', [
            'ventasMes' => $ventasMes,
            'productosVendidos' => $productosVendidos,
            'usuariosActivos' => $usuariosActivos,
            'pedidosPendientes' => $pedidosPendientes,
            'visitas' => $visitas,
            'peticionesCount' => $peticionesCount,
            'visitasDiariasLabels' => $visitasDiariasLabels,
            'visitasDiariasData' => $visitasDiariasData,
            'visitasMensualesLabels' => $visitasMensualesLabels,
            'visitasMensualesData' => $visitasMensualesData,
            'lowStockProducts' => $lowStockProducts,
        ]);
    }
}
