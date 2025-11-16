@extends('layouts.admin')

@section('title', 'Crear Imagen del Carrusel')

@section('content')
<div class="content-header">
    <h1>Agregar Nueva Imagen al Carrusel</h1>
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
    <form action="{{ route('admin.carrusel.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="imagen">Imagen del Carrusel</label>
            <input 
                type="file" 
                id="imagen" 
                name="imagen" 
                class="form-control @error('imagen') is-invalid @enderror"
                accept="image/*"
                required
            >
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
                value="{{ old('orden', 0) }}"
            >
            <small>Número menor = aparece primero en el carrusel</small>
            @error('orden')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Guardar Imagen
            </button>
            <a href="{{ route('admin.carrusel.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>
</div>


@endsection
