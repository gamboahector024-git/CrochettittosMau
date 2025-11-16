@extends('layouts.cliente')

@section('title', 'Mis Peticiones')

@section('content')
<div class="container">
    {{-- 1. Usamos el contenedor "glass" que ya tienes --}}
    <div class="profile-container">

        {{-- 2. Usamos el título estilizado --}}
        <h2 class="profile-title">Mis Peticiones</h2>

        @if($peticiones->isEmpty())
            <p>No tienes peticiones todavía. Puedes enviar una nueva desde el botón "Nueva Petición" en la barra superior.</p>
        @else
            {{-- 3. Reemplazamos la <table> por nuestra lista de tarjetas --}}
            <div class="petitions-list">
                
                @foreach($peticiones as $peticion)
                    <div class="peticion-card">
                        <div class="peticion-header">
                            <span class="peticion-id">Petición #{{ $peticion->id_peticion }}</span>
                            <span class="peticion-date">{{ optional($peticion->created_at)->format('d/m/Y') }}</span>
                            
                            {{-- Usamos tu lógica para el estado, pero en minúsculas --}}
                            <span class="peticion-status status-{{ strtolower(str_replace(' ', '-', $peticion->estado)) }}">
                                {{ ucfirst($peticion->estado) }}
                            </span>
                        </div>
                        <div class="peticion-body">
                            <strong>{{ $peticion->titulo }}</strong>
                            <p>{{ \Illuminate\Support\Str::limit($peticion->descripcion, 200) }}</p>
                            
                            {{-- 4. Usamos la clase de botón correcta --}}
                            <a href="{{ route('cliente.peticiones.show', $peticion->id_peticion) }}" class="tertiary-button">
                                Ver Detalles
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- 5. Le damos estilo a la paginación de Laravel --}}
            <div class="pagination-links">
                {{ $peticiones->links() }}
            </div>
            
        @endif
    </div>
</div>
@endsection