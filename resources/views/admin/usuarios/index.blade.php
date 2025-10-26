@extends('layouts.admin')

@section('title', 'Usuarios')
@section('header', 'Gestión de Usuarios')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="actions" style="margin-bottom:16px; display:flex; gap:12px; align-items:center;">
        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">+ Nuevo Usuario</a>
        <form action="{{ route('admin.usuarios.index') }}" method="GET" style="display:flex; gap:8px; align-items:center;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por nombre, email o teléfono">
            <select name="rol">
                <option value="">Todos</option>
                <option value="cliente" {{ request('rol') === 'cliente' ? 'selected' : '' }}>Cliente</option>
                <option value="admin" {{ request('rol') === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            <button type="submit">Buscar</button>
            @if(request()->has('q') || request()->has('rol'))
                <a href="{{ route('admin.usuarios.index') }}">Limpiar</a>
            @endif
        </form>
    </div>

    <table border="1" cellpadding="8" cellspacing="0" width="100%">
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
                    <td>
                        <a href="{{ route('admin.usuarios.edit', $usuario) }}">Editar</a>
                        <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar este usuario?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center;">No hay usuarios.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:12px;">
        {{ $usuarios->links() }}
    </div>
@endsection
