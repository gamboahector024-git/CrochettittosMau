@extends('layouts.cliente')

@section('title', 'Tienda - Crochettitos')

@section('content')
<div class="container">
    <!-- Mensajes de éxito -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

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
                        <p class="category">{{ $producto->categoria->nombre }}</p>
                        <button class="buy-button" onclick="openModal(
                            '{{ addslashes($producto->nombre) }}',
                            '{{ number_format($producto->precio, 2) }}',
                            '{{ addslashes($producto->descripcion) }}',
                            '{{ addslashes($producto->imagen_url ?? 'https://via.placeholder.com/250') }}'
                        )">Ver Detalles</button>
                    </div>
                @endforeach
            @else
                <p class="no-products">No se encontraron productos con los filtros seleccionados</p>
            @endif
        </main>
    </div>
</div>

<!-- Modal de producto -->
<div id="productModal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <img id="modalImage" src="" alt="Imagen del Producto" class="modal-image">
        <h2 id="modalName"></h2>
        <p id="modalPrice" class="modal-price"></p>
        <p id="modalDescription" class="modal-description"></p>
    </div>
</div>

<script src="{{ asset('js/main.js') }}"></script>
@endsection