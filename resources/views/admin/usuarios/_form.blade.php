@php($isEdit = isset($usuario))
<div style="display:grid; gap:12px; max-width:640px;">
    <div>
        <label for="nombre">Nombre</label>
        <input id="nombre" name="nombre" type="text" value="{{ old('nombre', $isEdit ? $usuario->nombre : '') }}" required>
        @error('nombre')<div class="error">{{ $message }}</div>@enderror
    </div>

    <div>
        <label for="apellido">Apellido</label>
        <input id="apellido" name="apellido" type="text" value="{{ old('apellido', $isEdit ? $usuario->apellido : '') }}" required>
        @error('apellido')<div class="error">{{ $message }}</div>@enderror
    </div>

    <div>
        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $isEdit ? $usuario->email : '') }}" required>
        @error('email')<div class="error">{{ $message }}</div>@enderror
    </div>

    <div>
        <label for="password">Contraseña {{ $isEdit ? '(dejar en blanco si no cambia)' : '' }}</label>
        <input id="password" name="password" type="password" {{ $isEdit ? '' : 'required' }}>
        @error('password')<div class="error">{{ $message }}</div>@enderror
    </div>

    <div>
        <label for="password_confirmation">Confirmar Contraseña</label>
        <input id="password_confirmation" name="password_confirmation" type="password" {{ $isEdit ? '' : 'required' }}>
    </div>

    <div>
        <label for="direccion">Dirección</label>
        <textarea id="direccion" name="direccion" rows="3">{{ old('direccion', $isEdit ? $usuario->direccion : '') }}</textarea>
        @error('direccion')<div class="error">{{ $message }}</div>@enderror
    </div>

    <div>
        <label for="telefono">Teléfono</label>
        <input id="telefono" name="telefono" type="text" value="{{ old('telefono', $isEdit ? $usuario->telefono : '') }}">
        @error('telefono')<div class="error">{{ $message }}</div>@enderror
    </div>

    <div>
        <label for="rol">Rol</label>
        @php($current = old('rol', $isEdit ? $usuario->rol : 'cliente'))
        <select id="rol" name="rol" required>
            <option value="cliente" {{ $current === 'cliente' ? 'selected' : '' }}>Cliente</option>
            <option value="admin" {{ $current === 'admin' ? 'selected' : '' }}>Admin</option>
        </select>
        @error('rol')<div class="error">{{ $message }}</div>@enderror
    </div>
</div>
