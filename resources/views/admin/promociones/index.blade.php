@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Promociones por producto</h1>

    <div class="mb-3" style="display:flex; gap:12px; align-items:center;">
        <a href="{{ route('admin.promociones.create') }}" class="btn btn-primary">+ Nueva oferta</a>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Producto</th>
                    <th>Stock</th>
                    <th>Precio</th>
                    <th>Oferta</th>
                    <th>Vigencia</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $producto)
                    @php($oferta = $producto->promocionActiva)
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
                        <td>$ {{ number_format($producto->precio, 2) }}</td>
                        <td>
                            @if($oferta)
                                {{ $oferta->titulo }}<br>
                                <small>
                                    {{ $oferta->tipo === 'porcentaje' ? ($oferta->valor.'%') : ('$'.$oferta->valor) }}
                                </small>
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            @if($oferta)
                                {{ $oferta->fecha_inicio }} — {{ $oferta->fecha_fin }}
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            @if($oferta)
                                {{ $oferta->activa ? 'Activa' : 'Inactiva' }}
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            @if($oferta)
                                <a href="{{ route('admin.promociones.edit', $oferta) }}">Editar oferta</a>
                                <form action="{{ route('admin.promociones.toggle-status', $oferta) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit">{{ $oferta->activa ? 'Desactivar' : 'Activar' }}</button>
                                </form>
                                <form action="{{ route('admin.promociones.destroy', $oferta) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar esta oferta?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Eliminar</button>
                                </form>
                            @else
                                {{-- Cambio aquí: antes decía "Crear oferta" --}}
                                <a href="{{ route('admin.promociones.create', ['id_producto' => $producto->id_producto]) }}">Editar oferta</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">No hay productos.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $productos->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin/promociones.js') }}"></script>
@endsection
