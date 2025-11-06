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
        <div class="products-grid">
            @foreach($carrito->detalles as $detalle)
                <div class="product-card">
                    <div class="card-image-wrapper">
                        <img src="{{ $detalle->producto->imagen_url ?? 'https://via.placeholder.com/250' }}" alt="{{ $detalle->producto->nombre }}">
                    </div>
                    <div class="card-content">
                        <h3>{{ $detalle->producto->nombre }}</h3>
                        <p class="price">
                            @php
                                $precioUnitario = $detalle->producto->promocionActiva
                                    ? $detalle->producto->precio * (1 - $detalle->producto->promocionActiva->descuento/100)
                                    : $detalle->producto->precio;
                            @endphp
                            ${{ number_format($precioUnitario, 2) }}
                        </p>
                        <form action="{{ route('carrito.update', $detalle->id_detalle) }}" method="POST" class="quantity-form" style="margin-top:8px;">
                            @csrf
                            @method('PUT')
                            <label for="cantidad-{{ $detalle->id_detalle }}">Cantidad:</label>
                            <input type="number" id="cantidad-{{ $detalle->id_detalle }}" name="cantidad" value="{{ $detalle->cantidad }}" min="1" class="quantity-input">
                            <button type="submit" class="buy-button">Actualizar</button>
                        </form>
                        <div class="item-subtotal" style="margin-top:6px;">
                            <small>Subtotal: ${{ number_format($detalle->cantidad * $precioUnitario, 2) }}</small>
                        </div>
                        <form action="{{ route('carrito.destroy', $detalle->id_detalle) }}" method="POST" class="remove-form" style="margin-top:10px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="wishlist" title="Eliminar producto">
                                <i class="fas fa-trash"></i> Quitar
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
            
            <div class="cart-summary">
                <div class="summary-content">
                    <h3>Resumen del Carrito</h3>
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>${{ number_format($carrito->detalles->sum(function($item) {
                            return $item->cantidad * ($item->producto->precio * (1 - ($item->producto->promocionActiva ? $item->producto->promocionActiva->descuento/100 : 0)));
                        }), 2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Envío:</span>
                        <span>Gratis</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total:</span>
                        <span>${{ number_format($carrito->detalles->sum(function($item) {
                            return $item->cantidad * ($item->producto->precio * (1 - ($item->producto->promocionActiva ? $item->producto->promocionActiva->descuento/100 : 0)));
                        }), 2) }}</span>
                    </div>
                    <button class="checkout-button" disabled>
                        <i class="fas fa-credit-card"></i> Proceder al pago
                    </button>
                    <a href="{{ route('tienda') }}" class="continue-shopping-btn">
                        <i class="fas fa-arrow-left"></i> Continuar comprando
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="empty-cart">
            <i class="fas fa-shopping-cart empty-cart-icon"></i>
            <p>Tu carrito está vacío</p>
            <a href="{{ route('tienda') }}" class="continue-shopping">
                <i class="fas fa-arrow-left"></i> Continuar comprando
            </a>
        </div>
    @endif
</div>
@endsection
