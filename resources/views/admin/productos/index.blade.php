@extends('layouts.admin')

@section('title', 'Productos')

@section('content')
    <div class="content-header">
        <h1>Gestión de Productos</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="actions-header">
        <form action="{{ route('admin.productos.index') }}" method="GET" class="search-form">
            <div class="form-group">
                <label for="search-input" class="sr-only">Buscar productos</label>
                <input type="text" id="search-input" name="q" class="form-control" value="{{ request('q') }}" placeholder="Buscar por nombre...">
            </div>
            
            <div class="form-group">
                <label for="category-select" class="sr-only">Filtrar por categoría</label>
                <select id="category-select" name="categoria" class="form-control">
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
            
            <button type="submit" class="btn btn-primary">Buscar</button>
            
            @if(request()->has('q') || request()->has('categoria'))
                <a href="{{ route('admin.productos.index') }}" class="btn btn-secondary">Limpiar</a>
            @endif
        </form>
        
        <div class="action-links">
            <a href="{{ route('admin.productos.create') }}" class="btn btn-primary">+ Nuevo Producto</a>
        </div>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th scope="col">Imagen</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Categoría</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $producto)
                    <tr>
                        <td style="width:100px; text-align:center;">
                            @if($producto->imagen_url)
                                <img src="/{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}" class="form-image-preview" style="margin-top: 0; max-width: 80px; max-height: 80px;">
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td data-label="Nombre">{{ $producto->nombre }}</td>
                        <td data-label="Stock">{{ $producto->stock }}</td>
                        <td data-label="Categoría">{{ $producto->categoria?->nombre ?? 'Sin categoría' }}</td>
                        <td data-label="Precio">$ {{ number_format($producto->precio, 2) }}</td>
                        <td data-label="Acciones">
                            <div class="action-links">
                                <a href="{{ route('admin.productos.edit', $producto) }}" class="btn btn-secondary" aria-label="Editar {{ $producto->nombre }}">Editar</a>
                                <form action="{{ route('admin.productos.destroy', $producto) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" aria-label="Eliminar {{ $producto->nombre }}">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-row">No hay productos.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-links">
        {{ $productos->links() }}
    </div>
@endsection