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

        <!-- Tarjeta Peticiones -->
        <div class="bg-gradient-to-r from-pink-500 to-pink-600 p-6 rounded-xl shadow-lg text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">📮 Peticiones</h3>
                    <p class="text-3xl font-bold mt-2">{{ $peticionesCount }}</p>
                </div>
                <div class="text-pink-200 text-4xl">📮</div>
            </div>
        </div>

        <!-- Resumen Visitas Diarias -->
        <div class="bg-white p-6 rounded-xl shadow-lg text-gray-800 md:col-span-2 lg:col-span-3">
            <h3 class="text-lg font-semibold mb-4">📈 Visitas de los últimos 7 días</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
                @foreach ($visitasDiariasLabels as $index => $label)
                    <div class="text-center">
                        <div class="text-sm text-gray-500">{{ $label }}</div>
                        <div class="text-2xl font-semibold">{{ $visitasDiariasData[$index] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Resumen Visitas Mensuales -->
        <div class="bg-white p-6 rounded-xl shadow-lg text-gray-800 md:col-span-2 lg:col-span-3">
            <h3 class="text-lg font-semibold mb-4">📊 Visitas de los últimos 6 meses</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-3">
                @foreach ($visitasMensualesLabels as $index => $label)
                    <div class="text-center">
                        <div class="text-sm text-gray-500">{{ $label }}</div>
                        <div class="text-2xl font-semibold">{{ $visitasMensualesData[$index] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Reloj + Calendario -->
        <div class="bg-white p-6 rounded-xl shadow-lg text-gray-800 md:col-span-2 lg:col-span-3">
            <div class="flex flex-col lg:flex-row lg:items-start gap-6">
                <!-- Reloj -->
                <div class="flex-1">
                    <h3 class="text-lg font-semibold mb-2">🕒 Hora actual</h3>
                    <div class="text-4xl font-bold" id="clock-time">--:--:--</div>
                    <div class="text-gray-500 mt-1" id="clock-date">--</div>
                </div>
                <!-- Calendario -->
                <div class="flex-[2] w-full">
                    <h3 class="text-lg font-semibold mb-2">🗓️ Calendario</h3>
                    <div id="calendar" class="border rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div id="cal-month" class="font-semibold"></div>
                            <div id="cal-year" class="text-gray-500"></div>
                        </div>
                        <div class="grid grid-cols-7 gap-1 text-center text-sm font-medium text-gray-600">
                            <div>Dom</div><div>Lun</div><div>Mar</div><div>Mié</div><div>Jue</div><div>Vie</div><div>Sáb</div>
                        </div>
                        <div id="cal-grid" class="grid grid-cols-7 gap-1 mt-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const timeEl = document.getElementById('clock-time');
        const dateEl = document.getElementById('clock-date');
        const optsTime = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
        const optsDate = { weekday: 'long', year: 'numeric', month: 'long', day: '2-digit' };
        function tick(){
            const now = new Date();
            timeEl.textContent = now.toLocaleTimeString('es-MX', optsTime);
            dateEl.textContent = now.toLocaleDateString('es-MX', optsDate);
        }
        tick();
        setInterval(tick, 1000);

        // Calendario del mes actual
        const calMonthEl = document.getElementById('cal-month');
        const calYearEl = document.getElementById('cal-year');
        const calGridEl = document.getElementById('cal-grid');
        const today = new Date();
        const year = today.getFullYear();
        const month = today.getMonth(); // 0-11
        const first = new Date(year, month, 1);
        const last = new Date(year, month + 1, 0);
        calMonthEl.textContent = first.toLocaleString('es-MX', { month: 'long' }).replace(/^./, c => c.toUpperCase());
        calYearEl.textContent = year;
        const startWeekday = first.getDay(); // 0=Dom
        const daysInMonth = last.getDate();
        calGridEl.innerHTML = '';
        for(let i=0;i<startWeekday;i++){
            const cell = document.createElement('div');
            cell.className = 'h-10';
            calGridEl.appendChild(cell);
        }
        for(let d=1; d<=daysInMonth; d++){
            const cell = document.createElement('div');
            const isToday = d === today.getDate();
            cell.className = 'h-10 flex items-center justify-center rounded ' + (isToday ? 'bg-blue-600 text-white font-semibold' : 'bg-gray-50');
            cell.textContent = d;
            calGridEl.appendChild(cell);
        }
    });
    </script>
@endsection
