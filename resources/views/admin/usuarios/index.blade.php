@extends('layouts.admin')

@section('title', 'Usuarios')

@section('content')
    {{-- Encabezado Elegante --}}
    <div class="content-header" style="margin-bottom: 2rem;">
        <h1 style="font-family: var(--font-heading); color: var(--accent); font-size: 2.5rem;">
            Gestión de Usuarios
        </h1>
        <p style="color: var(--text-muted);">Administra los accesos y clientes de la tienda.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Barra de Herramientas (Filtros y Botón) --}}
    <div class="card search-card" style="margin-bottom: 2rem;">
        <div class="actions-header">
            <form action="{{ route('admin.usuarios.index') }}" method="GET" class="search-form">
                
                {{-- Buscador --}}
                <div class="form-group" style="position: relative;">
                    <input type="text" name="q" value="{{ request('q') }}" 
                           class="form-control" placeholder="🔍 Buscar usuario..."
                           style="padding-left: 35px; border-radius: 20px;">
                </div>
                
                {{-- Filtro de Rol --}}
                <div class="form-group">
                    <select name="rol" class="form-control" style="border-radius: 20px;">
                        <option value="">Todos los roles</option>
                        <option value="cliente" {{ request('rol') === 'cliente' ? 'selected' : '' }}>Cliente</option>
                        <option value="admin" {{ request('rol') === 'admin' ? 'selected' : '' }}>Administrador</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary" style="border-radius: 20px;">Filtrar</button>
                
                @if(request()->has('q') || request()->has('rol'))
                    <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary" style="border-radius: 20px;">Limpiar</a>
                @endif
            </form>

            <div class="action-links">
                <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary shadow-hover">
                    <span style="font-size: 1.2em; vertical-align: middle;">+</span> Nuevo Usuario
                </a>
            </div>
        </div>
    </div>

    {{-- Tabla de Usuarios con Glassmorphism --}}
    <div class="card" style="padding: 0; overflow: hidden;">
        <table class="table-hover">
            <thead style="background-color: rgba(255,255,255,0.4);">
                <tr>
                    <th scope="col" style="padding-left: 30px;">Usuario</th> {{-- Columna combinada --}}
                    <th scope="col">Rol</th>
                    <th scope="col">Teléfono</th>
                    <th scope="col">Registrado</th>
                    <th scope="col" style="text-align: right; padding-right: 30px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $usuario)
                    <tr style="transition: background 0.3s;">
                        {{-- Avatar + Nombre + Email --}}
                        <td style="padding-left: 30px;">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                {{-- Avatar generado con CSS --}}
                                <div class="user-avatar">
                                    {{ strtoupper(substr($usuario->nombre, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight: 700; color: var(--text-dark); font-size: 1rem;">
                                        {{ $usuario->nombre }} {{ $usuario->apellido }}
                                    </div>
                                    <small style="color: var(--text-muted); display:block;">{{ $usuario->email }}</small>
                                </div>
                            </div>
                        </td>

                        {{-- Badge de Rol --}}
                        <td>
                            @php
                                $rolClass = match(strtolower($usuario->rol)) {
                                    'admin', 'administrador' => 'badge-purple',
                                    'cliente' => 'badge-green',
                                    default => 'badge-gray',
                                };
                            @endphp
                            <span class="status-badge {{ $rolClass }}">
                                {{ ucfirst($usuario->rol) }}
                            </span>
                        </td>

                        <td style="color: var(--text-muted);">
                            {{ $usuario->telefono ?? '—' }}
                        </td>

                        <td style="font-size: 0.9rem; color: var(--text-muted);">
                            {{-- Formateo de fecha más limpio (requiere que fecha_registro sea timestamp o string fecha) --}}
                            📅 {{ date('d M, Y', strtotime($usuario->fecha_registro)) }}
                        </td>

                        <td style="text-align: right; padding-right: 30px;">
                            <div class="action-links" style="justify-content: flex-end;">
                                <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="btn-icon" title="Editar">
                                    ✏️
                                </a>
                                <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST" onsubmit="return confirm('¿Eliminar a este usuario permanentemente?');" style="display:inline;">
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
                        <td colspan="5" class="empty-row">
                            <div style="padding: 40px; text-align: center;">
                                <span style="font-size: 3rem;">👥</span>
                                <p>No se encontraron usuarios.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-links" style="margin-top: 20px;">
        {{ $usuarios->links() }}
    </div>
@endsection