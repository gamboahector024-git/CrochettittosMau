@extends('layouts.admin')

@section('title', 'Productos')
@section('header', 'Gestión de Productos')

@section('content')
    @if(session('success'))
        <div role="alert" class="alert alert-success">{{ session('success') }}</div>
    @endif

    <section class="actions" style="margin-bottom:16px; display:flex; gap:12px; align-items:center;">
        <a href="{{ route('admin.productos.create') }}" class="btn btn-primary">+ Nuevo Producto</a>
        <form action="{{ route('admin.productos.index') }}" method="GET" style="display:flex; gap:8px; align-items:center;">
            <label for="search-input" style="display:none;">Buscar productos</label>
            <input type="text" id="search-input" name="q" value="{{ request('q') }}" placeholder="Buscar por nombre o descripción" aria-label="Buscar productos">
            
            <label for="category-select" style="display:none;">Filtrar por categoría</label>
            <select id="category-select" name="categoria" aria-label="Seleccionar categoría">
                <option value="">Todas las categorías</option>
                @isset($categorias)
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id_categoria }}" {{ (string)request('categoria') === (string)$cat->id_categoria ? 'selected' : '' }}>
                            {{ $cat->nombre }}
                        </option>
                    @endforeach
                @endisset
            </select>
            
            <button type="submit">Buscar</button>
            
            @if(request()->has('q') || request()->has('categoria'))
                <a href="{{ route('admin.productos.index') }}" aria-label="Limpiar filtros de búsqueda">Limpiar</a>
            @endif
        </form>
    </section>

    <div role="region" aria-labelledby="productos-table-caption" tabindex="0">
        <table border="1" cellpadding="8" cellspacing="0" width="100%">
            <caption id="productos-table-caption" style="caption-side: top; text-align: left; font-weight: bold; margin-bottom: 8px;">
                Lista de productos
            </caption>
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
                                <img src="/{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}" style="max-width:90px; max-height:90px; object-fit:cover;" loading="lazy">
                            @else
                                <span aria-hidden="true">—</span>
                                <span class="sr-only">Sin imagen</span>
                            @endif
                        </td>
                        <td>{{ $producto->nombre }}</td>
                        <td>{{ $producto->stock }}</td>
                        <td>{{ $producto->categoria?->nombre ?? 'Sin categoría' }}</td>
                        <td>$ {{ number_format($producto->precio, 2) }}</td>
                        <td>
                            <a href="{{ route('admin.productos.edit', $producto) }}" aria-label="Editar {{ $producto->nombre }}">Editar</a>
                            <form action="{{ route('admin.productos.destroy', $producto) }}" method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar este producto?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" aria-label="Eliminar {{ $producto->nombre }}">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;">No hay productos.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <nav style="margin-top:12px;" aria-label="Navegación de páginas de productos">
        {{ $productos->links() }}
    </nav>
@endsection