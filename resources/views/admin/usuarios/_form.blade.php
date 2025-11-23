@php
    $usuario = $usuario ?? null;
    $isEdit = $isEdit ?? false;
@endphp

<div class="form-grid">
    
    {{-- COLUMNA IZQUIERDA: Datos Personales --}}
    <div class="left-column">
        <h3 style="color: var(--accent); font-size: 1.2rem; margin-bottom: 20px; border-bottom: 2px solid var(--color-petal-glaze); display:inline-block;">
            Datos Personales
        </h3>

        {{-- Fila doble: Nombre y Apellido --}}
        <div class="row-2-cols">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" 
                       value="{{ old('nombre', $usuario?->nombre) }}" required>
            </div>
            <div class="form-group">
                <label for="apellido">Apellido</label>
                <input type="text" name="apellido" id="apellido" class="form-control" 
                       value="{{ old('apellido', $usuario?->apellido) }}" required>
            </div>
        </div>

        {{-- Email --}}
        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input type="email" name="email" id="email" class="form-control" 
                   value="{{ old('email', $usuario?->email) }}" required>
        </div>

        {{-- Teléfono --}}
        <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="text" name="telefono" id="telefono" class="form-control" 
                   value="{{ old('telefono', $usuario?->telefono) }}">
        </div>

        {{-- Dirección (Area de texto) --}}
        <div class="form-group">
            <label for="direccion">Dirección de Envío</label>
            <textarea name="direccion" id="direccion" rows="3" class="form-control">{{ old('direccion', $usuario?->direccion) }}</textarea>
        </div>
    </div>

    {{-- COLUMNA DERECHA: Cuenta y Seguridad --}}
    <div class="right-column">
        <h3 style="color: var(--accent); font-size: 1.2rem; margin-bottom: 20px; border-bottom: 2px solid var(--color-petal-glaze); display:inline-block;">
            Configuración de Cuenta
        </h3>

        {{-- Rol --}}
        <div class="form-group">
            <label for="rol">Rol de Usuario</label>
            <select name="rol" id="rol" class="form-control" required>
                <option value="cliente" {{ old('rol', $usuario?->rol) == 'cliente' ? 'selected' : '' }}>Cliente</option>
                <option value="admin" {{ old('rol', $usuario?->rol) == 'admin' ? 'selected' : '' }}>Administrador</option>
            </select>
        </div>

        {{-- Caja de Seguridad (Contraseña) --}}
        <div style="background: rgba(255,255,255,0.5); padding: 20px; border-radius: 16px; border: 1px solid var(--border); margin-top: 15px;">
            <label style="font-weight: 600; color: var(--text-dark); margin-bottom: 15px; display:block; display:flex; align-items:center; gap:8px;">
                🔒 Seguridad
            </label>
            
            <div class="form-group">
                <label for="password" style="font-size: 0.9em;">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" {{ $isEdit ? '' : 'required' }}>
                @if($isEdit)
                    <small style="color: var(--text-muted); font-style: italic; display:block; margin-top:5px;">
                        Déjalo vacío si no quieres cambiarla.
                    </small>
                @endif
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label for="password_confirmation" style="font-size: 0.9em;">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" {{ $isEdit ? '' : 'required' }}>
            </div>
        </div>
    </div>
</div>