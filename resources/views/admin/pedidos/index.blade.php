@extends('layouts.admin')

@section('title', 'Pedidos')
@section('header', 'Gestión de Pedidos')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="actions" style="margin-bottom:16px; display:flex; gap:12px; align-items:flex-end; flex-wrap:wrap;">
        <form action="{{ route('admin.pedidos.index') }}" method="GET" style="display:flex; gap:8px; align-items:flex-end; flex-wrap:wrap;">
            <div>
                <label for="q">ID o cliente</label>
                <input type="text" id="q" name="q" value="{{ request('q') }}" placeholder="ID, nombre, email">
            </div>
            <div>
                <label for="estado">Estado</label>
                @php($estados = ['pendiente','procesando','enviado','entregado','cancelado'])
                <select id="estado" name="estado">
                    <option value="">Todos</option>
                    @foreach($estados as $e)
                        <option value="{{ $e }}" {{ request('estado') === $e ? 'selected' : '' }}>{{ ucfirst($e) }}</option>
                    @endforeach
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
            <div style="display:flex; gap:8px;">
                <button type="submit">Filtrar</button>
                @if(request()->has('q') || request()->has('estado') || request()->has('from') || request()->has('to'))
                    <a href="{{ route('admin.pedidos.index') }}">Limpiar</a>
                @endif
            </div>
        </form>
    </div>

    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Estado</th>
                <th>Total</th>
                <th>Método pago</th>
                <th>Empresa envío</th>
                <th>Código rastreo</th>
                <th>Fecha envío</th>
                <th>Entrega estimada</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pedidos as $pedido)
                <tr>
                    <td>#{{ $pedido->id_pedido }}</td>
                    <td>{{ $pedido->fecha_pedido }}</td>
                    <td>
                        {{ $pedido->usuario->nombre ?? '—' }} {{ $pedido->usuario->apellido ?? '' }}<br>
                        <small>{{ $pedido->usuario->email ?? '' }}</small>
                    </td>
                    <td>{{ ucfirst($pedido->estado) }}</td>
                    <td>$ {{ number_format($pedido->total, 2) }}</td>
                    <td>{{ $pedido->metodo_pago ?? '—' }}</td>
                    <td>{{ $pedido->empresa_envio ?? '—' }}</td>
                    <td>{{ $pedido->codigo_rastreo ?? '—' }}</td>
                    <td>{{ optional($pedido->fecha_envio)->format('Y-m-d H:i') ?? '—' }}</td>
                    <td>{{ optional($pedido->fecha_entrega_estimada)->format('Y-m-d') ?? '—' }}</td>
                    <td>
                        <a href="{{ route('admin.pedidos.show', $pedido) }}">Ver</a>
                        <a href="{{ route('admin.pedidos.edit', $pedido) }}">Editar</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">No hay pedidos.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:12px;">
        {{ $pedidos->links() }}
    </div>
@endsection

