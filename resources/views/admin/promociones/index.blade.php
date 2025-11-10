@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Promociones</h1>
        <div>
            <button type="button" class="btn btn-danger" id="bulk-delete-btn" style="display: none;">
                Eliminar Seleccionadas
            </button>
        </div>
    </div>

    <!-- Filtros simples -->
    <div class="mb-3">
        <select class="form-select" id="filtro-promociones" onchange="filtrarPromociones()" style="max-width: 200px;">
            <option value="todos" {{ $filtro === 'todos' ? 'selected' : '' }}>Todos</option>
            <option value="con_promocion" {{ $filtro === 'con_promocion' ? 'selected' : '' }}>Con promoción</option>
            <option value="sin_promocion" {{ $filtro === 'sin_promocion' ? 'selected' : '' }}>Sin promoción</option>
        </select>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Promoción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $producto)
                    @php($activa = $producto->promocionActiva)
                    @php($ultima = $producto->ultimaPromocion)
                    <tr>
                        <td>
                            @if($ultima)
                                <input type="checkbox" name="promocion_ids[]" value="{{ $ultima->id_promocion }}" class="promocion-checkbox">
                            @endif
                        </td>
                        <td>
                            <strong>{{ $producto->nombre }}</strong>
                            <br><small class="text-muted">Stock: {{ $producto->stock }}</small>
                        </td>
                        <td>${{ number_format($producto->precio, 2) }}</td>
                        <td>
                            @if($ultima)
                                <strong>{{ $ultima->titulo }}</strong>
                                <br><small>{{ $ultima->tipo === 'porcentaje' ? $ultima->valor.'%' : '$'.$ultima->valor }} descuento</small>
                                <br><small class="text-muted">{{ optional($ultima->fecha_inicio)->format('d/m/Y') }} - {{ optional($ultima->fecha_fin)->format('d/m/Y') }}</small>
                            @else
                                <span class="text-muted">Sin promoción</span>
                            @endif
                        </td>
                        <td>
                            @php($now = now())
                            @if($activa)
                                <span class="badge bg-success">Activa</span>
                            @elseif($ultima)
                                @if(($ultima->fecha_inicio && $now->lt($ultima->fecha_inicio)) || ($ultima->fecha_fin && $now->gt($ultima->fecha_fin)))
                                    <span class="badge bg-warning text-dark">Fuera de vigencia</span>
                                @elseif(!$ultima->activa)
                                    <span class="badge bg-secondary">Inactiva</span>
                                @else
                                    <span class="badge bg-light text-dark">Sin promoción</span>
                                @endif
                            @else
                                <span class="badge bg-light text-dark">Sin promoción</span>
                            @endif
                        </td>
                        <td>
                            @if($ultima)
                                <a href="{{ route('admin.promociones.edit', $ultima) }}" class="btn btn-sm btn-primary">Editar</a>
                                
                                <form action="{{ route('admin.promociones.toggle-status', $ultima) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning">
                                        {{ $ultima->activa ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.promociones.destroy', $ultima) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar promoción {{ $ultima->titulo }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="filtro" value="{{ $filtro }}">
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>

                                @php($vigenteAhora = $ultima->fecha_inicio && $ultima->fecha_fin && $now->between($ultima->fecha_inicio->copy()->startOfDay(), $ultima->fecha_fin->copy()->endOfDay()))
                                @if(!$activa && !$vigenteAhora)
                                    <a href="{{ route('admin.promociones.create', ['id_producto' => $producto->id_producto]) }}" class="btn btn-sm btn-success">Crear</a>
                                @endif
                            @else
                                <a href="{{ route('admin.promociones.create', ['id_producto' => $producto->id_producto]) }}" class="btn btn-sm btn-success">Crear</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No hay productos.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $productos->links() }}

    <!-- Formulario para bulk delete -->
    <form id="bulk-delete-form" action="{{ route('admin.promociones.bulk-delete') }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
        <input type="hidden" name="ids" id="bulk-delete-ids">
        <input type="hidden" name="filtro" value="{{ $filtro }}">
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin/promociones.js') }}?v=1"></script>
@endsection
