@extends('layouts.cliente')

@section('title', 'Editar Perfil - Crochettitos')

@section('content')
<div class="profile-edit-container">
    <div class="edit-header">
        <h1><i class="fas fa-user-edit"></i> Editar Perfil</h1>
    </div>

    <form action="{{ route('perfil.update') }}" method="POST" class="edit-form">
        @csrf
        @method('PUT')
        
        <!-- Nombre -->
        <div class="form-group">
            <label for="nombre"><i class="fas fa-signature"></i> Nombre:</label>
            <input type="text" id="nombre" name="nombre" 
                   value="{{ old('nombre', $usuario->nombre) }}" 
                   required
                   class="@error('nombre') is-invalid @enderror">
            @error('nombre')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Teléfono -->
        <div class="form-group">
            <label for="telefono"><i class="fas fa-phone"></i> Teléfono:</label>
            <input type="text" id="telefono" name="telefono" 
                   value="{{ old('telefono', $usuario->telefono) }}"
                   class="@error('telefono') is-invalid @enderror">
            @error('telefono')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Dirección -->
        <div class="form-group">
            <label for="direccion"><i class="fas fa-map-marker-alt"></i> Dirección:</label>
            <textarea id="direccion" name="direccion"
                      class="@error('direccion') is-invalid @enderror">{{ old('direccion', $usuario->direccion) }}</textarea>
            @error('direccion')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Campos adicionales -->
        <div class="form-group">
            <label for="apellido"><i class="fas fa-user-tag"></i> Apellido:</label>
            <input type="text" id="apellido" name="apellido" 
                value="{{ old('apellido', $usuario->apellido) }}"
                class="@error('apellido') is-invalid @enderror">
            @error('apellido')<div class="error-message">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="email"><i class="fas fa-envelope"></i> Email:</label>
            <input type="email" id="email" name="email" 
                value="{{ old('email', $usuario->email) }}"
                required
                class="@error('email') is-invalid @enderror">
            @error('email')<div class="error-message">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="password"><i class="fas fa-lock"></i> Nueva Contraseña:</label>
            <input type="password" id="password" name="password"
                class="@error('password') is-invalid @enderror">
            @error('password')<div class="error-message">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation"><i class="fas fa-lock"></i> Confirmar Contraseña:</label>
            <input type="password" id="password_confirmation" name="password_confirmation">
        </div>

        <!-- Botones -->
        <div class="form-actions">
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> Guardar Cambios
            </button>
            <a href="{{ route('perfil.index') }}" class="btn-cancel">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </form>
</div>
@endsection