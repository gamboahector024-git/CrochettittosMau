@extends('layouts.cliente')

@section('title', 'Mis Peticiones')

@section('content')
<div class="container">
    <div class="peticion-container">
        <h2 class="peticion-title">Mis Peticiones</h2>

        @if($peticiones->isEmpty())
            <div class="peticion-empty">
                <i class="fas fa-inbox peticion-empty-icon"></i>
                <h3 class="peticion-empty-title">No tienes peticiones todavía</h3>
                <p class="peticion-empty-text">Envía tu primera petición personalizada desde el botón "Nueva Petición"</p>
            </div>
        @else
            <div class="peticion-list">
                @foreach($peticiones as $peticion)
                    <div class="peticion-card">
                        <div class="peticion-header">
                            <div>
                                <span class="peticion-id">
                                    <i class="fas fa-hashtag"></i>
                                    #{{ $peticion->numero_peticion_cliente ?? $peticion->id_peticion }}
                                </span>
                                <span class="peticion-date">
                                    <i class="far fa-calendar"></i>
                                    {{ optional($peticion->created_at)->format('d/m/Y') }}
                                </span>
                            </div>
                            
                            <span class="peticion-status status-{{ strtolower(str_replace(' ', '-', $peticion->estado)) }}">
                                {{ ucfirst($peticion->estado) }}
                            </span>
                        </div>
                        
                        <div class="peticion-body">
                            <h3 class="peticion-body-title">{{ $peticion->titulo }}</h3>
                            
                            <div class="peticion-meta">
                                @if($peticion->categoria)
                                    <span class="peticion-meta-item">
                                        <i class="fas fa-tag peticion-meta-icon"></i>
                                        {{ $peticion->categoria->nombre }}
                                    </span>
                                @endif
                                <span class="peticion-meta-item">
                                    <i class="fas fa-cube peticion-meta-icon cube"></i>
                                    {{ $peticion->cantidad }} {{ $peticion->cantidad == 1 ? 'unidad' : 'unidades' }}
                                </span>
                            </div>
                            
                            <p class="peticion-description">
                                {{ \Illuminate\Support\Str::limit($peticion->descripcion, 200) }}
                            </p>
                            
                            <div class="peticion-actions">
                                <a href="{{ route('cliente.peticiones.show', $peticion->id_peticion) }}" 
                                   class="peticion-button">
                                    <i class="fas fa-eye"></i>
                                    Ver Detalles
                                </a>
                                
                                @if(!empty($peticion->respuesta_admin) && $peticion->respuesta_cliente === 'pendiente')
                                    <span class="peticion-notification">
                                        <i class="fas fa-bell peticion-notification-icon"></i>
                                        Nueva propuesta disponible
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="peticion-pagination">
                {{ $peticiones->links() }}
            </div>
        @endif
    </div>
</div>
@endsection