@php
    // Definimos $isEdit y $producto si no vienen (para el formulario de 'crear')
    $isEdit = $isEdit ?? false;
    $producto = $producto ?? null;
@endphp

<div class="form-group">
    <label for="nombre">Nombre</label>
    <input id="nombre" name="nombre" type="text" class="form-control" value="{{ old('nombre', $isEdit ? $producto->nombre : '') }}" required>
    @error('nombre')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div class="form-group">
    <label for="descripcion">Descripción</label>
    <textarea id="descripcion" name="descripcion" rows="4" class="form-control">{{ old('descripcion', $isEdit ? $producto->descripcion : '') }}</textarea>
    @error('descripcion')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
    <div class="form-group">
        <label for="precio">Precio</label>
        <input id="precio" name="precio" type="number" step="0.01" min="0" class="form-control" value="{{ old('precio', $isEdit ? $producto->precio : '') }}" required>
        @error('precio')<div class="form-error">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
        <label for="stock">Stock</label>
        <input type="number" min="0" class="form-control" id="stock" name="stock" 
               value="{{ old('stock', $isEdit ? $producto->stock : '0') }}" required>
        @error('stock')<div class="form-error">{{ $message }}</div>@enderror
    </div>
</div>

<div class="form-group">
    <label for="id_categoria">Categoría</label>
    <select id="id_categoria" name="id_categoria" class="form-control">
        @php($current = old('id_categoria', $isEdit ? $producto->id_categoria : ''))
        <option value="" {{ $current === '' ? 'selected' : '' }}>Sin categoría</option>
        @isset($categorias)
            @foreach($categorias as $cat)
                <option value="{{ $cat->id_categoria }}" {{ (string)$current === (string)$cat->id_categoria ? 'selected' : '' }}>
                    {{ $cat->nombre }}
                </option>
            @endforeach
        @endisset
    </select>
    @error('id_categoria')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div class="form-group">
    <label for="imagen">Imagen del Producto <small>(Opcional)</small></label>
    <input id="imagen" name="imagen" type="file" class="form-control" accept="image/*">
    @error('imagen')<div class="form-error">{{ $message }}</div>@enderror
    
    @if($isEdit && $producto->imagen_url)
        <div style="margin-top:15px;">
            <label>Imagen actual:</label>
            <img src="/{{ $producto->imagen_url }}" alt="Imagen actual" class="form-image-preview">
        </div>
    @endif
</div>