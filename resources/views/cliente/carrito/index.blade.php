@extends('layouts.cliente')

@section('content')
<div class="container cart-container">
    <h1>Tu Carrito de Compras</h1>
    
    {{-- Mensajes de éxito/error --}}
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif
    
    @if($carrito->detalles->count())
        <div class="cart-items">
            @foreach($carrito->detalles as $detalle)
                <div class="cart-item">
                    <div class="item-image">
                        <img src="{{ $detalle->producto->imagen_url ?? 'https://via.placeholder.com/250' }}" alt="{{ $detalle->producto->nombre }}">
                    </div>
                    
                    <div class="item-details">
                        <h3>{{ $detalle->producto->nombre }}</h3>
                        <p class="item-description">{{ Str::limit($detalle->producto->descripcion, 100) }}</p>
                        <p class="item-price">
                            @php
                                $oferta = $detalle->producto->promocionActiva;
                                $precioOriginal = $detalle->producto->precio;
                                if ($oferta) {
                                    if ($oferta->tipo === 'porcentaje') {
                                        $precioUnitario = max($precioOriginal * (1 - ($oferta->valor/100)), 0);
                                    } else { // fijo
                                        $precioUnitario = max($precioOriginal - $oferta->valor, 0);
                                    }
                                } else {
                                    $precioUnitario = $precioOriginal;
                                }
                            @endphp
                            ${{ number_format($precioUnitario, 2) }}
                            @if($oferta)
                                <small style="text-decoration: line-through; color: #7A6A74; margin-left: 8px;">
                                    ${{ number_format($precioOriginal, 2) }}
                                </small>
                            @endif
                        </p>
                    </div>

                    <div class="item-quantity">
                        <form action="{{ route('carrito.update', $detalle->id_detalle) }}" method="POST" class="quantity-form">
                            @csrf
                            @method('PUT')
                            <label for="cantidad-{{ $detalle->id_detalle }}">Cantidad:</label>
                            <input type="number" id="cantidad-{{ $detalle->id_detalle }}" name="cantidad" value="{{ $detalle->cantidad }}" min="1" class="quantity-input">
                            <button type="submit" class="update-btn">Actualizar</button>
                        </form>
                    </div>

                    <div class="item-subtotal">
                        <span class="subtotal">
                            ${{ number_format($detalle->cantidad * $precioUnitario, 2) }}
                        </span>
                    </div>

                    <div class="remove-form">
                        <form action="{{ route('carrito.destroy', $detalle->id_detalle) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="remove-item" title="Eliminar producto">
                                ×
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="cart-summary">
            <div class="summary-content">
                <h3>Resumen del Pedido</h3>
                
                @php
                    $subtotal = $carrito->detalles->sum(function($item) {
                        $oferta = $item->producto->promocionActiva;
                        $base = $item->producto->precio;
                        if ($oferta) {
                            $unit = $oferta->tipo === 'porcentaje' ? max($base * (1 - ($oferta->valor/100)), 0) : max($base - $oferta->valor, 0);
                        } else {
                            $unit = $base;
                        }
                        return $item->cantidad * $unit;
                    });
                @endphp
                
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>${{ number_format($subtotal, 2) }}</span>
                </div>
                
                <div class="summary-row">
                    <span>Envío:</span>
                    <span>Gratis</span>
                </div>
                
                <div class="summary-row total">
                    <span>Total:</span>
                    <span>${{ number_format($subtotal, 2) }}</span>
                </div>

                <a href="{{ route('carrito.checkout') }}" class="checkout-button">
                    <i class="fas fa-credit-card"></i> Proceder al Pago
                </a>

                <a href="{{ route('tienda') }}" class="continue-shopping-btn">
                    <i class="fas fa-arrow-left"></i> Continuar Comprando
                </a>

                <form action="{{ route('carrito.clear') }}" method="POST" style="margin-top: 1rem;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="tertiary-button" 
                            onclick="return confirm('¿Estás seguro de vaciar el carrito?')">
                        <i class="fas fa-trash"></i> Vaciar Carrito
                    </button>
                </form>
            </div>
        </div>
    @else
        <div class="empty-cart">
            <div class="empty-cart-icon">🛒</div>
            <p>Tu carrito está vacío</p>
            <a href="{{ route('tienda') }}" class="continue-shopping">
                <i class="fas fa-arrow-left"></i> Continuar Comprando
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/cliente/carrito.js') }}"></script>
@endpush