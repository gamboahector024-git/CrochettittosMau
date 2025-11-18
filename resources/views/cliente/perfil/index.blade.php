@extends('layouts.cliente')

@section('title', 'Mi Perfil - Crochettittos')

@section('content')
<div class="container">
    {{-- 
      Este es el contenedor principal con efecto "glass"
      que definimos en el CSS.
    --}}
    <div class="profile-container">

        {{-- 
          Usamos la clase .profile-title que ya tiene el estilo
          con la fuente 'Playfair Display' y la línea rosa.
        --}}
        <h2 class="profile-title">Mi Perfil</h2>

        <div class="profile-sections">
            <section class="profile-section">
                {{-- Usamos h3 para los subtítulos, como en los filtros --}}
                <h3>Información Personal</h3>
                <div class="profile-info">
                    <p><strong>Nombre:</strong> {{ $usuario->nombre }}</p>
                    <p><strong>Email:</strong> {{ $usuario->email }}</p>
                    <p><strong>Teléfono:</strong> {{ $usuario->telefono ?? 'No proporcionado' }}</p>
                    <p><strong>Dirección:</strong> {{ $usuario->direccion ?? 'No proporcionada' }}</p>
                </div>
                
                {{-- 
                  Usamos la clase de botón que ya existe en tu CSS
                  para el enlace de "Editar Perfil".
                  Asegúrate que la ruta 'perfil.edit' exista en tu web.php
                --}}
                <a href="{{ route('perfil.edit') }}" class="tertiary-button">Editar Información</a>
            </section>

            <section class="profile-section">
                <h3>Mis Pedidos Recientes</h3>
                @if(isset($pedidos) && count($pedidos) > 0)
                    <div class="orders-list">
                        @foreach($pedidos as $pedido)
                            <div class="order-card">
                                <div class="order-header">
                                    <span class="order-id">Pedido #{{ $pedido->id }}</span>
                                    <span class="order-date">{{ $pedido->created_at->format('d/m/Y') }}</span>
                                    
                                    {{-- 
                                      Añadimos una clase extra para el estado, 
                                      así le podemos dar color
                                    --}}
                                    <span class="order-status status-{{ strtolower($pedido->estado) }}">
                                        {{ $pedido->estado }}
                                    </span>

                                    <span class="order-total">${{ number_format($pedido->total, 2) }}</span>
                                </div>
                                <div class="order-products">
                                    @foreach($pedido->productos as $producto)
                                        <div class="order-product">
                                            {{-- Asegúrate de que $producto->imagen_url sea la ruta correcta --}}
                                            <img src="{{ $producto->imagen_url ?? asset('images/default.png') }}" alt="{{ $producto->nombre }}">
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

            <section class="profile-section">
                <h3>Mis Peticiones Recientes</h3>
                
                {{-- 
                  Asumo que puedes pasar una variable $peticiones a esta vista,
                  igual que pasaste $pedidos. Si no, puedes borrar el @if
                --}}
                @if(isset($peticiones) && count($peticiones) > 0)
                    <div class="petitions-list">
                        {{-- 
                          Ajusta esto a tus datos. 
                          Muestra solo las 3 más recientes.
                        --}}
                        @foreach($peticiones->take(3) as $peticion) 
                            <div class="peticion-card">
                                <div class="peticion-header">
                                    <span class="peticion-id">Petición #{{ $peticion->id_peticion }}</span>
                                    <span class="peticion-date">{{ optional($peticion->created_at)->format('d/m/Y') }}</span>
                                    <span class="peticion-status status-{{ strtolower(str_replace(' ', '-', $peticion->estado)) }}">
                                        {{ ucfirst($peticion->estado) }}
                                    </span>
                                </div>
                                <div class="peticion-body">
                                    <strong>{{ $peticion->titulo }}</strong>
                                    {{-- Usamos Str::limit para acortar la descripción --}}
                                    <p>{{ \Illuminate\Support\Str::limit($peticion->descripcion, 150) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>Aún no has realizado ninguna petición.</p>
                @endif
                
                <div class="profile-actions">
                    {{-- 
                      Este enlace va al índice de peticiones, 
                      que ya tenías en tu header
                    --}}
                    <a href="{{ route('cliente.peticiones.index') }}" class="tertiary-button">Ver todas mis peticiones</a>
                    
                    {{-- 
                      Este botón abre el modal para crear una nueva.
                      Usa el ID #newPeticionButton para que el JS funcione.
                    --}}
                    <a href="#" id="newPeticionButton" class="primary-button">Crear Nueva Petición</a>
                </div>

            </section>
        </div>
    </div>
</div>
@endsection