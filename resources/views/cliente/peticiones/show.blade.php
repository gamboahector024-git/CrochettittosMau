@extends('layouts.cliente')

@section('title', 'Detalle de Petición')

@section('content')
<div class="container" style="padding:20px;">
    <h1>Petición #{{ $peticion->id_peticion }}</h1>

    <div class="info-item"><strong>Título:</strong> {{ $peticion->titulo }}</div>
    <div class="info-item"><strong>Estado:</strong> <span class="status-badge status-{{ str_replace(' ', '-', $peticion->estado) }}">{{ ucfirst($peticion->estado) }}</span></div>
    <div class="info-item" style="margin-top:10px;"><strong>Descripción:</strong>
        <p>{{ $peticion->descripcion }}</p>
    </div>

    @if(!empty($peticion->imagen_referencia))
        <div style="margin-top:10px;">
            <strong>Imagen de referencia:</strong>
            <div>
                <img src="{{ asset($peticion->imagen_referencia) }}" alt="Imagen referencia" style="max-width:100%;height:auto;border:1px solid #ddd;padding:6px;">
            </div>
        </div>
    @endif

    @if(!empty($peticion->respuesta_admin))
        <div class="info-item" style="margin-top:12px;"><strong>Respuesta del administrador:</strong>
            <p>{{ $peticion->respuesta_admin }}</p>
        </div>
    @endif

    <div style="margin-top:14px;">
        <a href="{{ route('cliente.peticiones.index') }}" class="nav-button nav-button-pastel-secondary">Volver</a>
    </div>
</div>
@endsection
