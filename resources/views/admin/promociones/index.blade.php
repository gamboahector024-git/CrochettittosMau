@extends('layouts.admin')

@section('title', 'Promociones')

@section('content')
    <div class="content-header" style="margin-bottom: 2rem;">
        <h1 style="font-family: var(--font-heading); color: var(--accent); font-size: 2.5rem;">
            Gestión de Promociones
        </h1>
        <p style="color: var(--text-muted);">Crea descuentos y ofertas especiales para tus productos.</p>
    </div>

    {{-- Filtros (Estilo Glass) --}}
    <div class="card search-card" style="margin-bottom: 2rem;">
        <div class="actions-header" style="margin-bottom: 0;">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="filtro-promociones" style="display:inline-block; margin-right: 10px;">Ver:</label>
                <select id="filtro-promociones" onchange="filtrarPromociones()" class="form-control" style="width: auto; display: inline-block; min-width: 200px;">
                    <option value="todos" {{ $filtro === 'todos' ? 'selected' : '' }}>📂 Todos los productos</option>
                    <option value="con_promocion" {{ $filtro === 'con_promocion' ? 'selected' : '' }}>🏷️ Con promoción</option>
                    <option value="sin_promocion" {{ $filtro === 'sin_promocion' ? 'selected' : '' }}>❌ Sin promoción</option>
                </select>
            </div>
            
            {{-- Botón de eliminar masivo (se muestra vía JS) --}}
            <button type="button" class="btn btn-danger" id="bulk-delete-btn" style="display: none;">
                Eliminar Seleccionadas
            </button>
        </div>
    </div>

    {{-- Tabla de Promociones --}}
    <div class="card" style="padding: 0; overflow: hidden;">
        <table class="table-hover">
            <thead style="background-color: rgba(255,255,255,0.4);">
                <tr>
                    <th style="padding-left: 20px; width: 40px;"><input type="checkbox" id="select-all"></th>
                    <th scope="col">Producto</th>
                    <th scope="col">Precio Base</th>
                    <th scope="col">Promoción Actual</th>
                    <th scope="col">Estado</th>
                    <th scope="col" style="text-align: right; padding-right: 30px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $producto)
                    @php
                        $activa = $producto->promocionActiva;
                        $ultima = $producto->ultimaPromocion;
                        $now = now();
                    @endphp
                    <tr style="transition: background 0.3s;">
                        <td style="padding-left: 20px;">
                            @if($ultima)
                                <input type="checkbox" name="promocion_ids[]" value="{{ $ultima->id_promocion }}" class="promocion-checkbox">
                            @endif
                        </td>

                        {{-- Producto --}}
                        <td>
                            <div style="font-weight: 700; color: var(--text-dark);">{{ $producto->nombre }}</div>
                            <small style="color: var(--text-muted);">Stock: {{ $producto->stock }}</small>
                        </td>

                        {{-- Precio --}}
                        <td>
                            <span style="font-family: var(--font-body); font-weight: 600;">
                                ${{ number_format($producto->precio, 2) }}
                            </span>
                        </td>

                        {{-- Datos de la Promoción --}}
                        <td>
                            @if($ultima)
                                <div style="color: var(--accent); font-weight: 600;">{{ $ultima->titulo }}</div>
                                <div style="font-size: 0.85em; color: var(--text-muted);">
                                    {{ $ultima->tipo === 'porcentaje' ? $ultima->valor.'%' : '$'.$ultima->valor }} OFF
                                </div>
                                <small style="display:block; color: var(--text-muted); font-size: 0.75em;">
                                    {{ optional($ultima->fecha_inicio)->format('d/m/Y') }} - {{ optional($ultima->fecha_fin)->format('d/m/Y') }}
                                </small>
                            @else
                                <span style="color: var(--text-muted); font-style: italic;">—</span>
                            @endif
                        </td>

                        {{-- Estado (Badge) --}}
                        <td>
                            @if($activa)
                                <span class="status-badge status-activa">Activa</span>
                            @elseif($ultima)
                                @if(($ultima->fecha_inicio && $now->lt($ultima->fecha_inicio)) || ($ultima->fecha_fin && $now->gt($ultima->fecha_fin)))
                                    <span class="status-badge status-inactiva">Vencida</span>
                                @elseif(!$ultima->activa)
                                    <span class="status-badge status-inactiva">Pausada</span>
                                @endif
                            @else
                                <span class="status-badge status-inactiva" style="opacity: 0.5;">Sin promo</span>
                            @endif
                        </td>

                        {{-- Acciones --}}
                        <td style="text-align: right; padding-right: 30px;">
                            <div class="action-links" style="justify-content: flex-end;">
                                @if($ultima)
                                    {{-- Editar --}}
                                    <a href="{{ route('admin.promociones.edit', $ultima) }}" class="btn-icon" title="Editar">
                                        ✏️
                                    </a>

                                    {{-- Toggle Activar/Desactivar --}}
                                    <form action="{{ route('admin.promociones.toggle-status', $ultima) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-icon" title="{{ $ultima->activa ? 'Desactivar' : 'Activar' }}" style="color: {{ $ultima->activa ? '#f59e0b' : '#10b981' }};">
                                            {{ $ultima->activa ? '⏸️' : '▶️' }}
                                        </button>
                                    </form>

                                    {{-- Eliminar --}}
                                    <form action="{{ route('admin.promociones.destroy', $ultima) }}" method="POST" onsubmit="return confirm('¿Eliminar esta promoción?');">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="filtro" value="{{ $filtro }}">
                                        <button type="submit" class="btn-icon delete" title="Eliminar">
                                            🗑️
                                        </button>
                                    </form>
                                @else
                                    {{-- Crear Nueva --}}
                                    <a href="{{ route('admin.promociones.create', ['id_producto' => $producto->id_producto]) }}" class="btn btn-success" style="padding: 6px 12px; border-radius: 20px; font-size: 0.8rem;">
                                        + Crear
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-row">No hay productos en esta categoría.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-links" style="margin-top: 20px;">
        {{ $productos->links() }}
    </div>

    {{-- Form oculto para eliminación masiva --}}
    <form id="bulk-delete-form" action="{{ route('admin.promociones.bulk-delete') }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
        <input type="hidden" name="ids" id="bulk-delete-ids">
        <input type="hidden" name="filtro" value="{{ $filtro }}">
    </form>
@endsection

@section('scripts')
    {{-- Asegúrate de que tu JS maneje la selección de checkboxes y muestre el botón bulk-delete-btn --}}
    @vite('resources/js/admin/promociones.js')
@endsection