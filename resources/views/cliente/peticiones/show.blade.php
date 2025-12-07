@extends('layouts.cliente')

@section('title', 'Detalle de Petición')

@section('content')
<div class="container">
    <div class="peticion-container">
        <h2 class="peticion-title">Detalles de tu Petición</h2>

        <div class="peticion-detail-layout">
            
            {{-- COLUMNA IZQUIERDA: DETALLES --}}
            <div class="peticion-detail-panel">
                <h3 class="peticion-detail-title">Detalles de la Petición</h3>

                <div class="peticion-info">
                    <div class="peticion-info-item">
                        <i class="fas fa-heading peticion-info-icon"></i>
                        <div class="peticion-info-content">
                            <span class="peticion-info-label">Título</span>
                            <p class="peticion-info-value">{{ $peticion->titulo }}</p>
                        </div>
                    </div>
                    
                    @if($peticion->categoria)
                    <div class="peticion-info-item">
                        <i class="fas fa-tag peticion-info-icon"></i>
                        <div class="peticion-info-content">
                            <span class="peticion-info-label">Categoría</span>
                            <p class="peticion-info-value">{{ $peticion->categoria->nombre }}</p>
                        </div>
                    </div>
                    @endif
                    
                    <div class="peticion-info-item">
                        <i class="fas fa-cube peticion-info-icon"></i>
                        <div class="peticion-info-content">
                            <span class="peticion-info-label">Cantidad</span>
                            <p class="peticion-info-value">{{ $peticion->cantidad }} {{ $peticion->cantidad == 1 ? 'unidad' : 'unidades' }}</p>
                        </div>
                    </div>
                    
                    <div class="peticion-info-item">
                        <i class="fas fa-info-circle peticion-info-icon"></i>
                        <div class="peticion-info-content">
                            <span class="peticion-info-label">Estado</span>
                            <span class="peticion-status status-{{ strtolower(str_replace(' ', '-', $peticion->estado)) }}">
                                {{ ucfirst($peticion->estado) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="peticion-info-item">
                        <i class="far fa-calendar peticion-info-icon"></i>
                        <div class="peticion-info-content">
                            <span class="peticion-info-label">Fecha</span>
                            <p class="peticion-info-value">{{ optional($peticion->created_at)->format('d/m/Y \a \l\a\s H:i') }}</p>
                        </div>
                    </div>
                </div>

                <div class="peticion-description-panel">
                    <h4 class="peticion-description-title">
                        <i class="fas fa-align-left"></i>
                        Descripción
                    </h4>
                    <div class="peticion-description-content">
                        {{ $peticion->descripcion }}
                    </div>
                </div>

                {{-- RESPUESTA DEL ADMINISTRADOR --}}
                @if(!empty($peticion->respuesta_admin))
                    <div class="peticion-admin-response">
                        <h4 class="peticion-admin-title">
                            <i class="fas fa-comment-dots"></i>
                            Propuesta del Administrador
                        </h4>
                        <p class="peticion-admin-text">
                            {{ $peticion->respuesta_admin }}
                        </p>
                        
                        @if($peticion->precio_propuesto)
                            <div class="peticion-price-proposed">
                                <p class="peticion-price-label">Precio propuesto:</p>
                                <p class="peticion-price-amount">
                                    <span class="peticion-price-currency">$</span>
                                    {{ number_format($peticion->precio_propuesto, 2) }}
                                    <span class="peticion-price-currency">MXN</span>
                                </p>
                                <p class="peticion-price-date">
                                    <i class="far fa-clock"></i>
                                    Enviado: {{ $peticion->fecha_respuesta_admin?->format('d/m/Y H:i') }}
                                </p>
                            </div>

                            @if($peticion->respuesta_cliente === 'pendiente')
                                <div class="peticion-response-actions">
                                    <h5 class="peticion-response-title">¿Qué deseas hacer?</h5>
                                    
                                    <div class="peticion-paypal-container">
                                        <div id="paypal-button-container"></div>
                                    </div>
                                    
                                    <form action="{{ route('cliente.peticiones.rechazar', $peticion->id_peticion) }}" method="POST" 
                                          onsubmit="return confirm('¿Estás seguro de rechazar esta propuesta?');"
                                          style="text-align: center;">
                                        @csrf
                                        <button type="submit" class="peticion-button-secondary">
                                            <i class="fas fa-times"></i>
                                            Rechazar Propuesta
                                        </button>
                                    </form>
                                </div>
                            @elseif($peticion->respuesta_cliente === 'aceptada')
                                <div class="peticion-response-state peticion-response-accepted">
                                    <i class="fas fa-check-circle"></i>
                                    Has aceptado esta propuesta y realizado el pago
                                </div>
                            @elseif($peticion->respuesta_cliente === 'rechazada')
                                <div class="peticion-response-state peticion-response-rejected">
                                    <i class="fas fa-times-circle"></i>
                                    Has rechazado esta propuesta
                                </div>
                            @endif
                        @endif
                    </div>
                @endif

                {{-- BOTÓN VOLVER --}}
                <div class="peticion-back-button">
                    <a href="{{ route('cliente.peticiones.index') }}" 
                       class="peticion-button">
                        <i class="fas fa-arrow-left"></i>
                        Volver a la lista
                    </a>
                </div>
            </div>

            {{-- COLUMNA DERECHA: IMAGEN --}}
            <div class="peticion-image-panel">
                <h3 class="peticion-image-title">
                    <i class="fas fa-image"></i>
                    Imagen de Referencia
                </h3>
                
                @if(!empty($peticion->imagen_referencia))
                    <div class="peticion-image-container">
                        <img src="{{ asset($peticion->imagen_referencia) }}" 
                             alt="Imagen de referencia para {{ $peticion->titulo }}"
                             class="peticion-image">
                    </div>
                    <p class="peticion-image-caption">
                        Haz clic en la imagen para verla en tamaño completo
                    </p>
                @else
                    <div class="peticion-image-empty">
                        <i class="far fa-image peticion-image-empty-icon"></i>
                        <p class="peticion-image-empty-text">No se adjuntó ninguna imagen de referencia.</p>
                    </div>
                @endif
                
                {{-- INFORMACIÓN ADICIONAL --}}
                <div class="peticion-additional-info">
                    <h4 class="peticion-additional-title">
                        <i class="fas fa-info-circle"></i>
                        Información
                    </h4>
                    <div class="peticion-additional-list">
                        <div class="peticion-additional-item">
                            <i class="fas fa-hashtag peticion-additional-icon"></i>
                            <span class="peticion-additional-text">
                                <strong>ID:</strong> {{ $peticion->id_peticion }}
                            </span>
                        </div>
                        <div class="peticion-additional-item">
                            <i class="fas fa-exchange-alt peticion-additional-icon"></i>
                            <span class="peticion-additional-text">
                                <strong>Última actualización:</strong> 
                                {{ optional($peticion->updated_at)->format('d/m/Y H:i') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(!empty($peticion->respuesta_admin) && $peticion->precio_propuesto && $peticion->respuesta_cliente === 'pendiente')
    @push('scripts')
    <script src="https://www.paypal.com/sdk/js?client-id={{ config('paypal.client_id') }}&currency=MXN&disable-funding=card"></script>
    <script src="{{ asset('js/cliente/peticion-pago.js') }}"></script>
    <script src="{{ asset('js/cliente/peticion-paypal-init.js') }}"></script>
    <script>
        window.initPeticionPayPalConfig({
            peticionId: {{ $peticion->id_peticion }},
            createUrl: '{{ route("paypal.peticion.create", $peticion->id_peticion) }}',
            returnUrl: '{{ route("paypal.peticion.return") }}',
            cancelUrl: '{{ route("paypal.peticion.cancel") }}',
            csrfToken: '{{ csrf_token() }}'
        });
    </script>
    @endpush
@endif
@endsection