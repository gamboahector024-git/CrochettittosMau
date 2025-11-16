@php
    $isEdit = $isEdit ?? false;
    $promocion = $promocion ?? null;
    $producto = $producto ?? null;
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
    <label>Producto</label>
    <p style="margin: 0 0 6px;">
        @if($isEdit && $promocion)
            {{ $promocion->producto->nombre }}
        @elseif($producto)
            {{ $producto->nombre }}
        @else
            Producto no especificado
        @endif
    </p>
    <input type="hidden" name="id_producto" value="{{ old('id_producto', $isEdit ? $promocion->id_producto : ($producto->id_producto ?? '')) }}">
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
        <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio', $isEdit ? optional($promocion->fecha_inicio)->format('Y-m-d') : '') }}" required>
        @error('fecha_inicio')<div class="form-error">{{ $message }}</div>@enderror
    </div>
    
    <div class="form-group">
        <label for="fecha_fin">Fecha fin</label>
        <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" value="{{ old('fecha_fin', $isEdit ? optional($promocion->fecha_fin)->format('Y-m-d') : '') }}" required>
        @error('fecha_fin')<div class="form-error">{{ $message }}</div>@enderror
    </div>
</div>