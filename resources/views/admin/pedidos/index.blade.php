@extends('layouts.admin')

@section('title', 'Pedidos')

@section('content')
    <div class="content-header" style="margin-bottom: 2rem;">
        <h1 style="font-family: var(--font-heading); color: var(--accent); font-size: 2.5rem;">
            Gestión de Pedidos
        </h1>
        <p style="color: var(--text-muted);">Monitorea y actualiza el estado de las compras.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Filtros Glassmorphism --}}
    <div class="card search-card" style="margin-bottom: 2rem;">
        <form action="{{ route('admin.pedidos.index') }}" method="GET" class="filter-form">
            
            <div class="form-group">
                <input type="text" name="q" class="form-control" value="{{ request('q') }}" 
                       placeholder="🔍 ID, Cliente o Email..." style="border-radius: 20px;">
            </div>

            <div class="form-group">
                <select name="estado" class="form-control" style="border-radius: 20px;">
                    <option value="">📦 Todos los estados</option>
                    @foreach(['pendiente','procesando','enviado','entregado','cancelado'] as $e)
                        <option value="{{ $e }}" {{ request('estado') === $e ? 'selected' : '' }}>
                            {{ ucfirst($e) }}
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
                <a href="{{ route('admin.pedidos.index') }}" class="btn btn-secondary" style="border-radius: 20px;">Limpiar</a>
            @endif
        </form>
    </div>

    {{-- Tabla de Pedidos --}}
    <div class="card" style="padding: 0; overflow: hidden;">
        <table class="table-hover">
            <thead style="background-color: rgba(255,255,255,0.4);">
                <tr>
                    <th scope="col" style="padding-left: 30px;">Pedido</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Pago y Total</th>
                    <th scope="col">Envío</th>
                    <th scope="col" style="text-align: right; padding-right: 30px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pedidos as $pedido)
                    <tr style="transition: background 0.3s;">
                        {{-- ID y Fecha juntos --}}
                        <td style="padding-left: 30px;">
                            <span style="font-weight: 700; color: var(--accent); font-size: 1.1em;">#{{ $pedido->id_pedido }}</span>
                            <div style="font-size: 0.85em; color: var(--text-muted); margin-top: 4px;">
                                {{ date('d M Y', strtotime($pedido->fecha_pedido)) }}<br>
                                {{ date('H:i', strtotime($pedido->fecha_pedido)) }}
                            </div>
                        </td>

                        {{-- Cliente --}}
                        <td>
                            <div style="font-weight: 600; color: var(--text-dark);">
                                {{ $pedido->usuario->nombre ?? 'Invitado' }} {{ $pedido->usuario->apellido ?? '' }}
                            </div>
                            <small style="color: var(--text-muted);">{{ $pedido->usuario->email ?? '' }}</small>
                        </td>

                        {{-- Badge Estado --}}
                        <td>
                            <span class="status-badge status-{{ $pedido->estado }}">
                                {{ ucfirst($pedido->estado) }}
                            </span>
                        </td>

                        {{-- Total y Método --}}
                        <td>
                            <div style="font-weight: 700; color: var(--text-dark);">$ {{ number_format($pedido->total, 2) }}</div>
                            <small style="color: var(--text-muted); text-transform: uppercase; font-size: 0.75em;">
                                {{ $pedido->metodo_pago }}
                            </small>
                        </td>

                        {{-- Info de Envío --}}
                        <td>
                            @if($pedido->codigo_rastreo)
                                <div style="font-size: 0.9em; font-weight: 500;">
                                    🚚 {{ $pedido->empresa_envio }}
                                </div>
                                <code style="background: rgba(0,0,0,0.05); padding: 2px 6px; border-radius: 4px; font-size: 0.8em;">
                                    {{ $pedido->codigo_rastreo }}
                                </code>
                            @else
                                <span style="color: var(--text-muted); font-style: italic;">Pendiente</span>
                            @endif
                        </td>

                        {{-- Acciones --}}
                        <td style="text-align: right; padding-right: 30px;">
                            <a href="{{ route('admin.pedidos.edit', $pedido) }}" class="btn-icon" title="Gestionar Pedido">
                                👁️
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-row">No hay pedidos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-links" style="margin-top: 20px;">
        {{ $pedidos->links() }}
    </div>
@endsection