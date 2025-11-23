@extends('layouts.admin')

@section('title', 'Productos')

@section('content')
    {{-- Encabezado con Tipografía Playfair --}}
    <div class="content-header" style="margin-bottom: 2rem;">
        <h1 style="font-family: var(--font-heading); color: var(--accent); font-size: 2.5rem;">
            Gestión de Productos
        </h1>
        <p style="color: var(--text-muted);">Administra tu inventario.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tarjeta de Filtros con Efecto Glass --}}
    <div class="card search-card" style="margin-bottom: 2rem;">
        <div class="actions-header">
            <form action="{{ route('admin.productos.index') }}" method="GET" class="search-form">
                
                {{-- Buscador con icono integrado visualmente --}}
                <div class="form-group" style="position: relative;">
                    <input type="text" id="search-input" name="q" class="form-control" 
                           value="{{ request('q') }}" placeholder="🔍 Buscar nombre..."
                           style="padding-left: 35px; border-radius: 20px;">
                </div>
                
                {{-- Selector de Categoría --}}
                <div class="form-group">
                    <select id="category-select" name="categoria" class="form-control" style="border-radius: 20px;">
                        <option value="">Todas las categorías</option>
                        @isset($categorias)
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id_categoria }}" {{ (string)request('categoria') === (string)$cat->id_categoria ? 'selected' : '' }}>
                                    {{ $cat->nombre }}
                                </option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary" style="border-radius: 20px;">Filtrar</button>
                
                @if(request()->has('q') || request()->has('categoria'))
                    <a href="{{ route('admin.productos.index') }}" class="btn btn-secondary" style="border-radius: 20px;">Limpiar</a>
                @endif
            </form>
            
            <div class="action-links">
                <a href="{{ route('admin.productos.create') }}" class="btn btn-primary shadow-hover">
                    <span style="font-size: 1.2em; vertical-align: middle;">+</span> Nuevo Producto
                </a>
            </div>
        </div>
    </div>

    {{-- Tabla de Productos dentro de Tarjeta Glassmorphism --}}
    <div class="card" style="padding: 0; overflow: hidden;">
        <table class="table-hover">
            <thead style="background-color: rgba(255,255,255,0.4);">
                <tr>
                    <th scope="col" style="padding-left: 30px;">Producto</th> {{-- Columna combinada Imagen + Nombre --}}
                    <th scope="col">Precio</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Categoría</th>
                    <th scope="col">Estado</th> {{-- Asumimos estado por stock --}}
                    <th scope="col" style="text-align: right; padding-right: 30px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $producto)
                    <tr style="transition: background 0.3s;">
                        {{-- Imagen y Nombre juntos para mejor diseño --}}
                        <td style="padding-left: 30px;">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <div style="width: 60px; height: 60px; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                                    @if($producto->imagen_url)
                                        <img src="/{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <div style="width: 100%; height: 100%; background: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #ccc;">📷</div>
                                    @endif
                                </div>
                                <div>
                                    <div style="font-weight: 700; color: var(--text-dark); font-size: 1rem;">{{ $producto->nombre }}</div>
                                    <small style="color: var(--text-muted);">ID: #{{ $producto->id }}</small>
                                </div>
                            </div>
                        </td>

                        <td style="font-weight: 600; color: var(--text-dark); font-family: var(--font-body);">
                            $ {{ number_format($producto->precio, 2) }}
                        </td>

                        <td>
                            @if($producto->stock <= 5)
                                <span style="color: #e11d48; font-weight: bold;">{{ $producto->stock }} (Bajo)</span>
                            @else
                                <span style="color: var(--text-muted);">{{ $producto->stock }} u.</span>
                            @endif
                        </td>

                        <td>
                            {{-- Lógica de colores para Categorías --}}
                            @php
                                $catName = $producto->categoria?->nombre ?? 'Sin categoría';
                                $badgeClass = match($catName) {
                                    'Flores' => 'badge-green',
                                    'Llaveros' => 'badge-yellow',
                                    'Amigurumis' => 'badge-pink',
                                    default => 'badge-gray',
                                };
                            @endphp
                            <span class="status-badge {{ $badgeClass }}">
                                {{ $catName }}
                            </span>
                        </td>

                        <td>
                            <span class="status-badge {{ $producto->stock > 0 ? 'status-activa' : 'status-inactiva' }}">
                                {{ $producto->stock > 0 ? 'Activo' : 'Agotado' }}
                            </span>
                        </td>

                        <td style="text-align: right; padding-right: 30px;">
                            <div class="action-links" style="justify-content: flex-end;">
                                <a href="{{ route('admin.productos.edit', $producto) }}" class="btn-icon" title="Editar">
                                    ✏️
                                </a>
                                <form action="{{ route('admin.productos.destroy', $producto) }}" method="POST" onsubmit="return confirm('¿Eliminar producto?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon delete" title="Eliminar">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-row">
                            <div style="display: flex; flex-direction: column; align-items: center; padding: 40px;">
                                <span style="font-size: 3rem; margin-bottom: 10px;">🧶</span>
                                <p>No hay productos registrados aún.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-links" style="margin-top: 20px;">
        {{ $productos->links() }}
    </div>
@endsection