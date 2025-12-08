@extends('layouts.admin')

@section('title', 'Estadísticas')

@section('content')
    <div class="page-header">
        <h1>Panel de Estadísticas</h1>
        <p class="page-subtitle">Resumen general del rendimiento de la tienda</p>
    </div>

    <div class="dashboard-grid">
        <div class="stat-card purple">
            <div class="card-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="card-content">
                <h3>Ventas del Mes</h3>
                <div class="value">${{ number_format($ventasMes, 2) }}</div>
                <div class="label">Total generado en ventas</div>
            </div>
        </div>

        <div class="stat-card green">
            <div class="card-icon">
                <i class="fas fa-box"></i>
            </div>
            <div class="card-content">
                <h3>Productos Vendidos</h3>
                <div class="value">{{ $productosVendidos }}</div>
                <div class="label">En el mes actual</div>
            </div>
        </div>

        <div class="stat-card blue">
            <div class="card-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="card-content">
                <h3>Usuarios Activos</h3>
                <div class="value">{{ $usuariosActivos }}</div>
                <div class="label">Últimos 15 minutos</div>
                <div class="trend up">
                    <i class="fas fa-bolt"></i>
                    <span>Conteo en tiempo real</span>
                </div>
            </div>
        </div>

        <div class="stat-card yellow">
            <div class="card-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="card-content">
                <h3>Pedidos Pendientes</h3>
                <div class="value">{{ $pedidosPendientes }}</div>
                <div class="label">En proceso de envío</div>
            </div>
        </div>

        <div class="stat-card red">
            <div class="card-icon">
                <i class="fas fa-eye"></i>
            </div>
            <div class="card-content">
                <h3>Visitas del Sitio</h3>
                <div class="value">{{ $visitas }}</div>
                <div class="label">Últimos 7 días</div>
            </div>
        </div>

        <div class="stat-card purple">
            <div class="card-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="card-content">
                <h3>Tasa de Conversión</h3>
                <div class="value">
                    {{ $tasaConversion !== null ? number_format($tasaConversion, 1) . '%' : '—' }}
                </div>
                <div class="label">Ratio visitas a ventas</div>
            </div>
        </div>

        <div class="stat-card orange">
            <div class="card-icon">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="card-content">
                <h3>Peticiones Pendientes</h3>
                <div class="value" id="metric-peticionesPendientes">{{ $peticionesPendientes }}</div>
                <div class="label">Solicitudes personalizadas por responder</div>
            </div>
        </div>

        <div class="stat-card teal">
            <div class="card-icon">
                <i class="fas fa-tag"></i>
            </div>
            <div class="card-content">
                <h3>Promociones Activas</h3>
                <div class="value" id="metric-promocionesActivas">{{ $promocionesActivas }}</div>
                <div class="label">Ofertas vigentes disponibles</div>
            </div>
        </div>
    </div>

    <div class="card low-stock-section">
        <div class="card-header">
            <div class="header-content">
                <h2>
                    <i class="fas fa-exclamation-triangle warning-icon"></i>
                    Productos por Agotarse
                </h2>
                <small class="subtitle">Productos con stock menor o igual a 5 unidades</small>
            </div>
            <div class="header-actions">
                <span class="badge alert-count">{{ $lowStockProducts->count() }}</span>
            </div>
        </div>

        <div class="card-body">
            @if($lowStockProducts->isNotEmpty())
                <div class="low-stock-grid">
                    @foreach($lowStockProducts as $producto)
                        <div class="low-stock-card {{ $producto->stock <= 2 ? 'critical' : 'warning' }}">
                            <div class="product-info">
                                <div style="font-weight: 700; color: var(--text-dark); font-size: 1rem;">{{ $producto->nombre }}</div>
                                <small style="color: var(--text-muted);">ID: {{ $producto->id_producto }}</small>
                                <div class="product-category">{{ $producto->categoria->nombre ?? 'Sin categoría' }}</div>
                                <div class="stock-info">
                                    <span class="stock-level">
                                        <i class="fas fa-boxes"></i>
                                        Stock: {{ $producto->stock }}
                                    </span>
                                </div>
                            </div>
                            <div class="product-actions"></div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-check-circle success-icon"></i>
                    <h3>¡Excelente!</h3>
                    <p>No hay productos con bajo stock en este momento.</p>
                </div>
            @endif
        </div>
    </div>
@endsection