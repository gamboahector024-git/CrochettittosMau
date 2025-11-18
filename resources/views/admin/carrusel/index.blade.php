@extends('layouts.admin')

@section('title', 'Gestionar Carrusel')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-carrusel.css') }}">
@endpush

@section('content')
<div class="content-header">
    <h1>Gestionar Carrusel</h1>
    <a href="{{ route('admin.carrusel.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Agregar Imagen
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
@endif

<div class="admin-section">
    <div class="section-title">Imágenes del Carrusel</div>

    @if($carruseles->isNotEmpty())
        <div class="admin-carousel" data-carousel>
            <div class="admin-carousel-window">
                <div class="admin-carousel-track">
                    @foreach($carruseles as $carrusel)
                        <article class="carrusel-card">
                            <div class="carrusel-image">
                                <img src="{{ asset($carrusel->imagen) }}" alt="Imagen del carrusel">
                            </div>

                            <div class="carrusel-info">
                                <p><strong>ID:</strong> {{ $carrusel->id }}</p>
                                <p><strong>Orden:</strong> {{ $carrusel->orden }}</p>
                            </div>

                            <div class="carrusel-actions">
                                <form action="{{ route('admin.carrusel.toggle', $carrusel->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $carrusel->activo ? 'btn-success' : 'btn-secondary' }}">
                                        {{ $carrusel->activo ? 'Ocultar' : 'Mostrar' }}
                                    </button>
                                </form>

                                <a href="{{ route('admin.carrusel.edit', $carrusel->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Editar
                                </a>

                                <form action="{{ route('admin.carrusel.destroy', $carrusel->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar esta imagen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="carousel-nav">
                <button type="button" class="carousel-prev" aria-label="Anterior">&#10094;</button>
                <button type="button" class="carousel-next" aria-label="Siguiente">&#10095;</button>
            </div>
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-image"></i>
            <p>No hay imágenes en el carrusel</p>
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/admin/carrusel.js') }}"></script>
@endpush
