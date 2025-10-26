@php($isEdit = isset($producto))
<div style="display:grid; gap:12px; max-width:640px;">
    <div>
        <label for="nombre">Nombre</label>
        <input id="nombre" name="nombre" type="text" value="{{ old('nombre', $isEdit ? $producto->nombre : '') }}" required>
        @error('nombre')<div class="error">{{ $message }}</div>@enderror
    </div>

    <div>
        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion" rows="4">{{ old('descripcion', $isEdit ? $producto->descripcion : '') }}</textarea>
        @error('descripcion')<div class="error">{{ $message }}</div>@enderror
    </div>

    <div>
        <label for="precio">Precio</label>
        <input id="precio" name="precio" type="number" step="0.01" min="0" value="{{ old('precio', $isEdit ? $producto->precio : '') }}" required>
        @error('precio')<div class="error">{{ $message }}</div>@enderror
    </div>

    <div>
        <label for="categoria">Categoría</label>
        <select id="categoria" name="categoria" required>
            @php($current = old('categoria', $isEdit ? $producto->categoria : ''))
            <option value="" disabled {{ $current === '' ? 'selected' : '' }}>Selecciona una categoría</option>
            <option value="llaveros" {{ $current === 'llaveros' ? 'selected' : '' }}>Llaveros</option>
            <option value="flores" {{ $current === 'flores' ? 'selected' : '' }}>Flores</option>
            <option value="personalizados" {{ $current === 'personalizados' ? 'selected' : '' }}>Personalizados</option>
        </select>
        @error('categoria')<div class="error">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label for="stock" class="form-label">Stock</label>
        <input type="number" min="0" class="form-control" id="stock" name="stock" 
               value="{{ old('stock', $producto->stock ?? '') }}" required>
    </div>

    <div>
        <label for="imagen">Imagen</label>
        <input id="imagen" name="imagen" type="file" accept="image/*">
        @error('imagen')<div class="error">{{ $message }}</div>@enderror
        @if($isEdit && $producto->imagen_url)
            <div style="margin-top:8px;">
                <img src="/{{ $producto->imagen_url }}" alt="Imagen actual" style="max-width:180px; max-height:180px; object-fit:cover;">
            </div>
        @endif
    </div>
</div>
