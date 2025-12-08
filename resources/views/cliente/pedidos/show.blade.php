@extends('layouts.cliente')

@section('title', 'Detalle del Pedido #' . ($pedido->numero_pedido_cliente ?? $pedido->id_pedido) . ' - Crochettittos')

@section('content')
<div class="container-pedidos">
    
    {{-- Cabecera con navegación --}}
    <div class="order-header-action">
        <a href="{{ route('cliente.pedidos.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Volver a Mis Pedidos
        </a>
        <h1 class="page-title">
            Pedido <span class="highlight-number">#{{ $pedido->numero_pedido_cliente ?? $pedido->id_pedido }}</span>
        </h1>
    </div>

    <div class="order-dashboard">
        
        {{-- COLUMNA IZQUIERDA: Contenido Principal --}}
        <div class="order-main-content">
            
            {{-- A. SECCIÓN DE PETICIÓN PERSONALIZADA (Si existe) --}}
            @if($pedido->peticion)
            <div class="info-card special-request-card">
                <div class="card-header">
                    <i class="fas fa-magic"></i> Petición Personalizada
                </div>
                <div class="peticion-grid">
                    <div class="peticion-details">
                        <h3 class="peticion-title">{{ $pedido->peticion->titulo }}</h3>
                        
                        @if($pedido->peticion->categoria)
                            <span class="category-badge">{{ $pedido->peticion->categoria->nombre }}</span>
                        @endif
                        
                        <p class="peticion-desc">{{ $pedido->peticion->descripcion }}</p>
                        
                        @if($pedido->peticion->respuesta_admin)
                            <div class="admin-reply">
                                <strong><i class="fas fa-comment-dots"></i> Nota de Crochettittos:</strong>
                                <p>{{ $pedido->peticion->respuesta_admin }}</p>
                            </div>
                        @endif
                    </div>

                    @if($pedido->peticion->imagen_referencia)
                    <div class="peticion-image-wrapper">
                        <span class="img-label">Referencia:</span>
                        <img src="{{ asset($pedido->peticion->imagen_referencia) }}" alt="Referencia" class="reference-img">
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- B. LISTA DE PRODUCTOS --}}
            @if($pedido->detalles->isNotEmpty())
            <div class="info-card">
                <div class="card-header">
                    <i class="fas fa-shopping-bag"></i> Productos
                </div>
                <div class="table-responsive">
                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th class="text-center">Cant.</th>
                                <th class="text-right">Precio Unit.</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pedido->detalles as $detalle)
                            <tr>
                                <td>
                                    <div class="product-cell">
                                        <div class="product-icon"><i class="fas fa-gift"></i></div>
                                        <span class="product-name">{{ $detalle->producto->nombre }}</span>
                                    </div>
                                </td>
                                <td class="text-center">{{ $detalle->cantidad }}</td>
                                <td class="text-right">${{ number_format($detalle->precio_unitario, 2) }}</td>
                                <td class="text-right font-bold">${{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- C. DIRECCIÓN DE ENVÍO --}}
            <div class="info-card">
                 <div class="card-header">
                    <i class="fas fa-map-marker-alt"></i> Dirección de Entrega
                </div>
                <div class="address-grid">
                    <div class="address-item">
                        <span class="label">Calle y número</span>
                        <span class="value">{{ $pedido->calle }}</span>
                    </div>
                    <div class="address-item">
                        <span class="label">Colonia</span>
                        <span class="value">{{ $pedido->colonia }}</span>
                    </div>
                    <div class="address-item">
                        <span class="label">Ciudad / Municipio</span>
                        <span class="value">{{ $pedido->municipio_ciudad }}</span>
                    </div>
                    <div class="address-item">
                        <span class="label">Estado</span>
                        <span class="value">{{ $pedido->estado_direccion }}</span>
                    </div>
                    <div class="address-item">
                        <span class="label">Código Postal</span>
                        <span class="value">{{ $pedido->codigo_postal }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA: Sidebar Resumen --}}
        <div class="order-sidebar">
            <div class="info-card summary-card">
                <div class="card-header">
                    <i class="fas fa-receipt"></i> Resumen
                </div>
                
                <div class="summary-row">
                    <span>Fecha:</span>
                    <strong>{{ $pedido->fecha_pedido->format('d/m/Y') }}</strong>
                </div>
                
                <div class="summary-row">
                    <span>Método de pago:</span>
                    <span>{{ $pedido->metodo_pago ?? 'Tarjeta' }}</span>
                </div>

                @if($pedido->empresa_envio)
                <div class="summary-row">
                    <span>Paquetería:</span>
                    <span>{{ $pedido->empresa_envio }}</span>
                </div>
                @endif

                @if($pedido->codigo_rastreo)
                <div class="tracking-box">
                    <span class="tracking-label">Guía de rastreo:</span>
                    <div class="tracking-code">
                        {{ $pedido->codigo_rastreo }}
                        <i class="fas fa-copy copy-icon" title="Copiar"></i>
                    </div>
                </div>
                @endif

                <div class="summary-divider"></div>

                <div class="summary-row status-row">
                    <span>Estado:</span>
                    <span class="status-badge status-{{ strtolower($pedido->estado) }}">
                        {{ ucfirst($pedido->estado) }}
                    </span>
                </div>

                <div class="summary-total">
                    <span>Total</span>
                    <span class="total-amount">${{ number_format($pedido->total, 2) }}</span>
                </div>
            </div>

            <div class="help-card">
                <i class="fas fa-headset"></i>
                <p>¿Necesitas ayuda con este pedido?</p>
                <a href="{{ route('faq') }}" class="help-link">Contáctanos</a>
            </div>
        </div>

    </div>
</div>
@endsection