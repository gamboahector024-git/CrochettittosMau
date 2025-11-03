@php($isEdit = isset($usuario))

{{-- 
Quitamos el <div> con "display:grid" porque 
la clase .form-group se encarga de espaciar todo.
--}}

<div class="form-group">
    <label for="nombre">Nombre</label>
    <input id="nombre" name="nombre" type="text" class="form-control" value="{{ old('nombre', $isEdit ? $usuario->nombre : '') }}" required>
    @error('nombre')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div class="form-group">
    <label for="apellido">Apellido</label>
    <input id="apellido" name="apellido" type="text" class="form-control" value="{{ old('apellido', $isEdit ? $usuario->apellido : '') }}" required>
    @error('apellido')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div class="form-group">
    <label for="email">Email</label>
    <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $isEdit ? $usuario->email : '') }}" required>
    @error('email')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div class="form-group">
    {{-- Moví la nota opcional a una etiqueta <small> para darle estilo --}}
    <label for="password">Contraseña @if($isEdit)<small>(dejar en blanco si no cambia)</small>@endif</label>
    <input id="password" name="password" type="password" class="form-control" {{ $isEdit ? '' : 'required' }}>
    @error('password')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div class="form-group">
    <label for="password_confirmation">Confirmar Contraseña</label>
    <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" {{ $isEdit ? '' : 'required' }}>
</div>

<div class="form-group">
    <label for="direccion">Dirección</label>
    <textarea id="direccion" name="direccion" rows="3" class="form-control">{{ old('direccion', $isEdit ? $usuario->direccion : '') }}</textarea>
    @error('direccion')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div class="form-group">
    <label for="telefono">Teléfono</label>
    <input id="telefono" name="telefono" type="text" class="form-control" value="{{ old('telefono', $isEdit ? $usuario->telefono : '') }}">
    @error('telefono')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div class="form-group">
    <label for="rol">Rol</label>
    @php($current = old('rol', $isEdit ? $usuario->rol : 'cliente'))
    <select id="rol" name="rol" class="form-control" required>
        <option value="cliente" {{ $current === 'cliente' ? 'selected' : '' }}>Cliente</option>
        <option value="admin" {{ $current === 'admin' ? 'selected' : '' }}>Admin</option>
    </select>
    @error('rol')<div class="form-error">{{ $message }}</div>@enderror
</div>