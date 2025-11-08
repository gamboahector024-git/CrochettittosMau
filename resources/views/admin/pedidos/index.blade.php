@extends('layouts.admin')

@section('title', 'Pedidos')

@section('content')
    <div class="content-header">
        <h1>Gestión de Pedidos</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card" style="margin-bottom: 25px;">
        <form action="{{ route('admin.pedidos.index') }}" method="GET" class="filter-form">
            <div class="form-group">
                <label for="q">ID o cliente</label>
                <input type="text" id="q" name="q" class="form-control" value="{{ request('q') }}" placeholder="ID, nombre, email">
            </div>
            <div class="form-group">
                <label for="estado">Estado</label>
                @php($estados = ['pendiente','procesando','enviado','entregado','cancelado'])
                <select id="estado" name="estado" class="form-control">
                    <option value="">Todos</option>
                    @foreach($estados as $e)
                        <option value="{{ $e }}" {{ request('estado') === $e ? 'selected' : '' }}>{{ ucfirst($e) }}</option>
                    @endforeach
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
                    <a href="{{ route('admin.pedidos.index') }}" class="btn btn-secondary">Limpiar</a>
                @endif
            </div>
        </form>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th>Método</th>
                    <th>Envío</th>
                    <th>Rastreo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pedidos as $pedido)
                    <tr>
                        <td data-label="ID">#{{ $pedido->id_pedido }}</td>
                        <td data-label="Fecha">{{ $pedido->fecha_pedido }}</td>
                        <td class="cliente-cell" data-label="Cliente">
                            {{ $pedido->usuario->nombre ?? '—' }} {{ $pedido->usuario->apellido ?? '' }}
                            <small>{{ $pedido->usuario->email ?? '' }}</small>
                        </td>
                        <td data-label="Estado">
                            <span class="status-badge status-{{ $pedido->estado }}">{{ ucfirst($pedido->estado) }}</span>
                        </td>
                        <td data-label="Total">$ {{ number_format($pedido->total, 2) }}</td>
                        <td data-label="Método">{{ $pedido->metodo_pago ?? '—' }}</td>
                        <td data-label="Envío">{{ $pedido->empresa_envio ?? '—' }}</td>
                        <td data-label="Rastreo">{{ $pedido->codigo_rastreo ?? '—' }}</td>
                        <td data-label="Acciones">
                            <div class="action-links">
                                {{-- Se eliminó el botón "Ver" --}}
                                <a class="btn btn-secondary" href="{{ route('admin.pedidos.edit', $pedido) }}">Editar</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="empty-row">No hay pedidos que coincidan con los filtros.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-links">
        {{ $pedidos->links() }}
    </div>
@endsection
