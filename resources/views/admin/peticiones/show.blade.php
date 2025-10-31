{{-- resources/views/admin/peticiones/show.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <h2>Petición #{{ $peticion->id_peticion }}</h2>
            <span class="badge bg-{{
                $peticion->estado === 'pendiente' ? 'warning' : 
                ($peticion->estado === 'en revisión' ? 'info' : 
                ($peticion->estado === 'aceptada' ? 'primary' : 
                ($peticion->estado === 'rechazada' ? 'secondary' : 'success')))
            }}">
                {{ ucfirst($peticion->estado) }}
            </span>
        </div>
        
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Usuario:</h5>
                    <p>{{ $peticion->usuario->nombre }} ({{ $peticion->usuario->email }})</p>
                </div>
                <div class="col-md-6">
                    <h5>Fecha:</h5>
                    <p>{{ $peticion->created_at?->format('d/m/Y H:i') ?? 'No especificado' }}</p>
                </div>
            </div>

            <div class="mb-4">
                <h5>Título:</h5>
                <p class="h6">{{ $peticion->titulo }}</p>
            </div>

            <div class="mb-4">
                <h5>Descripción:</h5>
                <p>{{ $peticion->descripcion }}</p>
            </div>

            @if($peticion->imagen_referencia)
            <div class="mb-4">
                <h5>Imagen de referencia:</h5>
                <img src="{{ asset('storage/'.$peticion->imagen_referencia) }}" 
                     class="img-thumbnail" style="max-width: 300px;">
            </div>
            @endif

            @if($peticion->respuesta_admin)
            <div class="mb-4">
                <h5>Respuesta actual:</h5>
                <div class="alert alert-info">
                    {{ $peticion->respuesta_admin }}
                </div>
            </div>
            @endif

            <hr>

            <form action="{{ route('admin.peticiones.responder', $peticion->id_peticion) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="respuesta" class="form-label">Respuesta:</label>
                    <textarea class="form-control" id="respuesta" name="respuesta_admin" 
                              rows="5" required>{{ old('respuesta_admin', $peticion->respuesta_admin) }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado después de responder:</label>
                    <select id="estado" name="estado" class="form-select">
                        <option value="en revisión" {{ old('estado', $peticion->estado) === 'en revisión' ? 'selected' : '' }}>En revisión</option>
                        <option value="aceptada" {{ old('estado', $peticion->estado) === 'aceptada' ? 'selected' : '' }}>Aceptada</option>
                        <option value="rechazada" {{ old('estado', $peticion->estado) === 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    {{ $peticion->estado === 'pendiente' ? 'Enviar respuesta' : 'Actualizar respuesta' }}
                </button>
            </form>

            @if($peticion->estado === 'aceptada')
            <form action="{{ route('admin.peticiones.completar', $peticion->id_peticion) }}" method="POST" style="display:inline-block; margin-left:8px;">
                @csrf
                <button type="submit" class="btn btn-success" onclick="return confirm('¿Marcar como completada y generar pedido?');">
                    Completar y generar pedido
                </button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection