@extends('layouts.cliente')

@section('title', 'Tienda - Crochettitos')

@section('content')
<div class="container">
    <!-- Mensaje para visitantes -->
    <div class="alert alert-info">
        <p><strong>¡Bienvenido!</strong> Explora nuestro catálogo. <a href="{{ route('login.form') }}">Inicia sesión</a> para ver detalles y realizar compras.</p>
    </div>

    <!-- Formulario de búsqueda -->
    <div class="search-container">
        <form action="{{ route('tienda') }}" method="GET">
            <input type="text" name="busqueda" placeholder="Buscar productos..." value="{{ request('busqueda') }}">
            <button type="submit">Buscar</button>
        </form>
    </div>

    <div class="shop-layout">
        <!-- Sidebar de filtros -->
        <aside class="filters-sidebar">
            <h3>Filtrar por</h3>
            
            <!-- Filtro por categoría -->
            <div class="filter-group">
                <h4>Categorías</h4>
                @foreach($categorias as $categoria)
                    <label>
                        <input type="checkbox" name="categoria" value="{{ $categoria->nombre }}"
                            {{ request('categoria') == $categoria->nombre ? 'checked' : '' }}>
                        {{ $categoria->nombre }}
                    </label>
                @endforeach
            </div>

            <!-- Filtro por precio -->
            <div class="filter-group">
                <h4>Rango de precios</h4>
                <div class="price-range">
                    <input type="number" name="precio_min" placeholder="Mínimo" value="{{ request('precio_min') }}">
                    <span>a</span>
                    <input type="number" name="precio_max" placeholder="Máximo" value="{{ request('precio_max') }}">
                </div>
            </div>

            <button type="submit" class="apply-filters">Aplicar Filtros</button>
        </aside>

        <!-- Lista de productos -->
        <main class="products-grid">
            @if(count($productos) > 0)
                @foreach($productos as $producto)
                    <div class="product-card">
                        <img src="{{ $producto->imagen_url ?? 'https://via.placeholder.com/250' }}" alt="{{ $producto->nombre }}">
                        <h3>{{ $producto->nombre }}</h3>
                        <p class="price">${{ number_format($producto->precio, 2) }}</p>
                        <p class="category">{{ $producto->categoria->nombre ?? 'Sin categoría' }}</p>
                        <button class="buy-button login-required" onclick="showLoginAlert()">Ver Detalles</button>
                    </div>
                @endforeach
            @else
                <p class="no-products">No se encontraron productos con los filtros seleccionados</p>
            @endif
        </main>
    </div>
</div>

<!-- Modal de login requerido -->
<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close-button" onclick="closeLoginModal()">&times;</span>
        <h2>Inicia Sesión</h2>
        <p>Para ver los detalles del producto y realizar compras, necesitas iniciar sesión.</p>
        <div class="modal-buttons">
            <a href="{{ route('login.form') }}" class="btn-primary">Iniciar Sesión</a>
            <a href="{{ route('registro.form') }}" class="btn-secondary">Registrarse</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showLoginAlert() {
    document.getElementById('loginModal').style.display = 'flex';
}

function closeLoginModal() {
    document.getElementById('loginModal').style.display = 'none';
}

// Cerrar modal al hacer clic fuera
window.onclick = function(event) {
    const modal = document.getElementById('loginModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>
@endpush
@endsection
