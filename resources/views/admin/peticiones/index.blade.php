{{-- resources/views/admin/peticiones/index.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container">
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
    <h1>Buzón de Peticiones</h1>
    
    <div class="mb-3">
        <form action="{{ route('admin.peticiones.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="q" class="form-label">Buscar</label>
                <input type="text" id="q" name="q" value="{{ request('q') }}" class="form-control" placeholder="Título, descripción o usuario">
            </div>
            <div class="col-md-3">
                <label for="estado" class="form-label">Estado</label>
                <select id="estado" name="estado" class="form-select">
                    <option value="">Todos</option>
                    <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="en revisión" {{ request('estado') === 'en revisión' ? 'selected' : '' }}>En revisión</option>
                    <option value="aceptada" {{ request('estado') === 'aceptada' ? 'selected' : '' }}>Aceptada</option>
                    <option value="rechazada" {{ request('estado') === 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                    <option value="completada" {{ request('estado') === 'completada' ? 'selected' : '' }}>Completada</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="from" class="form-label">Desde</label>
                <input type="date" id="from" name="from" value="{{ request('from') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="to" class="form-label">Hasta</label>
                <input type="date" id="to" name="to" value="{{ request('to') }}" class="form-control">
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                @if(request()->has('q') || request()->has('estado') || request()->has('from') || request()->has('to'))
                    <a href="{{ route('admin.peticiones.index') }}" class="btn btn-secondary">Limpiar</a>
                @endif
            </div>
        </form>
    </div>

    <form action="{{ route('admin.peticiones.bulk-status') }}" method="POST" id="bulk-status-form">
        @csrf
        
        <div class="mb-3 d-flex gap-2">
            <select name="estado" class="form-select" style="width: auto;">
                <option value="pendiente">Marcar como pendientes</option>
                <option value="en revisión">Marcar como en revisión</option>
                <option value="aceptada">Marcar como aceptadas</option>
                <option value="rechazada">Marcar como rechazadas</option>
                <option value="completada">Marcar como completadas</option>
            </select>
            <button type="submit" class="btn btn-outline-primary">Aplicar</button>
            
            <button type="button" class="btn btn-outline-danger ms-auto" 
                    onclick="handleBulkDelete()">
                Eliminar seleccionados
            </button>
        </div>

    </form>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
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
                    <tr class="@if($peticion->estado === 'pendiente') table-warning @elseif($peticion->estado === 'rechazada') table-secondary @elseif($peticion->estado === 'completada') table-success @endif">
                        <td><input type="checkbox" name="ids[]" value="{{ $peticion->id_peticion }}" class="row-checkbox"></td>
                        <td>{{ $peticion->id_peticion }}</td>
                        <td>{{ $peticion->usuario->nombre }}</td>
                        <td>{{ $peticion->titulo }}</td>
                        <td>
                            <span class="badge bg-{{
                                $peticion->estado === 'pendiente' ? 'warning' : 
                                ($peticion->estado === 'en revisión' ? 'info' : 
                                ($peticion->estado === 'aceptada' ? 'primary' : 
                                ($peticion->estado === 'rechazada' ? 'secondary' : 'success')))
                            }}">
                                {{ ucfirst($peticion->estado) }}
                            </span>
                        </td>
                        <td>{{ $peticion->created_at?->format('d/m/Y') ?? 'No especificado' }}</td>
                        <td>
                            <a href="{{ route('admin.peticiones.show', $peticion->id_peticion) }}" class="btn btn-sm btn-primary">Ver/Responder</a>
                            <form action="{{ route('admin.peticiones.toggle-status', $peticion->id_peticion) }}" method="POST" style="display: inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-secondary">
                                    {{ $peticion->estado === 'rechazada' ? 'Restaurar' : 'Rechazar' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $peticiones->links() }}
        </div>

    <form action="{{ route('admin.peticiones.bulk-delete') }}" method="POST" id="bulk-delete-form" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
</div>

<script>
    document.getElementById('select-all').addEventListener('change', function(e) {
        document.querySelectorAll('.row-checkbox').forEach(checkbox => {
            checkbox.checked = e.target.checked;
        });
    });
    
function handleBulkDelete() {
    const checked = document.querySelectorAll('.row-checkbox:checked');
    if (checked.length === 0) {
        alert('Selecciona al menos una petición');
        return;
    }

    if (confirm(`¿Eliminar ${checked.length} peticiones?`)) {
        const form = document.getElementById('bulk-delete-form');
        // Limpiar posibles inputs previos ids[]
        form.querySelectorAll('input[name="ids[]"]').forEach(n => n.remove());

        // Añadir inputs ids[] por cada fila marcada
        checked.forEach(checkbox => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = checkbox.value;
            form.appendChild(input);
        });

        form.submit();
    }
}

    document.getElementById('bulk-status-form').addEventListener('submit', function(e) {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        if (checked.length === 0) {
            e.preventDefault();
            alert('Selecciona al menos una petición');
            return;
        }
        // Limpiar ids[] previos en el formulario
        this.querySelectorAll('input[name="ids[]"]').forEach(n => n.remove());
        // Añadir ids seleccionados
        checked.forEach(checkbox => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = checkbox.value;
            this.appendChild(input);
        });
    });
</script>
@endsection