@extends('layouts.admin')

@section('title', 'Petición #' . $peticion->id_peticion)
@section('header', 'Petición #' . $peticion->id_peticion)

@section('content')
    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section>
        <p><strong>Estado:</strong> {{ ucfirst($peticion->estado) }}</p>
        <p><strong>Fecha:</strong> {{ $peticion->created_at?->format('d/m/Y H:i') ?? 'No especificado' }}</p>
    </section>

    <section>
        <h2>Datos del solicitante</h2>
        <p>{{ $peticion->usuario->nombre }} ({{ $peticion->usuario->email }})</p>
    </section>

    <section>
        <h2>Detalle de la petición</h2>
        <p><strong>Título:</strong> {{ $peticion->titulo }}</p>
        <p><strong>Descripción:</strong></p>
        <p>{{ $peticion->descripcion }}</p>

        @if($peticion->imagen_referencia)
            <figure>
                <img src="{{ asset('storage/'.$peticion->imagen_referencia) }}" alt="Imagen de referencia" style="max-width: 300px;">
            </figure>
        @endif

        @if($peticion->respuesta_admin)
            <div>
                <h3>Respuesta actual</h3>
                <p>{{ $peticion->respuesta_admin }}</p>
            </div>
        @endif
    </section>

    <section>
        <h2>Actualizar petición</h2>
        <form action="{{ route('admin.peticiones.responder', $peticion->id_peticion) }}" method="POST">
            @csrf
            <div>
                <label for="respuesta">Respuesta</label>
                <textarea id="respuesta" name="respuesta_admin" rows="5" required>{{ old('respuesta_admin', $peticion->respuesta_admin) }}</textarea>
            </div>

            <div>
                <label for="estado">Estado después de responder</label>
                <select id="estado" name="estado">
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
            <form action="{{ route('admin.peticiones.completar', $peticion->id_peticion) }}" method="POST" style="margin-top:12px;">
                @csrf
                <button type="submit" class="btn btn-success" onclick="return confirm('¿Marcar como completada y generar pedido?');">
                    Completar y generar pedido
                </button>
            </form>
        @endif
    </section>
@endsection