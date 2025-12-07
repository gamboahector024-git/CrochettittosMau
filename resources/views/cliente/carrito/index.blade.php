@extends('layouts.cliente')

@section('title', 'Mi Carrito - Crochettittos')

@section('content')
<div class="container cart-page-container">
    
    <div class="cart-header-title">
        <h1><i class="fas fa-shopping-cart"></i> Tu Carrito de Compras</h1>
    </div>
    
    {{-- Mensajes de Alerta --}}
    @if(session('success'))
        <div class="alert alert-success glass-alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-error glass-alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif
    
    @if($carrito->detalles->count())
        {{-- LAYOUT PRINCIPAL: Grid de 2 columnas --}}
        <div class="cart-wrapper">
            
            {{-- COLUMNA IZQUIERDA: Lista de Productos --}}
            <div class="cart-items-column">
                @foreach($carrito->detalles as $detalle)
                    <div class="cart-item glass-card">
                        
                        {{-- Imagen del Producto --}}
                        <div class="item-image">
                            <img src="{{ $detalle->producto->imagen_url ?? 'https://via.placeholder.com/250' }}" alt="{{ $detalle->producto->nombre }}">
                        </div>
                        
                        {{-- Detalles y Precio --}}
                        <div class="item-details">
                            <h3>{{ $detalle->producto->nombre }}</h3>
                            <p class="item-description">{{ Str::limit($detalle->producto->descripcion, 80) }}</p>
                            
                            {{-- Lógica de Precios Original --}}
                            <div class="item-price-block">
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
                                
                                <span class="price-current">${{ number_format($precioUnitario, 2) }}</span>
                                
                                @if($oferta)
                                    <span class="price-original">
                                        ${{ number_format($precioOriginal, 2) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Formulario de Cantidad --}}
                        <div class="item-quantity">
                            <form action="{{ route('carrito.update', $detalle->id_detalle) }}" method="POST" class="quantity-form-styled">
                                @csrf
                                @method('PUT')
                                <label for="cantidad-{{ $detalle->id_detalle }}" class="sr-only">Cant:</label>
                                
                                <div class="qty-input-group">
                                    <input type="number" id="cantidad-{{ $detalle->id_detalle }}" name="cantidad" value="{{ $detalle->cantidad }}" min="1" class="qty-input">
                                    <button type="submit" class="refresh-btn" title="Actualizar cantidad">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Subtotal del Item --}}
                        <div class="item-subtotal">
                            <small>Subtotal</small>
                            <span class="subtotal-value">
                                ${{ number_format($detalle->cantidad * $precioUnitario, 2) }}
                            </span>
                        </div>

                        {{-- Botón Eliminar --}}
                        <div class="item-remove">
                            <form action="{{ route('carrito.destroy', $detalle->id_detalle) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="remove-btn" title="Eliminar producto">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
                
                {{-- Botón Vaciar Carrito (al final de la lista) --}}
                <div class="cart-clear-wrapper">
                    <form action="{{ route('carrito.clear') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-danger-btn" onclick="return confirm('¿Estás seguro de vaciar el carrito?')">
                            <i class="fas fa-trash"></i> Vaciar todo el carrito
                        </button>
                    </form>
                </div>
            </div>

            {{-- COLUMNA DERECHA: Resumen de Pedido (Sticky) --}}
            <div class="cart-summary-column">
                <div class="summary-card glass-card sticky-element">
                    <h3>Resumen del Pedido</h3>
                    
                    {{-- Cálculo del Total (Tu lógica original) --}}
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
                    
                    <div class="summary-details">
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        
                        <div class="summary-row highlight-green">
                            <span>Envío:</span>
                            <span>Gratis <i class="fas fa-gift"></i></span>
                        </div>
                        
                        <div class="summary-divider"></div>
                        
                        <div class="summary-row total">
                            <span>Total:</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                    </div>

                    <div class="summary-actions">
                        <a href="{{ route('carrito.checkout') }}" class="checkout-button full-width">
                            Proceder al Pago <i class="fas fa-arrow-right"></i>
                        </a>

                        <a href="{{ route('tienda') }}" class="continue-shopping-link">
                            Continuar Comprando
                        </a>
                    </div>
                </div>
            </div>
        </div>

    @else
        {{-- Estado Vacío --}}
        <div class="empty-cart-container glass-card">
            <div class="empty-icon-bounce">🛒</div>
            <h2>Tu carrito está vacío</h2>
            <p>¡Nuestros amigurumis te están esperando!</p>
            <a href="{{ route('tienda') }}" class="continue-shopping-btn">
                <i class="fas fa-arrow-left"></i> Ir a la Tienda
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/cliente/carrito.js') }}"></script>
@endpush