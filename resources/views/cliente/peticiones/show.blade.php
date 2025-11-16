@extends('layouts.cliente')

@section('title', 'Detalle de Petición')

@section('content')
<div class="container">
    {{-- 1. Usamos el contenedor "glass" --}}
    <div class="profile-container">

        {{-- 2. Título estilizado --}}
        <h2 class="profile-title">Petición #{{ $peticion->id_peticion }}</h2>

        {{-- 3. Layout de 2 columnas (definido en tu CSS) --}}
        <div class="peticion-detail-layout">

            {{-- COLUMNA IZQUIERDA: DETALLES --}}
            <div class="peticion-details">
                <h3>Detalles de la Petición</h3>

                {{-- 4. Reusamos .profile-info para la lista de datos --}}
                <div class="profile-info">
                    <p><strong>Título:</strong> {{ $peticion->titulo }}</p>
                    
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
                        <h4>Respuesta del Administrador</h4>
                        <p>{{ $peticion->respuesta_admin }}</p>
                    </div>
                @endif

                {{-- 6. El botón "Volver" con el estilo correcto --}}
                <a href="{{ route('cliente.peticiones.index') }}" class="tertiary-button" style="margin-top: 1.5rem;">Volver a la lista</a>
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
@endsection