<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

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
        $data = $this->getDashboardData();
        return view('admin.dashboard', $data);
    }

    public function dashboardStats(Request $request)
    {
        $data = $this->getDashboardData();
        return response()->json([
            'ventasMes' => $data['ventasMes'],
            'productosVendidos' => $data['productosVendidos'],
            'usuariosActivos' => $data['usuariosActivos'],
            'pedidosPendientes' => $data['pedidosPendientes'],
            'visitas' => $data['visitas'],
            'tasaConversion' => $data['tasaConversion'],
            'peticionesPendientes' => $data['peticionesPendientes'],
            'promocionesActivas' => $data['promocionesActivas'],
            'lowStockCount' => $data['lowStockProducts']->count(),
            'lowStockProducts' => $data['lowStockProducts']->map(function ($p) {
                return [
                    'id_producto' => $p->id_producto,
                    'nombre' => $p->nombre,
                    'stock' => $p->stock,
                    'categoria' => optional($p->categoria)->nombre,
                ];
            })->values(),
        ]);
    }

    private function getDashboardData(): array
    {
        $now = Carbon::now();

        $ventasMes = Pedido::where('estado', 'entregado')
            ->whereBetween('created_at', [$now->copy()->startOfMonth(), $now])
            ->sum('total');

        $ventasMesAnterior = Pedido::where('estado', 'entregado')
            ->whereBetween('created_at', [
                $now->copy()->subMonth()->startOfMonth(),
                $now->copy()->subMonth()->endOfMonth(),
            ])->sum('total');

        $variacionVentas = $this->calculateTrend($ventasMesAnterior, $ventasMes);

        $productosVendidos = Pedido::where('estado', 'entregado')
            ->whereBetween('created_at', [$now->copy()->startOfMonth(), $now])
            ->with('detalles')
            ->get()
            ->sum(function ($pedido) {
                return $pedido->detalles->sum('cantidad');
            });

        $productosVendidosAnterior = Pedido::where('estado', 'entregado')
            ->whereBetween('created_at', [
                $now->copy()->subMonth()->startOfMonth(),
                $now->copy()->subMonth()->endOfMonth(),
            ])
            ->with('detalles')
            ->get()
            ->sum(function ($pedido) {
                return $pedido->detalles->sum('cantidad');
            });

        $variacionProductos = $this->calculateTrend($productosVendidosAnterior, $productosVendidos);

        $usuariosActivos = Usuario::whereNotNull('ultima_actividad')
            ->where('ultima_actividad', '>=', $now->copy()->subMinutes(15))
            ->count();

        $pedidosPendientes = Pedido::where('estado', 'pendiente')->count();

        $pedidosPendientesAnterior = Pedido::where('estado', 'pendiente')
            ->whereBetween('created_at', [
                $now->copy()->subMonth()->startOfMonth(),
                $now->copy()->subMonth()->endOfMonth(),
            ])->count();

        $variacionPedidosPendientes = $this->calculateTrend($pedidosPendientesAnterior, $pedidosPendientes);

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

        $visitasPrevias = Visita::whereBetween('created_at', [
                $inicioRangoDiario->copy()->subDays(7),
                $inicioRangoDiario->copy()->subSecond(),
            ])->count();

        $variacionVisitas = $this->calculateTrend($visitasPrevias, $visitas);

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
        $peticionesPendientes = Peticion::where('estado', 'pendiente')->count();

        // Promociones activas
        $promocionesActivas = \App\Models\Promocion::where('activa', true)->count();

        // Productos con stock bajo
        $lowStockProducts = Producto::with('categoria')
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->take(10)
            ->get();

        $activityEvents = [];

        if ($lastPedido = Pedido::latest('created_at')->first()) {
            $activityEvents[] = [
                'icon' => 'fas fa-shopping-cart',
                'variant' => 'success',
                'message' => "Pedido #{$lastPedido->id_pedido} ({$lastPedido->estado})",
                'timestamp' => $lastPedido->created_at ?? $lastPedido->fecha_pedido,
            ];
        }

        if ($lastUsuario = Usuario::orderByDesc('fecha_registro')->first()) {
            $activityEvents[] = [
                'icon' => 'fas fa-user',
                'variant' => 'info',
                'message' => "Nuevo usuario: {$lastUsuario->nombre}",
                'timestamp' => $lastUsuario->fecha_registro,
            ];
        }

        if ($lastPeticion = Peticion::latest()->first()) {
            $activityEvents[] = [
                'icon' => 'fas fa-lightbulb',
                'variant' => 'warning',
                'message' => "Nueva petición: {$lastPeticion->titulo}",
                'timestamp' => $lastPeticion->created_at,
            ];
        }

        $recentActivity = collect($activityEvents)
            ->filter(fn ($event) => !empty($event['timestamp']))
            ->map(function ($event) {
                $event['time'] = Carbon::parse($event['timestamp'])->locale('es')->diffForHumans();
                return $event;
            })
            ->sortByDesc(fn ($event) => $event['timestamp'])
            ->take(4)
            ->values()
            ->all();

        $tasaConversion = $visitas > 0 ? ($productosVendidos / max($visitas, 1)) * 100 : null;

        $tasaConversionAnterior = $visitasPrevias > 0
            ? ($productosVendidosAnterior / max($visitasPrevias, 1)) * 100
            : null;

        $variacionConversion = $this->calculateTrend($tasaConversionAnterior, $tasaConversion, precision: 1);

        return [
            'ventasMes' => $ventasMes,
            'productosVendidos' => $productosVendidos,
            'usuariosActivos' => $usuariosActivos,
            'pedidosPendientes' => $pedidosPendientes,
            'visitas' => $visitas,
            'peticionesCount' => $peticionesCount,
            'peticionesPendientes' => $peticionesPendientes,
            'promocionesActivas' => $promocionesActivas,
            'visitasDiariasLabels' => $visitasDiariasLabels,
            'visitasDiariasData' => $visitasDiariasData,
            'visitasMensualesLabels' => $visitasMensualesLabels,
            'visitasMensualesData' => $visitasMensualesData,
            'lowStockProducts' => $lowStockProducts,
            'variacionVentas' => $variacionVentas,
            'variacionProductos' => $variacionProductos,
            'variacionPedidosPendientes' => $variacionPedidosPendientes,
            'variacionVisitas' => $variacionVisitas,
            'tasaConversion' => $tasaConversion,
            'variacionConversion' => $variacionConversion,
            'recentActivity' => $recentActivity,
        ];
    }

    private function calculateTrend($previous, $current, int $precision = 0): ?array
    {
        if ($previous === null && $current === null) {
            return null;
        }

        if (empty($previous) && empty($current)) {
            return null;
        }

        if (empty($previous)) {
            return [
                'direction' => 'up',
                'percentage' => null,
            ];
        }

        $change = $current - $previous;
        $percentage = $previous == 0
            ? null
            : round(($change / $previous) * 100, $precision);

        return [
            'direction' => $change >= 0 ? 'up' : 'down',
            'percentage' => $percentage,
        ];
    }
}
