@extends('layouts.cliente')

@section('title', 'Editar Perfil - Crochettittos')

@section('content')
<div class="container">
    {{-- 1. Usamos el mismo contenedor de "Mi Perfil" para la consistencia --}}
    <div class="profile-container">

        {{-- 2. Usamos el mismo estilo de título --}}
        <h2 class="profile-title">Editar Perfil</h2>

        {{-- 
          Asegúrate de que esta ruta 'perfil.update' exista en 'routes/web.php'
          y que sea de tipo PATCH o PUT.
        --}}
        <form action="{{ route('perfil.update') }}" method="POST">
            @csrf
            @method('PATCH') {{-- O 'PUT' --}}

            {{-- 3. Usamos un grid para organizar el formulario --}}
            <div class="form-grid">
                
                {{-- CAMPO: NOMBRE --}}
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="form-input" 
                           value="{{ old('nombre', $usuario->nombre) }}" required>
                </div>

                {{-- CAMPO: APELLIDO (lo vi en tu captura) --}}
                <div class="form-group">
                    <label for="apellido">Apellido</label>
                    <input type="text" id="apellido" name="apellido" class="form-input" 
                           value="{{ old('apellido', $usuario->apellido) ?? '' }}">
                </div>

                {{-- CAMPO: TELÉFONO --}}
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" class="form-input" 
                           value="{{ old('telefono', $usuario->telefono) ?? '' }}">
                </div>

                {{-- CAMPO: EMAIL (deshabilitado) --}}
                <div class="form-group">
                    <label for="email">Email (no se puede cambiar)</label>
                    <input type="email" id="email" name="email" class="form-input" 
                           value="{{ $usuario->email }}" disabled>
                </div>

                {{-- CAMPO: DIRECCIÓN (ocupa todo el ancho) --}}
                <div class="form-group full-width">
                    <label for="direccion">Dirección</label>
                    <textarea id="direccion" name="direccion" class="form-input" 
                              rows="3">{{ old('direccion', $usuario->direccion) ?? '' }}</textarea>
                </div>
            </div>

            {{-- 4. Sección para cambiar contraseña --}}
            <h3 class="form-subtitle">Cambiar Contraseña</h3>
            <p class="form-hint">
                Deja estos campos en blanco si no deseas cambiar tu contraseña.
            </p>

            <div class="form-grid">
                {{-- CAMPO: NUEVA CONTRASEÑA --}}
                <div class="form-group">
                    <label for="password">Nueva Contraseña</label>
                    <div class="password-field">
                        <input type="password" id="password" name="password" class="form-input password-input" 
                               autocomplete="new-password">
                        <button type="button" class="toggle-password-btn" data-target="password" title="Mostrar/Ocultar contraseña">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                {{-- CAMPO: CONFIRMAR CONTRASEÑA --}}
                <div class="form-group">
                    <label for="password_confirmation">Confirmar Contraseña</label>
                    <div class="password-field">
                        <input type="password" id="password_confirmation" name="password_confirmation" 
                               class="form-input password-input">
                        <button type="button" class="toggle-password-btn" data-target="password_confirmation" title="Mostrar/Ocultar contraseña">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- 5. Botones de acción --}}
            <div class="form-actions">
                <button type="submit" class="primary-button">Guardar Cambios</button>
                <a href="{{ route('perfil.index') }}" class="tertiary-button">Cancelar</a>
            </div>

        </form>
    </div>
</div>

@endsection