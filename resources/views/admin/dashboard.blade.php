@extends('layouts.admin')

@section('title', 'Estadísticas')

@section('content')
    {{-- 1. Usamos el H1 directamente. Tu CSS ya lo estiliza. --}}
    <h1>Panel de Estadísticas</h1>

    {{-- 2. Corregimos el nombre de la clase a "dashboard-grid" --}}
    <div class="dashboard-grid">
        
        {{-- 3. Corregimos la estructura interna de TODAS las tarjetas. --}}
        {{-- Ya no existe ".stat-card-header". --}}

        {{-- Tarjeta 1: Ventas del mes --}}
        <div class="stat-card purple"> {{-- 'purple' es el rosa fuerte en tu CSS --}}
            <div class="card-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <h3>Ventas del mes</h3>
            <div class="value">${{ number_format($ventasMes, 2) }}</div>
            <div class="label">Total generado en ventas</div>
        </div>

        {{-- Tarjeta 2: Productos vendidos --}}
        <div class="stat-card green">
            <div class="card-icon">
                <i class="fas fa-box"></i>
            </div>
            <h3>Productos vendidos</h3>
            <div class="value">{{ $productosVendidos }}</div>
            <div class="label">En el mes actual</div>
        </div>

        {{-- Tarjeta 3: Usuarios activos --}}
        <div class="stat-card blue"> {{-- 'blue' es el rosa suave en tu CSS --}}
            <div class="card-icon">
                <i class="fas fa-users"></i>
            </div>
            <h3>Usuarios activos</h3>
            <div class="value">{{ $usuariosActivos }}</div>
            <div class="label">Últimos 30 días</div>
        </div>

        {{-- Tarjeta 4: Pedidos pendientes --}}
        <div class="stat-card yellow">
            <div class="card-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h3>Pedidos pendientes</h3>
            <div class="value">{{ $pedidosPendientes }}</div>
            <div class="label">En proceso de envío</div>
        </div>

        {{-- Tarjeta 5: Visitas --}}
        <div class="stat-card red"> {{-- 'red' es rosa fuerte en tu CSS --}}
            <div class="card-icon">
                <i class="fas fa-eye"></i>
            </div>
            <h3>Visitas del sitio</h3>
            <div class="value">{{ $visitas }}</div>
            <div class="label">Últimas 24 horas</div>
        </div>
        
        {{-- 
          La tarjeta de "Bajo Stock" ha sido movida 
          afuera de este grid.
        --}}

    </div> {{-- Fin de .dashboard-grid --}}


    {{-- 
      4. SECCIÓN DE BAJO STOCK (AHORA SEPARADA)
      Usamos la clase ".card" que es tu contenedor "glass" genérico.
    --}}
    <div class="card">
        {{-- Usamos la cabecera estándar de tu CSS --}}
        <div class="actions-header" style="margin-bottom: 20px;">
            {{-- Usamos un <h2> simple para el título de la tarjeta --}}
            <h2 style="font-size: 1.3rem; font-weight: 500; color: var(--text-light); margin: 0;">
                <i class="fas fa-exclamation-triangle" style="color: var(--accent); margin-right: 8px;"></i>
                Productos por Agotarse
            </h2>
            <small class="label">Productos con stock menor o igual a 5</small>
        </div>

        @if($lowStockProducts->isNotEmpty())
            {{-- 
              Usamos las clases "low-stock-grid" y "low-stock-card"
              que moveremos a tu admin.css
            --}}
            <div class="low-stock-grid">
                @foreach($lowStockProducts as $product)
                    <div class="low-stock-card">
                        <div class="card-title">{{ $product->nombre }}</div>
                        <div class="card-body">Stock: <strong>{{ $product->stock }}</strong></div>
                        <div class="card-actions">
                            @if(isset($product->id_producto))
                                {{-- 
                                  Usamos los estilos de botón de tu CSS.
                                  Añadimos un estilo en línea para hacerlo más pequeño.
                                --}}
                                <a href="{{ route('admin.productos.edit', $product->id_producto) }}" 
                                   class="btn btn-primary" 
                                   style="padding: 5px 10px; font-size: 0.8rem;">
                                    Editar
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Usamos la clase "empty-row" de tu CSS --}}
            <p class="empty-row" style="padding: 20px 0;">
                ¡Felicidades! Ningún producto con bajo stock.
            </p>
        @endif
    </div>

@endsection