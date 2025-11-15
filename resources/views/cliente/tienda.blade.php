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
                <img src="Captura de pantalla 2025-11-11 011338" alt="Imagen de Bienvenida 1">
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

                <button type="submit" class="apply-filters">Aplicar Filtros</button>
            </form>
        </aside>

        <!-- ===== GRID DE PRODUCTOS (CON ONCLICK CORREGIDO) ===== -->
        <main class="products-grid">
            @if($productos->count())
                @foreach($productos as $producto)
                    
                    {{-- 1. Pre-calculamos los valores de la oferta --}}
                    @php
                        $oferta = $producto->promocionActiva;
                        // Usamos el precio promocional si existe, si no, el precio normal
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
                            
                            {{-- Lógica de precio para mostrar el precio original tachado --}}
                            <p class="price">
                                ${{ number_format($precioFinal, 2) }}
                                @if($oferta)
                                    <span class="original-price-card">${{ number_format($precioOriginalNum, 2) }}</span>
                                @endif
                            </p>
                            
                            {{-- 
                              ===== ONCLICK CORREGIDO =====
                              Llama a la función 'openModal' de main.js con todos los 9 argumentos.
                              Ahora SÍ pasa el id_producto real.
                            --}}
                            <button class="buy-button" onclick="openModal(
                                {{ $producto->id_producto }}, {{-- 1. ID de Producto REAL --}}
                                {{ json_encode($producto->nombre) }}, {{-- 2. Nombre --}}
                                '{{ number_format($precioFinal, 2) }}', {{-- 3. Precio Final --}}
                                {{ json_encode($producto->descripcion ?? 'Sin descripción.') }}, {{-- 4. Descripción --}}
                                '{{ $producto->imagen_url ?? 'https://via.placeholder.com/250' }}', {{-- 5. Imagen --}}
                                '{{ $producto->categoria->nombre ?? 'Sin categoría' }}', {{-- 6. Categoría --}}
                                {{ $oferta ? 'true' : 'false' }}, {{-- 7. PromocionActiva (booleano) --}}
                                {{ $badge ? json_encode($badge) : 'null' }}, {{-- 8. Badge (texto del descuento) --}}
                                {{ $precioOriginalNum ? "'".number_format($precioOriginalNum, 2)."'" : 'null' }} {{-- 9. Precio Original --}}
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

{{-- ¡ELIMINADO! El <dialog id="productModal"> duplicado ya no está aquí --}}

@endsection

{{-- JAVASCRIPT EXCLUSIVO PARA ESTA PÁGINA (EL CARRUSEL) --}}
@push('scripts')
<script>
    // Espera a que el DOM esté cargado
    document.addEventListener('DOMContentLoaded', () => {
        const carouselTrack = document.querySelector('.carousel-track');
        
        // Asegurarse de que el carrusel existe en esta página
        if (carouselTrack) {
            const slides = Array.from(carouselTrack.children);
            const dotsContainer = document.querySelector('.carousel-dots');
            let slideIndex = 0;
            let intervalId;

            // Salir si no hay slides
            if (slides.length === 0) return;
            
            // Clonar slides para el efecto infinito
            slides.forEach(slide => {
                carouselTrack.appendChild(slide.cloneNode(true));
            });

            // Función para mover a un slide específico
            function goToSlide(index, smooth = true) {
                const slides = Array.from(carouselTrack.children);
                // Prevenir error si los slides aún no están cargados
                if (slides.length === 0 || !slides[0]) return;
                const currentSlideWidth = slides[0].getBoundingClientRect().width;
                if (!smooth) carouselTrack.style.transition = 'none';
                carouselTrack.style.transform = `translateX(-${index * currentSlideWidth}px)`;
                if (!smooth) {
                    carouselTrack.offsetHeight; 
                    carouselTrack.style.transition = 'transform 0.8s ease-in-out';
                }
                
                let activeDotIndex = index % (slides.length / 2); // Ajustado para clones
                dots.forEach(dot => dot.classList.remove('active'));
                if (dots[activeDotIndex]) {
                    dots[activeDotIndex].classList.add('active');
                }
            }

            // Función para el siguiente slide
            function autoSlide() {
                const slides = Array.from(carouselTrack.children);
                const totalSlides = slides.length / 2; // Número de slides originales
                
                slideIndex++;
                goToSlide(slideIndex);

                // Resetear al inicio sin animación si llega al final de los clones
                if (slideIndex >= totalSlides) {
                    setTimeout(() => {
                        slideIndex = 0;
                        goToSlide(slideIndex, false);
                    }, 800); // 800ms = duración de la transición en el CSS
                }
            }
            
            // Crear los puntos de navegación
            const dots = [];
            const originalSlidesCount = slides.length;
            for(let i = 0; i < originalSlidesCount; i++) {
                const dot = document.createElement('span');
                dot.classList.add('dot');
                if (i === 0) dot.classList.add('active');
                dot.addEventListener('click', () => {
                    slideIndex = i;
                    goToSlide(slideIndex);
                    resetAutoSlide(); // Reinicia el timer si se hace clic manual
                });
                dotsContainer.appendChild(dot);
                dots.push(dot);
            }

            // Iniciar el auto-deslizamiento
            let intervalId = setInterval(autoSlide, 5000); // Cambia cada 5 segundos

            // Reiniciar el auto-deslizamiento
            function resetAutoSlide() {
                clearInterval(intervalId);
                intervalId = setInterval(autoSlide, 5000);
            }

            // Ajustar el carrusel si la ventana cambia de tamaño
            window.addEventListener('resize', () => {
                goToSlide(slideIndex, false); // Sin animación al reajustar
            });
        }
    });
</script>
@endpush