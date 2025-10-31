@extends('layouts.admin')

@section('title', 'Buzón de Peticiones')
@section('header', 'Buzón de Peticiones')

@section('content')
    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div>{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.peticiones.index') }}" method="GET">
        <div>
            <label for="q">Buscar</label>
            <input type="text" id="q" name="q" value="{{ request('q') }}" placeholder="Título, descripción o usuario">
        </div>
        <div>
            <label for="estado">Estado</label>
            <select id="estado" name="estado">
                <option value="">Todos</option>
                <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="en revisión" {{ request('estado') === 'en revisión' ? 'selected' : '' }}>En revisión</option>
                <option value="aceptada" {{ request('estado') === 'aceptada' ? 'selected' : '' }}>Aceptada</option>
                <option value="rechazada" {{ request('estado') === 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                <option value="completada" {{ request('estado') === 'completada' ? 'selected' : '' }}>Completada</option>
            </select>
        </div>
        <div>
            <label for="from">Desde</label>
            <input type="date" id="from" name="from" value="{{ request('from') }}">
        </div>
        <div>
            <label for="to">Hasta</label>
            <input type="date" id="to" name="to" value="{{ request('to') }}">
        </div>
        <div>
            <button type="submit" class="btn btn-primary">Filtrar</button>
            @if(request()->has('q') || request()->has('estado') || request()->has('from') || request()->has('to'))
                <a href="{{ route('admin.peticiones.index') }}" class="btn">Limpiar</a>
            @endif
        </div>
    </form>

    <form method="POST">
        @csrf
        <div>
            <label for="estado-masivo">Cambiar estado a:</label>
            <select id="estado-masivo" name="estado">
                <option value="en revisión">En revisión</option>
                <option value="aceptada">Aceptada</option>
                <option value="rechazada">Rechazada</option>
            </select>
            <button type="submit" class="btn btn-primary" formaction="{{ route('admin.peticiones.bulk-status') }}">
                Aplicar a seleccionadas
            </button>
            <button type="submit" class="btn btn-danger" formaction="{{ route('admin.peticiones.bulk-delete') }}" onclick="return confirm('¿Eliminar peticiones seleccionadas?');">
                Eliminar seleccionadas
            </button>
        </div>

        <div>
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
                        <tr>
                            <td><input type="checkbox" name="ids[]" value="{{ $peticion->id_peticion }}" class="row-checkbox"></td>
                            <td>{{ $peticion->id_peticion }}</td>
                            <td>
                                {{ $peticion->usuario->nombre }}
                                @if(!empty($peticion->usuario->email))
                                    <br><small>{{ $peticion->usuario->email }}</small>
                                @endif
                            </td>
                            <td>{{ $peticion->titulo }}</td>
                            <td>{{ ucfirst($peticion->estado) }}</td>
                            <td>{{ $peticion->created_at?->format('d/m/Y') ?? 'No especificado' }}</td>
                            <td>
                                <a href="{{ route('admin.peticiones.show', $peticion->id_peticion) }}" class="btn btn-primary">Ver/Responder</a>
                                <form action="{{ route('admin.peticiones.destroy', $peticion->id_peticion) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Eliminar esta petición?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $peticiones->links() }}
        </div>
    </form>
@endsection

@section('scripts')
<script>
document.getElementById('select-all')?.addEventListener('change', function (event) {
    document.querySelectorAll('.row-checkbox').forEach(function (checkbox) {
        checkbox.checked = event.target.checked;
    });
});
</script>
@endsection