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
                    <img src="{{ asset($carrusel->imagen) }}" alt="Imagen carrusel #{{ $loop->iteration }}">
                </div>
            @empty
                <div class="carousel-slide">
                    <img src="https://via.placeholder.com/1200x400/dedede/555?text=Sin+imagenes+disponibles" alt="Sin imágenes">
                </div>
            @endforelse
        </div>
        <div class="carousel-dots"></div>
    </section>
    
    {{-- Buscador Principal --}}
    <div class="search-container">
        <form action="{{ route('tienda') }}" method="GET">
            {{-- Mantenemos los filtros actuales al buscar --}}
            @foreach(request('categorias', []) as $cat)
                <input type="hidden" name="categorias[]" value="{{ $cat }}">
            @endforeach
            <input type="hidden" name="precio_min" value="{{ request('precio_min') }}">
            <input type="hidden" name="precio_max" value="{{ request('precio_max') }}">

            <input type="text" name="busqueda" placeholder="Buscar productos..." value="{{ request('busqueda') }}">
            <button type="submit">Buscar</button>
        </form>
    </div>

    <div class="shop-layout">
        {{-- =============================================== --}}
        {{-- ============ SIDEBAR DE FILTROS ================ --}}
        {{-- =============================================== --}}
        <aside class="filters-sidebar">
            <h3>Filtrar por</h3>

            <form action="{{ route('tienda') }}" method="GET" id="filterForm">
                {{-- Mantenemos la búsqueda si existe --}}
                @if(request('busqueda'))
                    <input type="hidden" name="busqueda" value="{{ request('busqueda') }}">
                @endif

                {{-- FILTRO DE CATEGORÍAS (Multi-selección) --}}
                <div class="filter-group">
                    <h4>Categorías</h4>
                    <div class="checkbox-list">
                        @foreach($categorias as $categoria)
                            <label class="custom-checkbox">
                                <input type="checkbox" name="categorias[]" value="{{ $categoria->nombre }}"
                                    {{ in_array($categoria->nombre, request('categorias', [])) ? 'checked' : '' }}>
                                <span class="checkmark-box"></span>
                                <span class="label-text">{{ $categoria->nombre }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- FILTRO DE PRECIO --}}
                <div class="filter-group">
                    <h4>Rango de precios</h4>
                    <div class="price-inputs">
                        <div class="input-wrapper">
                            <span class="currency-symbol">$</span>
                            <input type="number" name="precio_min" placeholder="Min" 
                                   value="{{ request('precio_min') }}" min="0">
                        </div>
                        <span class="separator">-</span>
                        <div class="input-wrapper">
                            <span class="currency-symbol">$</span>
                            <input type="number" name="precio_max" placeholder="Max" 
                                   value="{{ request('precio_max') }}" min="0">
                        </div>
                    </div>
                </div>

                {{-- ORDENAR POR --}}
                <div class="filter-group">
                    <h4>Ordenar por</h4>
                    <select name="orden" class="styled-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="recientes" {{ request('orden') == 'recientes' ? 'selected' : '' }}>Más Recientes</option>
                        <option value="precio_asc" {{ request('orden') == 'precio_asc' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                        <option value="precio_desc" {{ request('orden') == 'precio_desc' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                        <option value="nombre_asc" {{ request('orden') == 'nombre_asc' ? 'selected' : '' }}>Nombre: A-Z</option>
                        <option value="nombre_desc" {{ request('orden') == 'nombre_desc' ? 'selected' : '' }}>Nombre: Z-A</option>
                    </select>
                </div>

                <button type="submit" class="apply-filters-btn">Aplicar Filtros</button>
                
                @if(request()->hasAny(['categorias', 'precio_min', 'precio_max', 'busqueda']))
                    <a href="{{ route('tienda') }}" class="clear-filters">Limpiar todos los filtros</a>
                @endif
            </form>
        </aside>

        {{-- =============================================== --}}
        {{-- ============ GRILLA DE PRODUCTOS =============== --}}
        {{-- =============================================== --}}
        <main class="products-grid">
            @if($productos->count())
                @foreach($productos as $producto)
                    
                    @php
                        $oferta = $producto->promocionActiva;
                        $precioFinal = $producto->precio_promocional ?? $producto->precio;
                        $precioOriginalNum = ($oferta) ? $producto->precio : null;
                        $badge = null;

                        if ($oferta) {
                            if ($oferta->tipo === 'porcentaje') {
                                $badge = $oferta->valor . '% OFF';
                            } else {
                                $badge = '$' . number_format($oferta->valor, 2) . ' OFF';
                            }
                        }
                    @endphp

                    <div class="product-card">
                        <div class="card-image-wrapper">
                            <img src="{{ $producto->imagen_url ?? 'https://via.placeholder.com/250' }}" alt="{{ $producto->nombre }}">
                            @if($oferta)
                                <span class="discount-badge-card">{{ $badge }}</span>
                            @endif
                        </div>
                        <div class="card-content">
                            <h3>{{ $producto->nombre }}</h3>
                            
                            <p class="price">
                                ${{ number_format($precioFinal, 2) }}
                                @if($oferta)
                                    <span class="original-price-card">${{ number_format($precioOriginalNum, 2) }}</span>
                                @endif
                            </p>
                            
                            <button class="buy-button" onclick="openModal(
                                {{ $producto->id_producto }},
                                {{ json_encode($producto->nombre) }},
                                '{{ number_format($precioFinal, 2) }}',
                                {{ json_encode($producto->descripcion ?? 'Sin descripción.') }},
                                '{{ $producto->imagen_url ?? 'https://via.placeholder.com/250' }}',
                                '{{ $producto->categoria->nombre ?? 'Sin categoría' }}',
                                {{ $oferta ? 'true' : 'false' }},
                                {{ $badge ? json_encode($badge) : 'null' }},
                                {{ $precioOriginalNum ? "'".number_format($precioOriginalNum, 2)."'" : 'null' }}
                            )">
                                Ver Detalles
                            </button>
                        </div>
                    </div>
                @endforeach
            @else
                {{-- Mensaje Empty State --}}
                <div class="no-products" style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                    <h3 style="color: #888;">No encontramos coincidencias 🧶</h3>
                    <p>Intenta ajustar tus filtros o buscar con otra palabra.</p>
                </div>
            @endif
        </main>
    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/cliente/tienda-carousel.js')
@endpush