@extends('layouts.cliente')

@section('title', 'Mi Perfil - Crochettitos')

@section('content')
<div class="profile-container">
    <div class="profile-header">
        <h1>Mi Perfil</h1>
        <p>Bienvenido, {{ $usuario->nombre }}</p>
    </div>

    <div class="profile-sections">
        <!-- Sección de información personal -->
        <section class="profile-section">
            <h2>Información Personal</h2>
            <div class="profile-info">
                <p><strong>Nombre:</strong> {{ $usuario->nombre }}</p>
                <p><strong>Email:</strong> {{ $usuario->email }}</p>
                <p><strong>Teléfono:</strong> {{ $usuario->telefono ?? 'No proporcionado' }}</p>
                <p><strong>Dirección:</strong> {{ $usuario->direccion ?? 'No proporcionada' }}</p>
            </div>
            <a href="#" class="edit-button">Editar Información</a>
        </section>

        <!-- Sección de pedidos -->
        <section class="profile-section">
            <h2>Mis Pedidos</h2>
            @if(count($pedidos) > 0)
                <div class="orders-list">
                    @foreach($pedidos as $pedido)
                        <div class="order-card">
                            <div class="order-header">
                                <span class="order-id">Pedido #{{ $pedido->id }}</span>
                                <span class="order-date">{{ $pedido->created_at->format('d/m/Y') }}</span>
                                <span class="order-status">{{ $pedido->estado }}</span>
                                <span class="order-total">${{ number_format($pedido->total, 2) }}</span>
                            </div>
                            <div class="order-products">
                                @foreach($pedido->productos as $producto)
                                    <div class="order-product">
                                        <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}">
                                        <span>{{ $producto->nombre }} (x{{ $producto->pivot->cantidad }})</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p>Aún no has realizado ningún pedido.</p>
            @endif
        </section>

        <!-- Sección de lista de deseos -->
        <section class="profile-section">
            <h2>Mi Lista de Deseos</h2>
            <a href="{{ route('perfil.lista-deseos') }}" class="view-wishlist">Ver lista completa</a>
            <div class="wishlist-preview">
                <!-- Mostrar algunos productos de la lista de deseos -->
            </div>
        </section>
    </div>
</div>
@endsection
