@extends('layouts.cliente')

@section('title', 'Mi Perfil - Crochettitos')

@section('content')
<div class="profile-container">
    <!-- Encabezado -->
    <div class="profile-header">
        <h1>Mi Perfil</h1>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
    </div>

    <!-- Sección de Información Personal -->
    <div class="profile-section">
        <h2><i class="fas fa-user-circle"></i> Información Personal</h2>
        <div class="profile-info-grid">
            <div class="info-item">
                <span class="info-label">Nombre:</span>
                <span class="info-value">{{ $usuario->nombre }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $usuario->email }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Teléfono:</span>
                <span class="info-value">{{ $usuario->telefono ?? 'No registrado' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Dirección:</span>
                <span class="info-value">{{ $usuario->direccion ?? 'No registrada' }}</span>
            </div>
        </div>
        <a href="{{ route('perfil.edit') }}" class="btn-edit-profile">
            <i class="fas fa-edit"></i> Editar Perfil
        </a>
    </div>

    <!-- Sección de Pedidos -->
    <div class="profile-section">
        <h2><i class="fas fa-box-open"></i> Mis Pedidos Recientes</h2>
        @if($pedidos->count() > 0)
            <div class="orders-grid">
                @foreach($pedidos as $pedido)
                    <div class="order-card">
                        <div class="order-header">
                            <span>Pedido #{{ $pedido->id }}</span>
                            <span>{{ $pedido->created_at->format('d/m/Y') }}</span>
                            <span class="status-{{ strtolower($pedido->estado) }}">
                                {{ $pedido->estado }}
                            </span>
                            <span>${{ number_format($pedido->total, 2) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="no-orders">Aún no has realizado pedidos.</p>
        @endif
    </div>
</div>
@endsection