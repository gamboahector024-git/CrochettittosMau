@extends('layouts.cliente')

@section('title', 'Detalle del Pedido - Crochettittos')

@section('content')
<div class="container">
    {{-- 1. Usamos el contenedor "glass" principal --}}
    <div class="profile-container">

        {{-- 2. Título estilizado y enlace "Volver" --}}
        <div class="order-detail-header">
            <h2 class="profile-title" style="margin-bottom: 0; padding-bottom: 0;">
                Detalle del Pedido #{{ $pedido->id_pedido }}
            </h2>
            <a href="{{ route('cliente.pedidos.index') }}" class="tertiary-button-link">
                &larr; Volver a Mis Pedidos
            </a>
        </div>

        {{-- 3. NUEVO LAYOUT DE 2 COLUMNAS (Principal + Sidebar) --}}
        <div class="order-detail-layout">
            
            {{-- ===== COLUMNA PRINCIPAL (IZQUIERDA) ===== --}}
            <div class="order-main-content">
                
                {{-- 4. Tabla de Productos --}}
                <h3 class="form-subtitle">Productos</h3>
                <div class="order-items-table-container">
                    <table class="order-items-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-right">Precio Unitario</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pedido->detalles as $detalle)
                            <tr>
                                <td>{{ $detalle->producto->nombre }}</td>
                                <td class="text-center">{{ $detalle->cantidad }}</td>
                                <td class="text-right">${{ number_format($detalle->precio_unitario, 2) }}</td>
                                <td class="text-right">${{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- 5. Dirección de Envío --}}
                <h3 class="form-subtitle">Dirección de Envío</h3>
                <div class="profile-info" style="background: var(--color-powdered-lilac-grey); padding: 1.5rem; border-radius: 12px;">
                    <p><strong>Calle:</strong> {{ $pedido->calle }}</p>
                    <p><strong>Colonia:</strong> {{ $pedido->colonia }}</p>
                    <p><strong>Ciudad:</strong> {{ $pedido->municipio_ciudad }}</p>
                    <p><strong>CP:</strong> {{ $pedido->codigo_postal }}</p>
                    <p><strong>Estado:</strong> {{ $pedido->estado_direccion }}</p>
                </div>

            </div>
            
            {{-- ===== SIDEBAR (DERECHA) - ESTA COLUMNA SERÁ "PEGAJOSA" ===== --}}
            <div class="order-sidebar">
                
                {{-- 6. Resumen del Pedido (Fecha, Estado, Total) --}}
                <h3 class="form-subtitle" style="margin-top: 0;">Resumen del Pedido</h3>
                <div class="order-summary-box">
                    <div class="summary-box-item">
                        <span>Fecha:</span>
                        <strong>{{ $pedido->fecha_pedido->format('d/m/Y') }}</strong>
                    </div>
                    <div class="summary-box-item">
                        <span>Estado:</span>
                        <span class="order-status status-{{ strtolower($pedido->estado) }}">
                            {{ ucfirst($pedido->estado) }}
                        </span>
                    </div>
                    <div class="summary-box-total">
                        <span>Total:</span>
                        <strong>${{ number_format($pedido->total, 2) }}</strong>
                    </div>
                </div>

                {{-- 7. Información de Pago --}}
                <h3 class="form-subtitle">Información de Pago y Envío</h3>
                <div class="profile-info">
                    <p><strong>Método de pago:</strong> {{ $pedido->metodo_pago ?? 'No especificado' }}</p>
                    @if($pedido->empresa_envio)
                        <p><strong>Empresa de envío:</strong> {{ $pedido->empresa_envio }}</p>
                        <p><strong>Código de rastreo:</strong> {{ $pedido->codigo_rastreo ?? 'No disponible' }}</p>
                    @endif
                </div>

            </div>
            
        </div> {{-- Fin de .order-detail-layout --}}
    </div> {{-- Fin de .profile-container --}}
</div>
@endsection