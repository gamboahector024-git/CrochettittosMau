@extends('layouts.cliente')

@section('content')
<div class="container my-5">
    <h1 class="mb-4 text-center">Mis Pedidos</h1>

    @if($pedidos->isEmpty())
        <div class="alert alert-info text-center" role="alert">
            Aún no has realizado ningún pedido.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col"># Pedido</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Estado</th>
                        <th scope="col" class="text-end">Total</th>
                        <th scope="col" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedidos as $pedido)
                        <tr>
                            <th scope="row">{{ $pedido->numero_pedido_cliente }}</th>
                            <td>{{ $pedido->fecha_pedido->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-primary">{{ ucfirst($pedido->estado) }}</span>
                            </td>
                            <td class="text-end">${{ number_format($pedido->total, 2) }}</td>
                            <td class="text-center">
                                <a href="{{ route('cliente.pedidos.show', $pedido) }}" class="btn btn-sm btn-outline-primary">
                                    Ver Detalles
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $pedidos->links() }}
        </div>
    @endif
</div>
@endsection
