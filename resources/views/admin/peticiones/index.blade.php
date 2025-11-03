@extends('layouts.admin')

@section('title', 'Buzón de Peticiones')

@section('content')
    <div class="content-header">
        <h1>Buzón de Peticiones</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card" style="margin-bottom: 25px;">
        <form action="{{ route('admin.peticiones.index') }}" method="GET" class="filter-form">
            <div class="form-group">
                <label for="q">Buscar</label>
                <input type="text" id="q" name="q" class="form-control" value="{{ request('q') }}" placeholder="Título, descripción o usuario">
            </div>
            <div class="form-group">
                <label for="estado">Estado</label>
                <select id="estado" name="estado" class="form-control">
                    <option value="">Todos</option>
                    <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="en revisión" {{ request('estado') === 'en revisión' ? 'selected' : '' }}>En revisión</option>
                    <option value="aceptada" {{ request('estado') === 'aceptada' ? 'selected' : '' }}>Aceptada</option>
                    <option value="rechazada" {{ request('estado') === 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                    <option value="completada" {{ request('estado') === 'completada' ? 'selected' : '' }}>Completada</option>
                </select>
            </div>
            <div class="form-group">
                <label for="from">Desde</label>
                <input type="date" id="from" name="from" class="form-control" value="{{ request('from') }}">
            </div>
            <div class="form-group">
                <label for="to">Hasta</label>
                <input type="date" id="to" name="to" class="form-control" value="{{ request('to') }}">
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                @if(request()->has('q') || request()->has('estado') || request()->has('from') || request()->has('to'))
                    <a href="{{ route('admin.peticiones.index') }}" class="btn btn-secondary">Limpiar</a>
                @endif
            </div>
        </form>
    </div>

    <div class="card">
        <form method="POST">
            @csrf
            <div class="actions-header" style="padding: 20px 20px 0 20px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="estado-masivo" style="margin-right: 10px;">Acciones masivas:</label>
                    <select id="estado-masivo" name="estado" class="form-control" style="width: auto; display: inline-block;">
                        <option value="en revisión">Mover a "En revisión"</option>
                        <option value="aceptada">Mover a "Aceptada"</option>
                        <option value="rechazada">Mover a "Rechazada"</option>
                    </select>
                </div>
                <div class="action-links">
                    <button type="submit" class="btn btn-primary" formaction="{{ route('admin.peticiones.bulk-status') }}">
                        Aplicar
                    </button>
                    <button type="submit" class="btn btn-danger" formaction="{{ route('admin.peticiones.bulk-delete') }}" onclick="return confirm('¿Eliminar peticiones seleccionadas?');">
                        Eliminar
                    </button>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th style="width: 1%;"><input type="checkbox" id="select-all"></th>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Título</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peticiones as $peticion)
                        <tr>
                            <td><input type="checkbox" name="ids[]" value="{{ $peticion->id_peticion }}" class="row-checkbox"></td>
                            <td data-label="ID">{{ $peticion->id_peticion }}</td>
                            <td data-label="Usuario" class="cliente-cell">
                                {{ $peticion->usuario->nombre }}
                                <small>{{ $peticion->usuario->email }}</small>
                            </td>
                            <td data-label="Título">{{ $peticion->titulo }}</td>
                            <td data-label="Estado">
                                <span class="status-badge status-{{ str_replace(' ', '-', $peticion->estado) }}">{{ ucfirst($peticion->estado) }}</span>
                            </td>
                            <td data-label="Fecha">{{ $peticion->created_at?->format('d/m/Y') ?? 'No especificado' }}</td>
                            <td data-label="Acciones">
                                <div class="action-links">
                                    <a href="{{ route('admin.peticiones.show', $peticion->id_peticion) }}" class="btn btn-primary">Ver</a>
                                    <form action="{{ route('admin.peticiones.destroy', $peticion->id_peticion) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Eliminar esta petición?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
        
        <div class="pagination-links" style="padding: 20px;">
            {{ $peticiones->links() }}
        </div>
    </div>
@endsection

@section('scripts')
<script>
// Script para seleccionar/deseleccionar todas las casillas
document.getElementById('select-all')?.addEventListener('change', function (event) {
    document.querySelectorAll('.row-checkbox').forEach(function (checkbox) {
        checkbox.checked = event.target.checked;
    });
});
</script>
@endsection