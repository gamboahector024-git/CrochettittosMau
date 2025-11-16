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
            
            <div class="carousel-slide">
                {{-- <img src="{{ asset('img/image_a2e101.jpg') }}" alt="Amigurumi Especial"> --}}
                <img src="https://via.placeholder.com/1200x400/A77BFF/FFFFFF?text=Tu+Imagen+Principal+Aqu%C3%BA" alt="Imagen de Bienvenida 1">
            </div>
            
            <div class="carousel-slide">
                <img src="https://via.placeholder.com/1200x400/5B8CFF/FFFFFF?text=Novedades" alt="Novedades">
            </div>
            
            <div class="carousel-slide">
                <img src="https://via.placeholder.com/1200x400/AEE6ED/FFFFFF?text=Hecho+a+Mano" alt="Hecho a Mano">
            </div>

        </div>
        <div class="carousel-dots"></div>
    </section>
    
    {{-- (Bloque de mensajes flash eliminado correctamente) --}}

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

                {{-- =============================================== --}}
                {{-- ====== INICIO DE LA CORRECCIÓN ESTÉTICA ====== --}}
                {{-- =============================================== --}}
                
                {{-- 1. Añadimos el .filter-group que faltaba --}}
                <div class="filter-group">
                    <h4>Ordenar por</h4>
                    
                    {{-- 2. Añadimos la clase 'filter-select' y eliminamos el 'onchange' --}}
                    <select name="orden" class="filter-select">
                        {{-- 
                          Cambiamos la primera opción para que sea más clara
                          y no tenga un 'value' vacío.
                        --}}
                        <option value="recientes" {{ request('orden') == 'recientes' ? 'selected' : '' }}>Más Recientes</option>
                        <option value="precio_asc" {{ request('orden') == 'precio_asc' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                        <option value="precio_desc" {{ request('orden') == 'precio_desc' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                        <option value="nombre_asc" {{ request('orden') == 'nombre_asc' ? 'selected' : '' }}>Nombre: A-Z</option>
                        <option value="nombre_desc" {{ request('orden') == 'nombre_desc' ? 'selected' : '' }}>Nombre: Z-A</option>
                    </select>
                </div>
                
                {{-- =============================================== --}}
                {{-- ======== FIN DE LA CORRECCIÓN ESTÉTICA ======== --}}
                {{-- =============================================== --}}

                <button type="submit" class="apply-filters">Aplicar Filtros</button>
            </form>
        </aside>

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
                <p class="no-products">No se encontraron productos con los filtros seleccionados.</p>
            @endif
        </main>
    </div>
</div>
@endsection

{{-- JAVASCRIPT EXCLUSIVO PARA ESTA PÁGINA (EL CARRUSEL) --}}
@push('scripts')
<script>
    // Espera a que el DOM esté cargado
    document.addEventListener('DOMContentLoaded', () => {
        const carouselTrack = document.querySelector('.carousel-track');
        
        if (carouselTrack) {
            const slides = Array.from(carouselTrack.children);
            const dotsContainer = document.querySelector('.carousel-dots');
            let slideIndex = 0;
            let intervalId;

            if (slides.length === 0) return;
            
            slides.forEach(slide => {
                carouselTrack.appendChild(slide.cloneNode(true));
            });

            function goToSlide(index, smooth = true) {
                const slides = Array.from(carouselTrack.children);
                if (slides.length === 0 || !slides[0]) return;
                const currentSlideWidth = slides[0].getBoundingClientRect().width;
                if (!smooth) carouselTrack.style.transition = 'none';
                carouselTrack.style.transform = `translateX(-${index * currentSlideWidth}px)`;
                if (!smooth) {
                    carouselTrack.offsetHeight; 
                    carouselTrack.style.transition = 'transform 0.8s ease-in-out';
                }
                
                let activeDotIndex = index % (slides.length / 2);
                dots.forEach(dot => dot.classList.remove('active'));
                if (dots[activeDotIndex]) {
                    dots[activeDotIndex].classList.add('active');
                }
            }

            function autoSlide() {
                const slides = Array.from(carouselTrack.children);
                const totalSlides = slides.length / 2;
                
                slideIndex++;
                goToSlide(slideIndex);

                if (slideIndex >= totalSlides) {
                    setTimeout(() => {
                        slideIndex = 0;
                        goToSlide(slideIndex, false);
                    }, 800); 
                }
            }
            
            const dots = [];
            const originalSlidesCount = slides.length;
            for(let i = 0; i < originalSlidesCount; i++) {
                const dot = document.createElement('span');
                dot.classList.add('dot');
                if (i === 0) dot.classList.add('active');
                dot.addEventListener('click', () => {
                    slideIndex = i;
                    goToSlide(slideIndex);
                    resetAutoSlide();
                });
                dotsContainer.appendChild(dot);
                dots.push(dot);
            }

            let intervalId = setInterval(autoSlide, 5000); 

            function resetAutoSlide() {
                clearInterval(intervalId);
                intervalId = setInterval(autoSlide, 5000);
            }

            window.addEventListener('resize', () => {
                goToSlide(slideIndex, false);
            });
        }
    });
</script>
@endpush