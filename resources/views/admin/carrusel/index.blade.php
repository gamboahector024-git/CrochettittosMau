@extends('layouts.admin')

@section('title', 'Gestionar Carrusel')

@section('content')
    <div class="content-header" style="margin-bottom: 2rem;">
        <h1 style="font-family: var(--font-heading); color: var(--accent); font-size: 2.5rem;">
            Carrusel de Inicio
        </h1>
        <p style="color: var(--text-muted);">Gestiona los banners promocionales de tu tienda.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Barra de Acción --}}
    <div class="card search-card" style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
        <span style="color: var(--text-muted); font-weight: 500;">
            Mostrando <strong>{{ $carruseles->count() }}</strong> imágenes
        </span>
        <a href="{{ route('admin.carrusel.create') }}" class="btn btn-primary shadow-hover">
            <span style="font-size: 1.2em; vertical-align: middle;">+</span> Agregar Imagen
        </a>
    </div>

    {{-- Galería de Imágenes --}}
    @if($carruseles->isNotEmpty())
        <div class="gallery-grid">
            @foreach($carruseles as $carrusel)
                <div class="carousel-card">
                    
                    {{-- Imagen --}}
                    <div class="carousel-img-container">
                        <img src="{{ asset($carrusel->imagen) }}" alt="Banner {{ $carrusel->id }}">
                        
                        {{-- Badge de Estado Flotante --}}
                        <div style="position: absolute; top: 10px; right: 10px;">
                            @if($carrusel->activo)
                                <span class="status-badge status-activa">Visible</span>
                            @else
                                <span class="status-badge status-inactiva">Oculto</span>
                            @endif
                        </div>
                    </div>

                    {{-- Info y Acciones --}}
                    <div class="carousel-body">
                        <div>
                            <span class="order-badge" title="Orden de aparición">#{{ $carrusel->orden }}</span>
                        </div>

                        <div class="action-links">
                            {{-- Toggle Activar/Desactivar --}}
                            <form action="{{ route('admin.carrusel.toggle', $carrusel->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-icon" title="{{ $carrusel->activo ? 'Ocultar' : 'Mostrar' }}" 
                                        style="color: {{ $carrusel->activo ? '#f59e0b' : '#10b981' }}; border-color: transparent;">
                                    {{ $carrusel->activo ? '👁️' : '🔒' }}
                                </button>
                            </form>

                            {{-- Editar --}}
                            <a href="{{ route('admin.carrusel.edit', $carrusel->id) }}" class="btn-icon" title="Editar">
                                ✏️
                            </a>

                            {{-- Eliminar --}}
                            <form action="{{ route('admin.carrusel.destroy', $carrusel->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta imagen del carrusel?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon delete" title="Eliminar">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card" style="text-align: center; padding: 50px;">
            <div style="font-size: 3rem; margin-bottom: 10px;">🖼️</div>
            <h3 style="color: var(--text-dark);">El carrusel está vacío</h3>
            <p style="color: var(--text-muted);">Agrega imágenes para destacar tus productos.</p>
        </div>
    @endif
@endsection