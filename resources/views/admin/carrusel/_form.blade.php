@php
    $isEdit = isset($carrusel);
    $carrusel = $carrusel ?? null;
@endphp

<div class="form-grid">
    
    {{-- COLUMNA IZQUIERDA: Carga de Imagen --}}
    <div class="left-column">
        <label style="display:block; margin-bottom: 8px; font-weight: 500; color: var(--text-light);">
            Imagen del Banner (Formato horizontal recomendado)
        </label>
        
        <div class="image-upload-wrapper">
            <div class="upload-area" id="uploadArea">
                {{-- Lógica para mostrar placeholder o imagen existente --}}
                @php
                    $hasImage = $isEdit && $carrusel->imagen;
                @endphp

                <div id="uploadPlaceholder" style="{{ $hasImage ? 'opacity: 0;' : 'opacity: 1;' }}">
                    <div class="upload-icon">🌄</div>
                    <div class="upload-text">
                        <span>{{ $isEdit ? 'Cambiar imagen' : 'Subir imagen' }}</span><br>
                        Arrastra o haz clic
                    </div>
                </div>
                
                <img id="imagePreview" class="image-preview" 
                     src="{{ $hasImage ? asset($carrusel->imagen) : '#' }}" 
                     alt="Vista previa"
                     style="{{ $hasImage ? 'display: block;' : 'display: none;' }} object-fit: cover;">
            </div>

            <input id="imagen" name="imagen" type="file" class="file-input-hidden" accept="image/*" onchange="previewImage(this)" {{ $isEdit ? '' : 'required' }}>
        </div>
        @error('imagen')<div class="form-error">{{ $message }}</div>@enderror
    </div>

    {{-- COLUMNA DERECHA: Configuración --}}
    <div class="right-column">
        <h3 style="color: var(--accent); font-size: 1.2rem; margin-bottom: 20px; border-bottom: 2px solid var(--color-petal-glaze); display:inline-block;">
            Configuración
        </h3>

        <div class="form-group">
            <label for="orden">Orden de aparición</label>
            <input type="number" id="orden" name="orden" class="form-control" 
                   value="{{ old('orden', $isEdit ? $carrusel->orden : 0) }}" min="0">
            <small style="color: var(--text-muted);">El número 0 aparece primero, luego el 1, etc.</small>
            @error('orden')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="activo">Visibilidad</label>
            <select name="activo" id="activo" class="form-control">
                <option value="1" {{ old('activo', $isEdit ? $carrusel->activo : 1) == 1 ? 'selected' : '' }}>Visible</option>
                <option value="0" {{ old('activo', $isEdit ? $carrusel->activo : 1) == 0 ? 'selected' : '' }}>Oculto</option>
            </select>
            @error('activo')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        
        <div class="alert alert-secondary" style="margin-top: 20px;">
            <strong>💡 Tip:</strong><br>
            Asegúrate de que las imágenes tengan buena calidad y un tamaño similar (ej. 1920x600px) para que el carrusel se vea uniforme.
        </div>
    </div>
</div>

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