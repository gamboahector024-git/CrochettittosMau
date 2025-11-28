@extends('layouts.admin')

@section('title', 'Estadísticas')

@section('content')
    <!-- Encabezado mejorado -->
    <div class="page-header">
        <h1>Panel de Estadísticas</h1>
        <p class="page-subtitle">Resumen general del rendimiento de la tienda</p>
    </div>

    <!-- Grid de estadísticas principales -->
    <div class="dashboard-grid">
        <!-- Tarjeta 1: Ventas del mes -->
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

        <!-- Tarjeta 2: Productos vendidos -->
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

        <!-- Tarjeta 3: Usuarios activos -->
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

        <!-- Tarjeta 4: Pedidos pendientes -->
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

        <!-- Tarjeta 5: Visitas del sitio -->
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

        <!-- Tarjeta 6: Tasa de conversión -->
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
    </div>

    <!-- Sección de productos con bajo stock -->
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
                            <div class="product-actions">
                                @if(isset($product->id_producto))
                                    <a href="{{ route('admin.productos.edit', $product->id_producto) }}" 
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                        Editar Stock
                                    </a>
                                @endif
                            </div>
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

@section('styles')
<style>
    /* Estilos específicos para el dashboard */
    .page-header {
        margin-bottom: 2.5rem;
        text-align: center;
    }

    .page-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--accent);
        margin-bottom: 0.5rem;
    }

    .page-subtitle {
        color: var(--text-muted);
        font-size: 1.1rem;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .stat-card {
        background: var(--bg-card);
        backdrop-filter: var(--glass-blur);
        border: 1px solid var(--border);
        border-radius: var(--border-radius);
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
        transition: all 0.4s ease;
        box-shadow: var(--glass-shadow);
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        border-color: var(--accent);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .card-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        background: rgba(213, 105, 137, 0.1);
        color: var(--accent);
        flex-shrink: 0;
    }

    .card-content {
        flex: 1;
    }

    .stat-card h3 {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--accent);
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .label {
        color: var(--text-muted);
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }

    .trend {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .trend.up {
        color: var(--accent-green);
    }

    .trend.down {
        color: #ef4444;
    }

    .trend.neutral {
        color: var(--text-muted);
    }

    .low-stock-section .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-light);
    }

    .header-content h2 {
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--text-dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .warning-icon {
        color: var(--accent);
    }

    .subtitle {
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    .alert-count {
        background: var(--accent);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .low-stock-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1rem;
    }

    .low-stock-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--border-radius-sm);
        padding: 1.25rem;
        transition: all 0.3s ease;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .low-stock-card.warning {
        border-left: 4px solid var(--accent-pink-soft);
    }

    .low-stock-card.critical {
        border-left: 4px solid #ef4444;
    }

    .low-stock-card:hover {
        transform: translateY(-2px);
        border-color: var(--accent);
    }

    .product-name {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
    }

    .product-category {
        color: var(--text-muted);
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }

    .stock-level {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-muted);
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-muted);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--accent-green);
    }

    .empty-state h3 {
        margin-bottom: 0.5rem;
        color: var(--text-dark);
    }

    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .activity-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.05);
        border-radius: var(--border-radius-sm);
        transition: all 0.3s ease;
    }

    .activity-item:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .activity-icon.success {
        background: rgba(194, 220, 128, 0.2);
        color: var(--accent-green);
    }

    .activity-icon.info {
        background: rgba(234, 156, 175, 0.2);
        color: var(--accent-pink-soft);
    }

    .activity-icon.warning {
        background: rgba(253, 253, 150, 0.2);
        color: #d97706;
    }

    .activity-content {
        flex: 1;
    }

    .activity-message {
        font-weight: 500;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
    }

    .activity-time {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
    }

    @media (max-width: 768px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
        
        .low-stock-grid {
            grid-template-columns: 1fr;
        }
        
        .stat-card {
            flex-direction: column;
            text-align: center;
        }
        
        .card-icon {
            align-self: center;
        }
    }
</style>
@endsection