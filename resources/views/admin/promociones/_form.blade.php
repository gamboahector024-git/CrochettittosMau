@php
    $isEdit = $isEdit ?? false;
    $promocion = $promocion ?? null;
    $producto = $producto ?? null;
@endphp

<div class="form-grid">
    
    {{-- COLUMNA IZQUIERDA: Info Básica --}}
    <div class="left-column">
        <h3 style="color: var(--accent); font-size: 1.2rem; margin-bottom: 20px; border-bottom: 2px solid var(--color-petal-glaze); display:inline-block;">
            Detalles de la Oferta
        </h3>

        <div class="form-group">
            <label for="titulo">Título de la Promoción</label>
            <input type="text" id="titulo" name="titulo" class="form-control" 
                   value="{{ old('titulo', $isEdit ? $promocion->titulo : '') }}" 
                   placeholder="Ej. Descuento de Verano" required>
            @error('titulo')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción <small>(Visible para el cliente)</small></label>
            <textarea id="descripcion" name="descripcion" class="form-control" rows="5"
                      placeholder="Aprovecha este descuento especial...">{{ old('descripcion', $isEdit ? $promocion->descripcion : '') }}</textarea>
            @error('descripcion')<div class="form-error">{{ $message }}</div>@enderror
        </div>
    </div>

    {{-- COLUMNA DERECHA: Configuración --}}
    <div class="right-column">
        <h3 style="color: var(--accent); font-size: 1.2rem; margin-bottom: 20px; border-bottom: 2px solid var(--color-petal-glaze); display:inline-block;">
            Configuración
        </h3>

        {{-- Tarjeta de Producto Seleccionado --}}
        <div style="background: rgba(255,255,255,0.5); padding: 15px; border-radius: 12px; border: 1px solid var(--border); margin-bottom: 20px;">
            <label style="margin-bottom: 5px; color: var(--text-muted);">Producto Aplicable:</label>
            <div style="font-weight: 700; color: var(--text-dark); font-size: 1.1rem;">
                @if($isEdit && $promocion)
                    {{ $promocion->producto->nombre }}
                @elseif($producto)
                    {{ $producto->nombre }}
                @else
                    Producto no especificado
                @endif
            </div>
            <input type="hidden" name="id_producto" value="{{ old('id_producto', $isEdit ? $promocion->id_producto : ($producto->id_producto ?? '')) }}">
            @error('id_producto')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        {{-- Descuento --}}
        <div class="row-2-cols">
            <div class="form-group">
                <label for="tipo">Tipo</label>
                <select id="tipo" name="tipo" class="form-control" required>
                    @php($tipoSel = old('tipo', $isEdit ? $promocion->tipo : 'porcentaje'))
                    <option value="porcentaje" {{ $tipoSel === 'porcentaje' ? 'selected' : '' }}>Porcentaje (%)</option>
                    <option value="fijo" {{ $tipoSel === 'fijo' ? 'selected' : '' }}>Monto Fijo ($)</option>
                </select>
                @error('tipo')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="valor">Valor</label>
                <input type="number" step="0.01" min="0" id="valor" name="valor" class="form-control" 
                       value="{{ old('valor', $isEdit ? $promocion->valor : '') }}" placeholder="0" required>
                @error('valor')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Fechas --}}
        <div class="row-2-cols">
            <div class="form-group">
                <label for="fecha_inicio">Inicia</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" 
                       value="{{ old('fecha_inicio', $isEdit ? optional($promocion->fecha_inicio)->format('Y-m-d') : '') }}" required>
                @error('fecha_inicio')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            
            <div class="form-group">
                <label for="fecha_fin">Termina</label>
                <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" 
                       value="{{ old('fecha_fin', $isEdit ? optional($promocion->fecha_fin)->format('Y-m-d') : '') }}" required>
                @error('fecha_fin')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
</div>