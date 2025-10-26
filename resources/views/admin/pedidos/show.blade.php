@extends('layouts.admin')

@section('title', 'Detalle de Pedido')
@section('header', 'Pedido #'.$pedido->id_pedido)

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div style="display:grid; gap:12px; margin-bottom:16px;">
        <div>
            <strong>Cliente:</strong>
            {{ $pedido->usuario->nombre ?? '—' }} {{ $pedido->usuario->apellido ?? '' }}
            @if(!empty($pedido->usuario->email))
                <small>({{ $pedido->usuario->email }})</small>
            @endif
        </div>
        <div>
            <strong>Fecha:</strong> {{ $pedido->fecha_pedido }}
        </div>
        <div>
            <strong>Dirección de envío:</strong><br>
            {{ $pedido->direccion_envio }}
        </div>
        <div>
            <form action="{{ route('admin.pedidos.update', $pedido) }}" method="POST" style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                @csrf
                @method('PUT')
                <label for="estado"><strong>Estado:</strong></label>
                @php($estados = ['pendiente','procesando','enviado','entregado','cancelado'])
                <select id="estado" name="estado">
                    @foreach($estados as $e)
                        <option value="{{ $e }}" {{ $pedido->estado === $e ? 'selected' : '' }}>{{ ucfirst($e) }}</option>
                    @endforeach
                </select>
                <button type="submit">Actualizar</button>
            </form>
        </div>
    </div>

    <h3>Productos</h3>
    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pedido->detalles as $detalle)
                <tr>
                    <td>{{ $detalle->producto->nombre ?? '—' }}</td>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>$ {{ number_format($detalle->precio_unitario, 2) }}</td>
                    <td>$ {{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center;">Sin productos en este pedido.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:12px; text-align:right;">
        <strong>Total:</strong> $ {{ number_format($pedido->total, 2) }}
    </div>
@endsection

