@extends('layouts.admin')

@section('title', 'Petición #' . $peticion->id_peticion)

@section('content')
    <div class="content-header">
        <h1>Petición #{{ $peticion->id_peticion }}</h1>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 28px;">
        
        <div class="card">
            <h2 class="sub-header">Detalle de la petición</h2>
            <div class="pedido-info-grid" style="grid-template-columns: 1fr;"> <div class="info-item">
                    <strong>Título:</strong> {{ $peticion->titulo }}
                </div>
                <div class="info-item info-item-full">
                    <strong>Descripción:</strong>
                    <p>{{ $peticion->descripcion }}</p>
                </div>
                
                @if($peticion->respuesta_admin)
                    <div class="info-item info-item-full alert alert-secondary" style="margin: 0;">
                        <strong>Respuesta actual:</strong>
                        <p>{{ $peticion->respuesta_admin }}</p>
                    </div>
                @endif

                @if($peticion->imagen_referencia)
                    <div class="info-item">
                        <strong>Imagen de referencia:</strong><br>
                        <img src="{{ asset($peticion->imagen_referencia) }}" alt="Imagen de referencia" class="form-image-preview">
                    </div>
                @endif
            </div>
        </div>

        <div>
            <div class="card" style="margin-bottom: 25px;">
                <div class="pedido-info-grid" style="grid-template-columns: 1fr;">
                    <div class="info-item">
                        <strong>Estado:</strong>
                        <span class="status-badge status-{{ str_replace(' ', '-', $peticion->estado) }}">{{ ucfirst($peticion->estado) }}</span>
                    </div>
                    <div class="info-item">
                        <strong>Fecha:</strong> {{ $peticion->created_at?->format('d/m/Y H:i') ?? 'No especificado' }}
                    </div>
                    <div class="info-item">
                        <strong>Solicitante:</strong>
                        {{ $peticion->usuario->nombre }}
                        <small>({{ $peticion->usuario->email }})</small>
                    </div>
                </div>
            </div>

            <div class="form-container">
                <h2 class="sub-header" style="margin-top: 0;">Actualizar Petición</h2>
                <form action="{{ route('admin.peticiones.responder', $peticion->id_peticion) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="respuesta">Respuesta</label>
                        <textarea id="respuesta" name="respuesta_admin" rows="5" class="form-control" required>{{ old('respuesta_admin', $peticion->respuesta_admin) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="estado">Actualizar estado</label>
                        <select id="estado" name="estado" class="form-control">
                            <option value="en revisión" {{ old('estado', $peticion->estado) === 'en revisión' ? 'selected' : '' }}>En revisión</option>
                            <option value="aceptada" {{ old('estado', $peticion->estado) === 'aceptada' ? 'selected' : '' }}>Aceptada</option>
                            <option value="rechazada" {{ old('estado', $peticion->estado) === 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            {{ $peticion->estado === 'pendiente' ? 'Enviar respuesta' : 'Actualizar respuesta' }}
                        </button>
                    </div>
                </form>

                @if($peticion->estado === 'aceptada')
                    <form action="{{ route('admin.peticiones.completar', $peticion->id_peticion) }}" method="POST" style="margin-top:12px;" onsubmit="return confirm('¿Marcar como completada y generar pedido?');">
                        @csrf
                        <button type="submit" class="btn btn-success" style="width: 100%;">
                            Completar y Generar Pedido
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection