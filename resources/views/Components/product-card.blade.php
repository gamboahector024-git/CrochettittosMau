@props(['producto'])

<article class="product-card">

    <figure class="card-image-wrapper">
        <img src="{{ $producto['imagen_url'] ?? 'https://via.placeholder.com/250' }}" alt="{{ $producto['nombre'] }}">
    </figure>
    
    <div class="card-content">
        <h3>{{ $producto['nombre'] }}</h3>
        <p class="price">${{ number_format($producto['precio'], 2) }}</p>
        
        <button class="buy-button" onclick="openModal(
            '{{ addslashes($producto['nombre']) }}',
            '{{ number_format($producto['precio'], 2) }}',
            '{{ addslashes($producto['descripcion']) }}',
            '{{ addslashes($producto['imagen_url'] ?? 'https://via.placeholder.com/250') }}'
        )">Ver Detalles</button>
    </div>
</article>