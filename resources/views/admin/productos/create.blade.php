@extends('layouts.admin')

@section('title', 'Nuevo Producto')

@section('content')
    {{-- Encabezado Elegante --}}
    <div class="content-header" style="margin-bottom: 30px;">
        <h1 style="font-family: var(--font-heading); color: var(--accent); font-size: 2.5rem;">
            Crear Nuevo Producto
        </h1>
        <p style="color: var(--text-muted);">Añade una nueva creación a tu catálogo.</p>
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

    {{-- Tarjeta Principal con Glassmorphism --}}
    <div class="card">
        <form action="{{ route('admin.productos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-grid">
                
                {{-- COLUMNA IZQUIERDA: Información Principal --}}
                <div class="left-column">
                    
                    {{-- Nombre --}}
                    <div class="form-group">
                        <label for="nombre">Nombre del Producto</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" 
                               placeholder="Ej. Llavero Corazón Amigurumi" value="{{ old('nombre') }}" required>
                    </div>

                    {{-- Descripción --}}
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea name="descripcion" id="descripcion" rows="6" class="form-control" 
                                  placeholder="Detalles sobre el material, tamaño, cuidados...">{{ old('descripcion') }}</textarea>
                    </div>

                    {{-- Fila doble: Precio y Stock --}}
                    <div class="row-2-cols">
                        <div class="form-group">
                            <label for="precio">Precio ($)</label>
                            <input type="number" step="0.01" name="precio" id="precio" class="form-control" 
                                   placeholder="0.00" value="{{ old('precio') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="stock">Stock Disponible</label>
                            <input type="number" name="stock" id="stock" class="form-control" 
                                   placeholder="0" value="{{ old('stock') }}" required>
                        </div>
                    </div>

                     {{-- Categoría --}}
                     <div class="form-group">
                        <label for="id_categoria">Categoría</label>
                        <select name="id_categoria" id="id_categoria" class="form-control" required>
                            <option value="" disabled selected>Selecciona una categoría...</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id_categoria }}" {{ old('id_categoria') == $categoria->id_categoria ? 'selected' : '' }}>
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
                        {{-- Área visual --}}
                        <div class="upload-area" id="uploadArea">
                            <div id="uploadPlaceholder">
                                <div class="upload-icon">🌸</div>
                                <div class="upload-text">
                                    <span>Haz clic para subir</span><br>
                                    o arrastra tu imagen aquí
                                </div>
                                <small style="color: #9ca3af; margin-top: 10px; display:block;">
                                    JPG, PNG (Max 2MB)
                                </small>
                            </div>
                            
                            {{-- Previsualización (vacía al inicio) --}}
                            <img id="imagePreview" class="image-preview" src="#" alt="Vista previa">
                        </div>

                        {{-- Input real oculto --}}
                        <input type="file" name="imagen" id="imagen" class="file-input-hidden" accept="image/*" onchange="previewImage(this)">
                    </div>
                </div>

            </div>

            {{-- Botones de Acción --}}
            <div class="form-actions" style="margin-top: 40px; border-top: 1px solid var(--border); padding-top: 20px; justify-content: flex-end;">
                <a href="{{ route('admin.productos.index') }}" class="btn btn-secondary" style="margin-right: 10px;">Cancelar</a>
                <button type="submit" class="btn btn-primary" style="padding: 12px 30px; font-size: 1rem;">
                    Guardar Producto
                </button>
            </div>
        </form>
    </div>

    {{-- Script simple para previsualizar la imagen al seleccionarla --}}
    <script>
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const placeholder = document.getElementById('uploadPlaceholder');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block'; // Mostrar imagen
                    placeholder.style.opacity = '0'; // Ocultar texto
                }
                
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.style.display = 'none';
                placeholder.style.opacity = '1';
            }
        }
    </script>
@endsection