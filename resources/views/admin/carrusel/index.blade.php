@extends('layouts.admin')

@section('title', 'Gestionar Carrusel')

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
        <div class="carrusel-grid">
            @foreach($carruseles as $carrusel)
                <div class="carrusel-card">
                    <div class="carrusel-image">
                        <img src="{{ asset($carrusel->imagen) }}" alt="Imagen del carrusel">
                    </div>
                    <div class="carrusel-info">
                        <p><strong>ID:</strong> {{ $carrusel->id }}</p>
                        <p><strong>Orden:</strong> {{ $carrusel->orden }}</p>
                    </div>
                    <div class="carrusel-actions">
                        <a href="{{ route('admin.carrusel.edit', $carrusel->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <form action="{{ route('admin.carrusel.destroy', $carrusel->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar esta imagen?')">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <style>
            .carrusel-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 20px;
                margin-top: 20px;
            }

            .carrusel-card {
                background: #fff;
                border: 1px solid #e6e6e6;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                transition: all 0.3s ease;
            }

            .carrusel-card:hover {
                box-shadow: 0 4px 16px rgba(0,0,0,0.15);
                transform: translateY(-2px);
            }

            .carrusel-image {
                width: 100%;
                height: 200px;
                overflow: hidden;
                background: #f5f5f5;
            }

            .carrusel-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .carrusel-info {
                padding: 15px;
                border-bottom: 1px solid #f0f0f0;
            }

            .carrusel-info p {
                margin: 5px 0;
                font-size: 13px;
                color: #666;
            }

            .carrusel-actions {
                padding: 12px 15px;
                display: flex;
                gap: 8px;
            }

            .carrusel-actions .btn {
                flex: 1;
                text-align: center;
            }
        </style>
    @else
        <div class="empty-state">
            <i class="fas fa-image"></i>
            <p>No hay imágenes en el carrusel</p>
            <a href="{{ route('admin.carrusel.create') }}" class="btn btn-primary">Crear primera imagen</a>
        </div>
    @endif
</div>

@endsection
