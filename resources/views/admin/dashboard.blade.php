@extends('layouts.admin')

@section('title', 'Estadísticas')

{{-- 
  Hemos eliminado las secciones @section('header') y @section('styles') 
  porque el nuevo 'layouts/admin.blade.php' ya no las usa y 
  'admin.css' ya contiene todos los estilos necesarios.
--}}

@section('content')
    <div class="content-header">
        <h1>Panel de Estadísticas</h1>
    </div>

    <style>
        /* Estilos mínimos para las tarjetas de productos con bajo stock */
        .low-stock-grid{display:flex;flex-wrap:wrap;gap:12px;margin-top:8px}
        .low-stock-card{background:#fff;border:1px solid #e6e6e6;padding:10px;border-radius:6px;min-width:160px;flex:1 1 180px;box-shadow:0 1px 3px rgba(0,0,0,0.04)}
        .low-stock-card .card-title{font-weight:600;margin-bottom:6px}
        .low-stock-card .card-body{color:#555}
        @media (max-width:600px){.low-stock-card{flex:1 1 100%}}
    </style>

    <div class="stats-grid">
        
        <div class="stat-card blue"> {{-- La clase "blue" viene de tu CSS y añade el borde --}}
            <div class="stat-card-header">
                <i class="fas fa-dollar-sign"></i>
                <h3>Ventas del mes</h3>
            </div>
            <div class="value">${{ number_format($ventasMes, 2) }}</div>
            <div class="label">Total generado en ventas</div>
        </div>

        <div class="stat-card green">
            <div class="stat-card-header">
                <i class="fas fa-box"></i>
                <h3>Productos vendidos</h3>
            </div>
            <div class="value">{{ $productosVendidos }}</div>
            <div class="label">En el mes actual</div>
        </div>

        <div class="stat-card purple">
            <div class="stat-card-header">
                <i class="fas fa-users"></i>
                <h3>Usuarios activos</h3>
            </div>
            <div class="value">{{ $usuariosActivos }}</div>
            <div class="label">Últimos 30 días</div>
        </div>

        <div class="stat-card yellow">
            <div class="stat-card-header">
                <i class="fas fa-shopping-cart"></i>
                <h3>Pedidos pendientes</h3>
            </div>
            <div class="value">{{ $pedidosPendientes }}</div>
            <div class="label">En proceso de envío</div>
        </div>

        <div class="stat-card red">
            <div class="stat-card-header">
                <i class="fas fa-eye"></i>
                <h3>Visitas del sitio</h3>
            </div>
            <div class="value">{{ $visitas }}</div>
            <div class="label">Últimas 24 horas</div>
        </div>

        <div class="stat-card red"> {{-- Productos por agotarse en tarjetas individuales --}}
            <div class="stat-card-header">
                <i class="fas fa-exclamation-triangle"></i>
                <h3>Productos por agotarse</h3>
            </div>
            <div class="value">
                @if($lowStockProducts->isNotEmpty())
                    <div class="low-stock-grid">
                        @foreach($lowStockProducts as $product)
                            <div class="low-stock-card">
                                <div class="card-title">{{ $product->nombre }}</div>
                                <div class="card-body">Stock: <strong>{{ $product->stock }}</strong></div>
                                <div style="margin-top:8px;">
                                    @if(isset($product->id_producto))
                                        <a href="{{ route('admin.productos.edit', $product->id_producto) }}" class="btn btn-sm btn-primary">Editar</a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    Ningún producto con bajo stock
                @endif
            </div>
            <div class="label">Primeros 10 productos con poco stock &lt; 5</div>
        </div>

        {{-- Puedes añadir más tarjetas aquí si es necesario --}}

    </div>
@endsection