@extends('layouts.admin')

@section('title', 'Editar Imagen del Carrusel')

@section('content')
<div class="content-header">
    <h1>Editar Imagen del Carrusel</h1>
    <a href="{{ route('admin.carrusel.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

@if($errors->any())
    <div class="alert alert-error">
        <strong>¡Error en la validación!</strong>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-container">
    <form action="{{ route('admin.carrusel.update', $carrusel->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="imagen">Imagen</label>
            <div class="current-image" style="margin-bottom: 15px;">
                <p><strong>Imagen actual:</strong></p>
                <img src="{{ asset($carrusel->imagen) }}" alt="Imagen actual" style="max-width: 300px; max-height: 200px; border-radius: 8px;">
            </div>
            <div class="file-input-wrapper">
                <input 
                    type="file" 
                    id="imagen" 
                    name="imagen" 
                    class="form-control @error('imagen') is-invalid @enderror"
                    accept="image/*"
                >
                <small>Dejar vacío para mantener la imagen actual | Formatos: JPG, PNG, GIF, WEBP | Máximo: 5MB</small>
            </div>
            <div id="preview-container" style="margin-top: 15px; display: none;">
                <p><strong>Nueva imagen:</strong></p>
                <img id="preview" style="max-width: 300px; max-height: 300px; border-radius: 8px;">
            </div>
            @error('imagen')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="orden">Orden</label>
            <input 
                type="number" 
                id="orden" 
                name="orden" 
                class="form-control @error('orden') is-invalid @enderror"
                placeholder="0"
                min="0"
                value="{{ old('orden', $carrusel->orden) }}"
            >
            <small>Número menor = aparece primero en el carrusel</small>
            @error('orden')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Actualizar Imagen
            </button>
            <a href="{{ route('admin.carrusel.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>
</div>

<style>
    .form-container {
        background: #fff;
        border: 1px solid #e6e6e6;
        border-radius: 8px;
        padding: 30px;
        max-width: 600px;
        margin: 20px 0;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
    }

    .form-group small {
        display: block;
        margin-top: 4px;
        color: #666;
        font-size: 12px;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .form-control.is-invalid {
        border-color: #dc3545;
    }

    .error-message {
        display: block;
        margin-top: 4px;
        color: #dc3545;
        font-size: 13px;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }

    .form-actions .btn {
        flex: 1;
        padding: 12px 20px;
    }

    .btn {
        display: inline-block;
        padding: 10px 16px;
        border: none;
        border-radius: 4px;
        font-size: 14px;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-success {
        background: #28a745;
        color: white;
    }

    .btn-success:hover {
        background: #218838;
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background: #5a6268;
    }

    .current-image p {
        margin: 0 0 10px 0;
        font-size: 14px;
        color: #666;
    }
</style>

<script>
    document.getElementById('imagen').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('preview').src = event.target.result;
                document.getElementById('preview-container').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
</script>

@endsection
