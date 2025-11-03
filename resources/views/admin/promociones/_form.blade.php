@php
    $isEdit = $isEdit ?? false;
    $promocion = $promocion ?? null;
    $id_producto_preseleccionado = request('id_producto', $isEdit ? $promocion->id_producto : '');
@endphp

<div class="form-group">
    <label for="titulo">Título</label>
    <input type="text" id="titulo" name="titulo" class="form-control" value="{{ old('titulo', $isEdit ? $promocion->titulo : '') }}" required>
    @error('titulo')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div class="form-group">
    <label for="descripcion">Descripción <small>(Opcional)</small></label>
    <textarea id="descripcion" name="descripcion" class="form-control" rows="3">{{ old('descripcion', $isEdit ? $promocion->descripcion : '') }}</textarea>
    @error('descripcion')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div class="form-group">
    <label for="id_producto">Producto</label>
    <select id="id_producto" name="id_producto" class="form-control" required>
        <option value="">Selecciona un producto</option>
        @foreach($productos as $producto)
            <option value="{{ $producto->id_producto }}" {{ old('id_producto', $id_producto_preseleccionado) == $producto->id_producto ? 'selected' : '' }}>
                {{ $producto->nombre }}
            </option>
        @endforeach
    </select>
    @error('id_producto')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
    <div class="form-group">
        <label for="tipo">Tipo de descuento</label>
        <select id="tipo" name="tipo" class="form-control" required>
            @php($tipoSel = old('tipo', $isEdit ? $promocion->tipo : 'porcentaje'))
            <option value="porcentaje" {{ $tipoSel === 'porcentaje' ? 'selected' : '' }}>Porcentaje (%)</option>
            <option value="fijo" {{ $tipoSel === 'fijo' ? 'selected' : '' }}>Fijo ($)</option>
        </select>
        @error('tipo')<div class="form-error">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
        <label for="valor">Valor</label>
        <input type="number" step="0.01" min="0" id="valor" name="valor" class="form-control" value="{{ old('valor', $isEdit ? $promocion->valor : '') }}" required>
        @error('valor')<div class="form-error">{{ $message }}</div>@enderror
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
    <div class="form-group">
        <label for="fecha_inicio">Fecha inicio</label>
        <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio', $isEdit ? $promocion->fecha_inicio : '') }}" required>
        @error('fecha_inicio')<div class="form-error">{{ $message }}</div>@enderror
    </div>
    
    <div class="form-group">
        <label for="fecha_fin">Fecha fin</label>
        <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" value="{{ old('fecha_fin', $isEdit ? $promocion->fecha_fin : '') }}" required>
        @error('fecha_fin')<div class="form-error">{{ $message }}</div>@enderror
    </div>
</div>

<div class="form-group">
    @php($activa = old('activa', $isEdit ? (int)$promocion->activa : 1))
    <div class="form-check-minimal">
        <input class="form-check-input" type="checkbox" id="activa" name="activa" value="1" {{ $activa ? 'checked' : '' }}>
        <label class="form-check-label" for="activa">Activar promoción al guardar</label>
    </div>
    @error('activa')<div class="form-error">{{ $message }}</div>@enderror
</div>