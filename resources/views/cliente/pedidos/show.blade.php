@extends('layouts.cliente')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Detalle del Pedido #{{ $pedido->id_pedido }}</h1>
        <a href="{{ route('cliente.pedidos.index') }}" class="btn btn-outline-secondary">
            &larr; Volver a Mis Pedidos
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-4">
                    <strong>Fecha del Pedido:</strong> {{ $pedido->fecha_pedido->format('d/m/Y') }}
                </div>
                <div class="col-md-4">
                    <strong>Estado:</strong> <span class="badge bg-primary">{{ ucfirst($pedido->estado) }}</span>
                </div>
                <div class="col-md-4 text-md-end">
                    <strong>Total:</strong> ${{ number_format($pedido->total, 2) }}
                </div>
            </div>
        </div>
        <div class="card-body">
            <h5 class="card-title mb-3">Productos</h5>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Producto</th>
                            <th scope="col" class="text-center">Cantidad</th>
                            <th scope="col" class="text-end">Precio Unitario</th>
                            <th scope="col" class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedido->detalles as $detalle)
                            <tr>
                                <td>{{ $detalle->producto->nombre }}</td>
                                <td class="text-center">{{ $detalle->cantidad }}</td>
                                <td class="text-end">${{ number_format($detalle->precio_unitario, 2) }}</td>
                                <td class="text-end">${{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <hr>

            <div class="row mt-4">
                <div class="col-md-6">
                    <h5 class="card-title mb-3">Dirección de Envío</h5>
                    <address>
                        {{ $pedido->calle }}<br>
                        {{ $pedido->colonia }}<br>
                        {{ $pedido->municipio_ciudad }}, {{ $pedido->codigo_postal }}<br>
                        {{ $pedido->estado_direccion }}
                    </address>
                </div>
                <div class="col-md-6">
                    <h5 class="card-title mb-3">Información de Pago y Envío</h5>
                    <p><strong>Método de pago:</strong> {{ $pedido->metodo_pago ?? 'No especificado' }}</p>
                    @if($pedido->empresa_envio)
                        <p><strong>Empresa de envío:</strong> {{ $pedido->empresa_envio }}</p>
                        <p><strong>Código de rastreo:</strong> {{ $pedido->codigo_rastreo ?? 'No disponible' }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
