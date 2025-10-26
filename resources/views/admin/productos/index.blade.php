@extends('layouts.admin')

@section('title', 'Productos')
@section('header', 'Gestión de Productos')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="actions" style="margin-bottom:16px; display:flex; gap:12px; align-items:center;">
        <a href="{{ route('admin.productos.create') }}" class="btn btn-primary">+ Nuevo Producto</a>
        <form action="{{ route('admin.productos.index') }}" method="GET" style="display:flex; gap:8px; align-items:center;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por nombre o descripción">
            <select name="categoria">
                <option value="">Todas las categorías</option>
                @foreach(['llaveros' => 'Llaveros','flores' => 'Flores','personalizados' => 'Personalizados'] as $val => $label)
                    <option value="{{ $val }}" {{ request('categoria') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit">Buscar</button>
            @if(request()->has('q') || request()->has('categoria'))
                <a href="{{ route('admin.productos.index') }}">Limpiar</a>
            @endif
        </form>
    </div>

    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Stock</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($productos as $producto)
                <tr>
                    <td style="width:100px; text-align:center;">
                        @if($producto->imagen_url)
                            <img src="/{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}" style="max-width:90px; max-height:90px; object-fit:cover;">
                        @else
                            —
                        @endif
                    </td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->stock }}</td>
                    <td>{{ ucfirst($producto->categoria) }}</td>
                    <td>$ {{ number_format($producto->precio, 2) }}</td>
                    <td>
                        <a href="{{ route('admin.productos.edit', $producto) }}">Editar</a>
                        <form action="{{ route('admin.productos.destroy', $producto) }}" method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar este producto?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Eliminar</button>
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

    <div style="margin-top:12px;">
        {{ $productos->links() }}
    </div>
@endsection
