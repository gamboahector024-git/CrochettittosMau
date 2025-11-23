@extends('layouts.admin')

@section('title', 'Editar Producto')

@section('content')
    {{-- Encabezado --}}
    <div class="content-header" style="margin-bottom: 30px;">
        <h1 style="font-family: var(--font-heading); color: var(--accent); font-size: 2.5rem;">
            Editar Producto
        </h1>
        <p style="color: var(--text-muted);">Actualizando: <strong>{{ $producto->nombre }}</strong></p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tarjeta Principal Glassmorphism --}}
    <div class="card">
        <form action="{{ route('admin.productos.update', $producto) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-grid">
                
                {{-- COLUMNA IZQUIERDA: Datos --}}
                <div class="left-column">
                    
                    {{-- Nombre --}}
                    <div class="form-group">
                        <label for="nombre">Nombre del Producto</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" 
                               value="{{ old('nombre', $producto->nombre) }}" required>
                    </div>

                    {{-- Descripción --}}
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea name="descripcion" id="descripcion" rows="6" class="form-control">{{ old('descripcion', $producto->descripcion) }}</textarea>
                    </div>

                    {{-- Precio y Stock --}}
                    <div class="row-2-cols">
                        <div class="form-group">
                            <label for="precio">Precio ($)</label>
                            <input type="number" step="0.01" name="precio" id="precio" class="form-control" 
                                   value="{{ old('precio', $producto->precio) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="stock">Stock</label>
                            <input type="number" name="stock" id="stock" class="form-control" 
                                   value="{{ old('stock', $producto->stock) }}" required>
                        </div>
                    </div>

                     {{-- Categoría --}}
                     <div class="form-group">
                        <label for="id_categoria">Categoría</label>
                        <select name="id_categoria" id="id_categoria" class="form-control" required>
                            <option value="" disabled>Selecciona una categoría...</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id_categoria }}" 
                                    {{ old('id_categoria', $producto->id_categoria) == $categoria->id_categoria ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- COLUMNA DERECHA: Imagen --}}
                <div class="right-column">
                    <label style="display:block; margin-bottom: 8px; font-weight: 500; color: var(--text-light);">
                        Imagen del Producto
                    </label>
                    
                    <div class="image-upload-wrapper">
                        <div class="upload-area" id="uploadArea">
                            
                            {{-- Placeholder: Solo visible si NO hay imagen actual --}}
                            <div id="uploadPlaceholder" style="{{ $producto->imagen_url ? 'opacity: 0;' : 'opacity: 1;' }}">
                                <div class="upload-icon">📸</div>
                                <div class="upload-text">
                                    <span>Cambiar imagen</span><br>
                                    Arrastra o haz clic
                                </div>
                            </div>
                            
                            {{-- Previsualización: Muestra la imagen actual de la BD o la nueva seleccionada --}}
                            <img id="imagePreview" class="image-preview" 
                                 src="{{ $producto->imagen_url ? asset($producto->imagen_url) : '#' }}" 
                                 alt="Vista previa"
                                 style="{{ $producto->imagen_url ? 'display: block;' : 'display: none;' }}">
                        </div>

                        <input type="file" name="imagen" id="imagen" class="file-input-hidden" accept="image/*" onchange="previewImage(this)">
                    </div>
                    
                    @if($producto->imagen_url)
                        <small style="display:block; text-align:center; margin-top:10px; color:var(--text-muted);">
                            Actualmente se muestra la imagen guardada.
                        </small>
                    @endif
                </div>

            </div>

            {{-- Botones --}}
            <div class="form-actions" style="margin-top: 40px; border-top: 1px solid var(--border); padding-top: 20px; justify-content: flex-end;">
                <a href="{{ route('admin.productos.index') }}" class="btn btn-secondary" style="margin-right: 10px;">Cancelar</a>
                <button type="submit" class="btn btn-primary" style="padding: 12px 30px;">
                    Actualizar Producto
                </button>
            </div>
        </form>
    </div>

    {{-- Script JS para actualizar la vista previa al seleccionar archivo --}}
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
            // Nota: Si cancelan la selección, mantenemos la imagen que ya estaba (o la vacía)
        }
    </script>
@endsection