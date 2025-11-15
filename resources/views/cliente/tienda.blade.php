@extends('layouts.cliente')

@section('title', 'Tienda - Crochettittos')

@section('content')
<div class="container">
    <div class="welcome-message">
        <h2>¡Bienvenido a Crochettittos! 🧶</h2>
        <p>
            En nuestra tienda encontrarás un universo de creaciones tejidas con amor y dedicación.
            Desde llaveros adorables y flores únicas, hasta piezas personalizadas para ti o tus seres queridos.  
            Aquí hay un poquito de todo... pero siempre hecho con mucho corazón 💖.
        </p>
    </div>

    <section class="image-carousel">
        <div class="carousel-track">
            
            @forelse($carruseles as $carrusel)
                <div class="carousel-slide">
                    <img src="{{ asset($carrusel->imagen) }}" alt="Imagen del carrusel">
                </div>
            @empty
                <div class="carousel-slide">
                    <img src="https://via.placeholder.com/1200x400/A77BFF/FFFFFF?text=Tu+Imagen+Principal+Aqu%C3%AD" alt="Imagen de Bienvenida 1">
                </div>
            @endforelse

        </div>
        <div class="carousel-dots"></div>
    </section>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="search-container">
        <form action="{{ route('tienda') }}" method="GET">
            <input type="text" name="busqueda" placeholder="Buscar productos..." value="{{ request('busqueda') }}">
            <button type="submit">Buscar</button>
        </form>
    </div>

    <div class="shop-layout">
        <aside class="filters-sidebar">
            <h3>Filtrar por</h3>

            <form action="{{ route('tienda') }}" method="GET">
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

                <div class="filter-group">
                    <h4>Rango de precios</h4>
                    <div class="price-range">
                        <input type="number" name="precio_min" placeholder="Mínimo" value="{{ request('precio_min') }}">
                        <span>a</span>
                        <input type="number" name="precio_max" placeholder="Máximo" value="{{ request('precio_max') }}">
                    </div>
                </div>

                <select name="orden" onchange="this.form.submit()">
                    <option value="">Ordenar por</option>
                    <option value="precio_asc" {{ request('orden') == 'precio_asc' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                    <option value="precio_desc" {{ request('orden') == 'precio_desc' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                    <option value="nombre_asc" {{ request('orden') == 'nombre_asc' ? 'selected' : '' }}>Nombre: A-Z</option>
                    <option value="nombre_desc" {{ request('orden') == 'nombre_desc' ? 'selected' : '' }}>Nombre: Z-A</option>
                </select>

                <button type="submit" class="apply-filters">Aplicar Filtros</button>
            </form>
        </aside>

        <!-- ===== GRID DE PRODUCTOS (CON COMPONENTES) ===== -->
        <main class="products-grid">
            @if($productos->count())
                @foreach($productos as $producto)
                    <x-product-card :producto="$producto" />
                @endforeach
            @else
                <p class="no-products">No se encontraron productos con los filtros seleccionados.</p>
            @endif
        </main>
    </div>
</div>

{{-- ¡ELIMINADO! El <dialog id="productModal"> duplicado ya no está aquí --}}

@endsection

{{-- JAVASCRIPT EXCLUSIVO PARA ESTA PÁGINA (EL CARRUSEL) --}}
@push('scripts')
<script src="{{ asset('js/carousel.js') }}?v=1"></script>
@endpush