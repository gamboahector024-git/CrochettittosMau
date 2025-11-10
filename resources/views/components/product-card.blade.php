@php
    $oferta = $producto->promocionActiva;
    $precioOriginalNum = $producto->precio;
    if ($oferta) {
        if ($oferta->tipo === 'porcentaje') {
            $precioFinal = max($precioOriginalNum * (1 - ($oferta->valor/100)), 0);
        } else { // fijo
            $precioFinal = max($precioOriginalNum - $oferta->valor, 0);
        }
    } else {
        $precioFinal = $precioOriginalNum;
    }
    $badge = null;
    if ($oferta) {
        $badge = $oferta->tipo === 'porcentaje'
            ? ($oferta->valor . '% OFF')
            : ('$' . number_format($oferta->valor, 2) . ' OFF');
    }
@endphp

<div class="product-card">
    <div class="card-image-wrapper">
        <img src="{{ $producto->imagen_url ?? 'https://via.placeholder.com/250' }}" alt="{{ $producto->nombre }}">
        @if($oferta)
            <div class="promotion-info">
                <span class="discount-badge-card">{{ $badge }}</span>
                <div class="promotion-details">
                    <strong>{{ $oferta->titulo }}</strong>
                    <small>Vigencia: {{ optional($oferta->fecha_inicio)->format('d/m/Y') }} - {{ optional($oferta->fecha_fin)->format('d/m/Y') }}</small>
                    @if($oferta->descripcion)
                        <p class="promo-description">{{ $oferta->descripcion }}</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
    <div class="card-content">
        <h3>{{ $producto->nombre }}</h3>
        
        @if($oferta)
            <div class="promotion-title">
                <strong>{{ $oferta->titulo }}</strong>
                @if($oferta->descripcion)
                    <p class="promo-description">{{ $oferta->descripcion }}</p>
                @endif
            </div>
        @endif
        
        <p class="price">
            @if($oferta)
                <span class="final-price">${{ number_format($precioFinal, 2) }}</span>
                <span class="original-price-card">${{ number_format($precioOriginalNum, 2) }}</span>
            @else
                ${{ number_format($precioFinal, 2) }}
            @endif
        </p>
        
        @if($oferta)
            <p class="promo-validity">
                <small>Válida hasta: {{ optional($oferta->fecha_fin)->format('d/m/Y') }}</small>
            </p>
        @endif
        
        {{-- 
          ===== ONCLICK CORREGIDO =====
          Llama a la función 'openModal' de main.js con todos los 9 argumentos.
          Ahora SÍ pasa el id_producto real.
        --}}
        <button class="buy-button" onclick="openModal(
            {{ $producto->id_producto }}, {{-- 1. productId --}}
            {{ json_encode($producto->nombre) }}, {{-- 2. name --}}
            '{{ number_format($precioFinal, 2) }}', {{-- 3. price --}}
            {{ json_encode($producto->descripcion ?? 'Sin descripción.') }}, {{-- 4. description --}}
            '{{ $producto->imagen_url ?? 'https://via.placeholder.com/250' }}', {{-- 5. imageUrl --}}
            '{{ $producto->categoria->nombre ?? 'Sin categoría' }}', {{-- 6. categoryName --}}
            {{ $oferta ? "'".number_format($precioOriginalNum, 2)."'" : 'null' }}, {{-- 7. originalPrice --}}
            {{ $badge ? json_encode($badge) : 'null' }} {{-- 8. discountBadgeText --}}
        )">
            Ver Detalles
        </button>
    </div>
</div>
