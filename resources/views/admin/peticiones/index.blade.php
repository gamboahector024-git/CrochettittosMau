@extends('layouts.admin')

@section('title', 'Buzón de Peticiones')

@section('content')
    <div class="content-header" style="margin-bottom: 2rem;">
        <h1 style="font-family: var(--font-heading); color: var(--accent); font-size: 2.5rem;">
            Buzón de Peticiones
        </h1>
        <p style="color: var(--text-muted);">Gestiona las solicitudes personalizadas de tus clientes.</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- 1. Tarjeta de Filtros --}}
    <div class="card search-card" style="margin-bottom: 2rem;">
        <form action="{{ route('admin.peticiones.index') }}" method="GET" class="filter-form">
            
            <div class="form-group" style="flex-grow: 2;">
                <input type="text" name="q" class="form-control" value="{{ request('q') }}" 
                       placeholder="🔍 Buscar por título, cliente..." style="border-radius: 20px;">
            </div>

            <div class="form-group">
                <select name="estado" class="form-control" style="border-radius: 20px;">
                    <option value="">📂 Todos los estados</option>
                    @foreach(['pendiente', 'en revisión', 'aceptada', 'rechazada', 'completada'] as $est)
                        <option value="{{ $est }}" {{ request('estado') === $est ? 'selected' : '' }}>
                            {{ ucfirst($est) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="display:flex; align-items:center; gap:10px;">
                <input type="date" name="from" class="form-control" value="{{ request('from') }}" style="border-radius: 20px;">
                <span style="color:var(--text-muted)">a</span>
                <input type="date" name="to" class="form-control" value="{{ request('to') }}" style="border-radius: 20px;">
            </div>

            <button type="submit" class="btn btn-primary" style="border-radius: 20px;">Filtrar</button>
            
            @if(request()->anyFilled(['q', 'estado', 'from', 'to']))
                <a href="{{ route('admin.peticiones.index') }}" class="btn btn-secondary" style="border-radius: 20px;">Limpiar</a>
            @endif
        </form>
    </div>

    {{-- 2. Tabla de Peticiones con Acciones Masivas --}}
    <div class="card" style="padding: 0; overflow: hidden;">
        <form method="POST" action="{{ route('admin.peticiones.bulk-status') }}" id="bulk-action-form">
            @csrf
            
            {{-- Barra de herramientas de tabla (Toolbar) --}}
            <div style="padding: 15px 20px; background: rgba(255,255,255,0.3); border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
                <div style="font-size: 0.9rem; color: var(--text-muted); font-weight: 600;">
                    Selecciona para acciones masivas:
                </div>
                <div style="display: flex; gap: 10px; align-items: center;">
                    <select name="estado" class="form-control" style="padding: 6px 12px; height: auto; width: auto; font-size: 0.9rem;">
                        <option value="en revisión">Mover a "En revisión"</option>
                        <option value="aceptada">Mover a "Aceptada"</option>
                        <option value="rechazada">Mover a "Rechazada"</option>
                    </select>
                    <button type="submit" class="btn btn-primary" style="padding: 6px 15px; font-size: 0.9rem;">Aplicar</button>
                    <button type="submit" class="btn btn-danger" formaction="{{ route('admin.peticiones.bulk-delete') }}" onclick="return confirm('¿Eliminar seleccionados?');" style="padding: 6px 15px; font-size: 0.9rem;">
                        🗑️
                    </button>
                </div>
            </div>

            <table class="table-hover">
                <thead style="background-color: rgba(255,255,255,0.4);">
                    <tr>
                        <th style="padding-left: 20px; width: 40px;"><input type="checkbox" id="select-all"></th>
                        <th scope="col">Solicitud</th>
                        <th scope="col">Cliente</th>
                        <th scope="col">Estado</th>
                        <th scope="col" style="text-align: right; padding-right: 30px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peticiones as $peticion)
                        <tr style="transition: background 0.3s;">
                            <td style="padding-left: 20px;">
                                <input type="checkbox" name="ids[]" value="{{ $peticion->id_peticion }}" class="row-checkbox">
                            </td>
                            
                            {{-- ID, Título y Fecha --}}
                            <td>
                                <div style="font-weight: 700; color: var(--text-dark);">
                                    {{ Str::limit($peticion->titulo, 30) }}
                                </div>
                                <div style="font-size: 0.85em; color: var(--text-muted); margin-top: 4px;">
                                    <span style="color: var(--accent);">#{{ $peticion->id_peticion }}</span> • {{ $peticion->created_at?->format('d M, Y') }}
                                </div>
                            </td>

                            {{-- Cliente --}}
                            <td>
                                <div style="font-weight: 600;">{{ $peticion->usuario->nombre }}</div>
                                <small style="color: var(--text-muted);">{{ $peticion->usuario->email }}</small>
                            </td>

                            {{-- Estado --}}
                            <td>
                                @php
                                    $slug = Str::slug($peticion->estado);
                                @endphp
                                <span class="status-badge status-{{ $slug }}">
                                    {{ ucfirst($peticion->estado) }}
                                </span>
                            </td>

                            {{-- Botón Ver --}}
                            <td style="text-align: right; padding-right: 30px;">
                                <a href="{{ route('admin.peticiones.show', $peticion->id_peticion) }}" class="btn-icon" title="Ver Detalles">
                                    👁️
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-row">No hay peticiones en el buzón.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </form>
    </div>

    <div class="pagination-links" style="margin-top: 20px;">
        {{ $peticiones->links() }}
    </div>

    {{-- Script simple para "Seleccionar todos" --}}
    <script>
        document.getElementById('select-all').addEventListener('change', function() {
            var checkboxes = document.querySelectorAll('.row-checkbox');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        });
    </script>
@endsection