@extends('layouts.cliente')

@section('title', 'Mis Pedidos - Crochettittos')

@section('content')
<div class="container">
    {{-- 1. Usamos el contenedor "glass" --}}
    <div class="profile-container">

        {{-- 2. Usamos el título estilizado --}}
        <h2 class="profile-title">Mis Pedidos</h2>

        @if($pedidos->isEmpty())
            <div class="alert alert-info text-center" role="alert">
                Aún no has realizado ningún pedido.
            </div>
        @else
            {{-- 3. Reemplazamos tu <table> por la lista de tarjetas --}}
            <div class="orders-list">
                
                @foreach($pedidos as $pedido)
                    <div class="order-card">
                        <div class="order-header">
                            {{-- 4. Usamos tus nuevas variables de la DB --}}
                            <span class="order-id">Pedido #{{ $pedido->numero_pedido_cliente }}</span> 
                            <span class="order-date">{{ $pedido->fecha_pedido->format('d/m/Y') }}</span>
                            
                            {{-- 5. Usamos el tag de estado con los colores pastel --}}
                            <span class="order-status status-{{ strtolower($pedido->estado) }}">
                                {{ ucfirst($pedido->estado) }}
                            </span>

                            <span class="order-total">${{ number_format($pedido->total, 2) }}</span>
                        </div>
                        
                        <div class="order-body">
                            {{-- 6. Usamos el botón rosa suave --}}
                            <a href="{{ route('cliente.pedidos.show', $pedido) }}" class="tertiary-button">
                                Ver Detalles
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- 7. Usamos la paginación estilizada --}}
            <div class="pagination-links">
                {{ $pedidos->links() }}
            </div>
            
        @endif
    </div>
</div>
@endsection