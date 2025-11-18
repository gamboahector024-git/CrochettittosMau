@extends('layouts.cliente')

@section('title', 'Detalle de Petición')

@section('content')
<div class="container">
    {{-- 1. Usamos el contenedor "glass" --}}
    <div class="profile-container">

        {{-- 2. Título estilizado --}}
        <h2 class="profile-title">Detalles de tu Petición</h2>

        {{-- 3. Layout de 2 columnas (definido en tu CSS) --}}
        <div class="peticion-detail-layout">

            {{-- COLUMNA IZQUIERDA: DETALLES --}}
            <div class="peticion-details">
                <h3>Detalles de la Petición</h3>

                {{-- 4. Reusamos .profile-info para la lista de datos --}}
                <div class="profile-info">
                    <p><strong>Título:</strong> {{ $peticion->titulo }}</p>
                    
                    @if($peticion->categoria)
                        <p><strong>Categoría:</strong> {{ $peticion->categoria->nombre }}</p>
                    @endif
                    <p><strong>Cantidad:</strong> {{ $peticion->cantidad }} {{ $peticion->cantidad == 1 ? 'unidad' : 'unidades' }}</p>
                    
                    <p><strong>Estado:</strong> 
                        <span class="peticion-status status-{{ strtolower(str_replace(' ', '-', $peticion->estado)) }}">
                            {{ ucfirst($peticion->estado) }}
                        </span>
                    </p>
                    
                    <p><strong>Fecha:</strong> {{ optional($peticion->created_at)->format('d/m/Y \a \l\a\s H:i') }}</p>
                    
                    <p><strong>Descripción:</strong></p>
                    <div class="peticion-description">
                        {{ $peticion->descripcion }}
                    </div>
                </div>

                {{-- 5. NUEVO BLOQUE: Respuesta del Administrador (se ve si existe) --}}
                @if(!empty($peticion->respuesta_admin))
                    <div class="admin-response">
                        <h4>Propuesta del Administrador</h4>
                        <p>{{ $peticion->respuesta_admin }}</p>
                        
                        @if($peticion->precio_propuesto)
                            <div class="precio-propuesto">
                                <p class="label">Precio propuesto:</p>
                                <p class="precio">${{ number_format($peticion->precio_propuesto, 2) }} MXN</p>
                                <p class="fecha">Enviado: {{ $peticion->fecha_respuesta_admin?->format('d/m/Y H:i') }}</p>
                            </div>

                            {{-- Botones de Aceptar/Rechazar solo si está pendiente --}}
                            @if($peticion->respuesta_cliente === 'pendiente')
                                <div class="botones-respuesta">
                                    {{-- Botón Aceptar y Pagar con PayPal --}}
                                    <div class="paypal-container">
                                        <div id="paypal-button-container"></div>
                                    </div>
                                    
                                    {{-- Botón Rechazar --}}
                                    <form action="{{ route('cliente.peticiones.rechazar', $peticion->id_peticion) }}" method="POST" onsubmit="return confirm('¿Estás seguro de rechazar esta propuesta?');">
                                        @csrf
                                        <button type="submit" class="tertiary-button btn-rechazar">
                                            Rechazar
                                        </button>
                                    </form>
                                </div>
                            @elseif($peticion->respuesta_cliente === 'aceptada')
                                <div class="estado-aceptada">
                                    Has aceptado esta propuesta y realizado el pago
                                </div>
                            @elseif($peticion->respuesta_cliente === 'rechazada')
                                <div class="estado-rechazada">
                                    Has rechazado esta propuesta
                                </div>
                            @endif
                        @endif
                    </div>
                @endif

                {{-- 6. El botón "Volver" con el estilo correcto --}}
                <a href="{{ route('cliente.peticiones.index') }}" class="tertiary-button">Volver a la lista</a>
            </div>

            {{-- COLUMNA DERECHA: IMAGEN --}}
            <div class="peticion-image-container">
                <h3>Imagen de Referencia</h3>
                @if(!empty($peticion->imagen_referencia))
                    {{-- Quitamos los estilos en línea feos --}}
                    <img src="{{ asset($peticion->imagen_referencia) }}" alt="Imagen de referencia para {{ $peticion->titulo }}">
                @else
                    <p>No se adjuntó ninguna imagen de referencia.</p>
                @endif
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