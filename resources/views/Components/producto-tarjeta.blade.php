@props(['producto'])

<article class="product-card">

    <figure class="card-image-wrapper">
        <img src="{{ $producto->imagen_url ?? 'https://via.placeholder.com/250' }}" alt="{{ $producto->nombre }}">
    </figure>
    
    <div class="card-content">
        <h3>{{ $producto->nombre }}</h3>
        <p class="price">${{ number_format($producto->precio, 2) }}</p>
        
        @php
            $precioBase = (float) ($producto->precio ?? 0);
            $precioFinal = $precioBase;
            $originalPrice = '';
            $badgeText = '';
            $promo = $producto->promocionActiva ?? null;
            if ($promo) {
                if ($promo->tipo === 'porcentaje' && isset($promo->valor)) {
                    $precioFinal = max(0, $precioBase * (1 - ($promo->valor / 100)));
                    $originalPrice = number_format($precioBase, 2);
                    $badgeText = '-'.(int) $promo->valor .'%';
                } elseif ($promo->tipo === 'monto' && isset($promo->valor)) {
                    $precioFinal = max(0, $precioBase - $promo->valor);
                    $originalPrice = number_format($precioBase, 2);
                    $badgeText = '-$'. number_format($promo->valor, 2);
                } elseif (!empty($promo->titulo)) {
                    $badgeText = $promo->titulo;
                }
            }
            $categoriaNombre = optional($producto->categoria)->nombre;
        @endphp

        <button class="buy-button" onclick="openModal(
            '{{ $producto->id_producto }}',
            '{{ addslashes($producto->nombre) }}',
            '{{ number_format($precioFinal, 2) }}',
            '{{ addslashes($producto->descripcion) }}',
            '{{ addslashes($producto->imagen_url ?? 'https://via.placeholder.com/250') }}',
            '{{ addslashes($categoriaNombre ?? '') }}',
            '{{ $originalPrice }}',
            '{{ addslashes($badgeText) }}'
        )">Ver Detalles</button>
    </div>
</article>