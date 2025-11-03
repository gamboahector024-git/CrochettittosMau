@extends('layouts.admin')

@section('title', 'Usuarios')
@section('header', 'Gestión de Usuarios')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        {{-- ¡Asegúrate de tener un estilo .alert-danger en tu admin_style.css! --}}
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Contenedor de acciones y búsqueda (envuelto en una tarjeta) --}}
    <div class="card actions-header">
        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">+ Nuevo Usuario</a>
        
        <form action="{{ route('admin.usuarios.index') }}" method="GET" class="search-form">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por nombre, email..." class="form-control">
            
            <select name="rol" class="form-control">
                <option value="">Todos los roles</option>
                <option value="cliente" {{ request('rol') === 'cliente' ? 'selected' : '' }}>Cliente</option>
                <option value="admin" {{ request('rol') === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            
            <button type="submit" class="btn btn-primary">Buscar</button>
            
            @if(request()->has('q') || request()->has('rol'))
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">Limpiar</a>
            @endif
        </form>
    </div>

    {{-- Tabla de usuarios (envuelta en una tarjeta) --}}
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Teléfono</th>
                    <th>Registrado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->id_usuario }}</td>
                        <td>{{ $usuario->nombre }}</td>
                        <td>{{ $usuario->apellido }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ ucfirst($usuario->rol) }}</td>
                        <td>{{ $usuario->telefono ?? '—' }}</td>
                        <td>{{ $usuario->fecha_registro }}</td>
                        <td class="action-links">
                            <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="btn btn-secondary">Editar</a>
                            <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST" onsubmit="return confirm('¿Eliminar este usuario?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty-row">No hay usuarios.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-links">
        {{ $usuarios->links() }}
    </div>
@endsection