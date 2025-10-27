<div class="mb-3">
    <label for="titulo" class="form-label">Título</label>
    <input type="text" id="titulo" name="titulo" class="form-control" value="{{ old('titulo', $promocion->titulo ?? '') }}" required>
    @error('titulo')<div class="text-danger">{{ $message }}</div>@enderror
    
</div>

<div class="mb-3">
    <label for="descripcion" class="form-label">Descripción</label>
    <textarea id="descripcion" name="descripcion" class="form-control" rows="3">{{ old('descripcion', $promocion->descripcion ?? '') }}</textarea>
    @error('descripcion')<div class="text-danger">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label for="tipo" class="form-label">Tipo de descuento</label>
    <select id="tipo" name="tipo" class="form-select" required>
        @php($tipoSel = old('tipo', $promocion->tipo ?? 'porcentaje'))
        <option value="porcentaje" {{ $tipoSel === 'porcentaje' ? 'selected' : '' }}>Porcentaje</option>
        <option value="fijo" {{ $tipoSel === 'fijo' ? 'selected' : '' }}>Fijo</option>
    </select>
    @error('tipo')<div class="text-danger">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label for="valor" class="form-label">Valor</label>
    <input type="number" step="0.01" min="0" id="valor" name="valor" class="form-control" value="{{ old('valor', $promocion->valor ?? '') }}" required>
    @error('valor')<div class="text-danger">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label for="id_producto" class="form-label">Producto</label>
    <select id="id_producto" name="id_producto" class="form-select" required>
        <option value="">Selecciona un producto</option>
        @foreach($productos as $producto)
            <option value="{{ $producto->id_producto }}" {{ old('id_producto', $promocion->id_producto ?? '') == $producto->id_producto ? 'selected' : '' }}>
                {{ $producto->nombre }}
            </option>
        @endforeach
    </select>
    @error('id_producto')<div class="text-danger">{{ $message }}</div>@enderror
</div>

<div class="row g-3">
    <div class="col-md-6">
        <label for="fecha_inicio" class="form-label">Fecha inicio</label>
        <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio', isset($promocion) ? $promocion->fecha_inicio : '') }}" required>
        @error('fecha_inicio')<div class="text-danger">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="fecha_fin" class="form-label">Fecha fin</label>
        <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" value="{{ old('fecha_fin', isset($promocion) ? $promocion->fecha_fin : '') }}" required>
        @error('fecha_fin')<div class="text-danger">{{ $message }}</div>@enderror
    </div>
</div>

<div class="form-check mt-3">
    @php($activa = old('activa', isset($promocion) ? (int)$promocion->activa : 1))
    <input class="form-check-input" type="checkbox" id="activa" name="activa" value="1" {{ $activa ? 'checked' : '' }}>
    <label class="form-check-label" for="activa">Activa</label>
    @error('activa')<div class="text-danger">{{ $message }}</div>@enderror
</div>
