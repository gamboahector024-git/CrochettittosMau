@extends('layouts.admin')

@section('title', 'Estadísticas')
@section('header', 'Estadísticas del Sitio')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Tarjeta Ventas -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded-xl shadow-lg text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">💰 Ventas del mes</h3>
                    <p class="text-3xl font-bold mt-2">${{ number_format($ventasMes, 2) }}</p>
                </div>
                <div class="text-blue-200 text-4xl">💰</div>
            </div>
        </div>

        <!-- Tarjeta Productos -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 rounded-xl shadow-lg text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">📦 Productos vendidos</h3>
                    <p class="text-3xl font-bold mt-2">{{ $productosVendidos }}</p>
                </div>
                <div class="text-green-200 text-4xl">📦</div>
            </div>
        </div>

        <!-- Tarjeta Usuarios -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 rounded-xl shadow-lg text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">👥 Usuarios activos</h3>
                    <p class="text-3xl font-bold mt-2">{{ $usuariosActivos }}</p>
                </div>
                <div class="text-purple-200 text-4xl">👥</div>
            </div>
        </div>

        <!-- Tarjeta Pedidos -->
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 p-6 rounded-xl shadow-lg text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">🛒 Pedidos pendientes</h3>
                    <p class="text-3xl font-bold mt-2">{{ $pedidosPendientes }}</p>
                </div>
                <div class="text-yellow-200 text-4xl">🛒</div>
            </div>
        </div>

        <!-- Tarjeta Visitas -->
        <div class="bg-gradient-to-r from-red-500 to-red-600 p-6 rounded-xl shadow-lg text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">👁️ Visitas del sitio</h3>
                    <p class="text-3xl font-bold mt-2">{{ $visitas }}</p>
                </div>
                <div class="text-red-200 text-4xl">👁️</div>
            </div>
        </div>
    </div>
@endsection
