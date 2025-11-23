@php
    // Inicialización de variables para evitar errores
    $isEdit = $isEdit ?? false;
    $producto = $producto ?? null;
@endphp

{{-- Usamos la clase .form-grid que definimos en el CSS para crear las 2 columnas --}}
<div class="form-grid">
    
    {{-- COLUMNA IZQUIERDA: Datos del Texto --}}
    <div class="left-column">
        
        {{-- Nombre --}}
        <div class="form-group">
            <label for="nombre">Nombre del Producto</label>
            <input id="nombre" name="nombre" type="text" class="form-control" 
                   value="{{ old('nombre', $isEdit ? $producto->nombre : '') }}" 
                   placeholder="Ej. Llavero Corazón" required>
            @error('nombre')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        {{-- Descripción --}}
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" rows="6" class="form-control"
                      placeholder="Detalles del producto...">{{ old('descripcion', $isEdit ? $producto->descripcion : '') }}</textarea>
            @error('descripcion')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        {{-- Precio y Stock (Fila doble) --}}
        <div class="row-2-cols">
            <div class="form-group">
                <label for="precio">Precio ($)</label>
                <input id="precio" name="precio" type="number" step="0.01" min="0" class="form-control" 
                       value="{{ old('precio', $isEdit ? $producto->precio : '') }}" required>
                @error('precio')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="stock">Stock</label>
                <input id="stock" name="stock" type="number" min="0" class="form-control" 
                       value="{{ old('stock', $isEdit ? $producto->stock : '0') }}" required>
                @error('stock')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Categoría --}}
        <div class="form-group">
            <label for="id_categoria">Categoría</label>
            <select id="id_categoria" name="id_categoria" class="form-control" required>
                @php($current = old('id_categoria', $isEdit ? $producto->id_categoria : ''))
                <option value="" disabled {{ $current === '' ? 'selected' : '' }}>Selecciona una categoría...</option>
                
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
    </div>

    {{-- COLUMNA DERECHA: Carga de Imagen Moderna --}}
    <div class="right-column">
        <label style="display:block; margin-bottom: 8px; font-weight: 500; color: var(--text-light);">
            Imagen del Producto <small>(Opcional)</small>
        </label>
        
        <div class="image-upload-wrapper">
            <div class="upload-area" id="uploadArea">
                
                {{-- Lógica para mostrar placeholder o imagen existente --}}
                @php
                    $hasImage = $isEdit && $producto->imagen_url;
                @endphp

                <div id="uploadPlaceholder" style="{{ $hasImage ? 'opacity: 0;' : 'opacity: 1;' }}">
                    <div class="upload-icon">📸</div>
                    <div class="upload-text">
                        <span>{{ $isEdit ? 'Cambiar imagen' : 'Subir imagen' }}</span><br>
                        Arrastra o haz clic
                    </div>
                </div>
                
                <img id="imagePreview" class="image-preview" 
                     src="{{ $hasImage ? asset($producto->imagen_url) : '#' }}" 
                     alt="Vista previa"
                     style="{{ $hasImage ? 'display: block;' : 'display: none;' }}">
            </div>

            {{-- Input oculto --}}
            <input id="imagen" name="imagen" type="file" class="file-input-hidden" accept="image/*" onchange="previewImage(this)">
        </div>
        @error('imagen')<div class="form-error">{{ $message }}</div>@enderror

        @if($hasImage)
            <small style="display:block; text-align:center; margin-top:10px; color:var(--text-muted);">
                Imagen actual guardada.
            </small>
        @endif
    </div>
</div>

{{-- Script necesario para la previsualización (incluido en el partial para que siempre funcione) --}}
<script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const placeholder = document.getElementById('uploadPlaceholder');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                placeholder.style.opacity = '0';
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>