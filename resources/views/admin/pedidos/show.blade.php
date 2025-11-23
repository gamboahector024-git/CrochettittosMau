@extends('layouts.admin')

@section('title', 'Detalle de Pedido')

@section('content')
    <div class="content-header" style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-family: var(--font-heading); color: var(--accent); font-size: 2.5rem;">
                Pedido #{{ $pedido->id_pedido }}
            </h1>
            <p style="color: var(--text-muted);">Realizado el {{ date('d/m/Y \a \l\a\s H:i', strtotime($pedido->fecha_pedido)) }}</p>
        </div>
        <div>
            {{-- Badge Grande del Estado Actual --}}
            <span class="status-badge status-{{ $pedido->estado }}" style="font-size: 1rem; padding: 10px 20px;">
                {{ ucfirst($pedido->estado) }}
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.pedidos.update', $pedido) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-grid">
            
            {{-- COLUMNA IZQUIERDA: Información (Lectura) --}}
            <div class="left-column">
                
                {{-- 1. Tarjeta de Cliente y Envío --}}
                <div class="card" style="margin-bottom: 25px;">
                    <h3 style="font-size: 1.1rem; color: var(--accent); margin-bottom: 15px; border-bottom: 1px solid var(--border); padding-bottom: 10px;">
                        👤 Cliente y Envío
                    </h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                            <strong style="display:block; color:var(--text-dark);">Datos de Contacto</strong>
                            <p style="color: var(--text-muted); margin-top: 5px;">
                                {{ $pedido->usuario->nombre ?? 'Invitado' }} {{ $pedido->usuario->apellido ?? '' }}<br>
                                {{ $pedido->usuario->email }}<br>
                                {{ $pedido->usuario->telefono ?? 'Sin teléfono' }}
                            </p>
                        </div>
                        <div>
                            <strong style="display:block; color:var(--text-dark);">Dirección de Entrega</strong>
                            <p style="color: var(--text-muted); margin-top: 5px; line-height: 1.5;">
                                {{ $pedido->calle }} {{ $pedido->numero ?? '' }}<br>
                                {{ $pedido->colonia }}<br>
                                {{ $pedido->municipio_ciudad }}, CP: {{ $pedido->codigo_postal }}<br>
                                {{ $pedido->estado_direccion }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- 2. Tarjeta de Contenido (Productos o Petición) --}}
                <div class="card">
                    <h3 style="font-size: 1.1rem; color: var(--accent); margin-bottom: 15px; border-bottom: 1px solid var(--border); padding-bottom: 10px;">
                        📦 Contenido del Pedido
                    </h3>

                    @if($pedido->peticion)
                        {{-- CASO 1: Es una Petición Personalizada --}}
                        <div class="alert alert-secondary" style="border-left: 4px solid var(--accent);">
                            <strong>Este pedido proviene de una Petición Personalizada (#{{ $pedido->peticion->id_peticion }})</strong>
                        </div>
                        <div style="display: flex; gap: 20px; margin-top: 15px;">
                            @if($pedido->peticion->imagen_referencia)
                                <img src="{{ asset($pedido->peticion->imagen_referencia) }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px; border: 1px solid var(--border);">
                            @endif
                            <div>
                                <h4 style="color: var(--text-dark); margin: 0;">{{ $pedido->peticion->titulo }}</h4>
                                <p style="color: var(--text-muted); margin-top: 5px;">{{ $pedido->peticion->descripcion }}</p>
                            </div>
                        </div>
                    @else
                        {{-- CASO 2: Son Productos Normales --}}
                        <table style="width: 100%;">
                            <thead style="border-bottom: 1px solid var(--border);">
                                <tr style="text-align: left; color: var(--text-muted); font-size: 0.9em;">
                                    <th style="padding-bottom: 10px;">Producto</th>
                                    <th style="padding-bottom: 10px;">Cant.</th>
                                    <th style="padding-bottom: 10px; text-align: right;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pedido->detalles as $detalle)
                                    <tr>
                                        <td style="padding: 12px 0; border-bottom: 1px solid rgba(0,0,0,0.05);">
                                            <div style="font-weight: 500;">{{ $detalle->producto->nombre ?? 'Producto Eliminado' }}</div>
                                            <small style="color: var(--text-muted);">${{ number_format($detalle->precio_unitario, 2) }} c/u</small>
                                        </td>
                                        <td style="padding: 12px 0; border-bottom: 1px solid rgba(0,0,0,0.05);">x{{ $detalle->cantidad }}</td>
                                        <td style="padding: 12px 0; border-bottom: 1px solid rgba(0,0,0,0.05); text-align: right; font-weight: 600;">
                                            ${{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" style="text-align: right; padding-top: 15px; font-weight: 600;">Total Pagado:</td>
                                    <td style="text-align: right; padding-top: 15px; font-weight: 700; font-size: 1.2em; color: var(--accent);">
                                        ${{ number_format($pedido->total, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    @endif
                </div>
            </div>

            {{-- COLUMNA DERECHA: Gestión (Acción) --}}
            <div class="right-column">
                
                <div class="card" style="position: sticky; top: 20px;">
                    <h3 style="font-size: 1.1rem; color: var(--text-dark); margin-bottom: 20px;">
                        🛠️ Gestionar Envío
                    </h3>

                    {{-- Estado --}}
                    <div class="form-group">
                        <label for="estado">Actualizar Estado</label>
                        <select name="estado" id="estado" class="form-control" style="font-weight: 600;">
                            @foreach(['pendiente','procesando','enviado','entregado','cancelado'] as $e)
                                <option value="{{ $e }}" {{ $pedido->estado === $e ? 'selected' : '' }}>
                                    {{ ucfirst($e) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <hr style="border: 0; border-top: 1px solid var(--border); margin: 20px 0;">

                    {{-- Datos de Rastreo --}}
                    <div class="form-group">
                        <label for="empresa_envio">Empresa de Paquetería</label>
                        <input type="text" name="empresa_envio" id="empresa_envio" class="form-control" 
                               value="{{ old('empresa_envio', $pedido->empresa_envio) }}" placeholder="Ej. FedEx">
                    </div>

                    <div class="form-group">
                        <label for="codigo_rastreo">Código de Rastreo</label>
                        <input type="text" name="codigo_rastreo" id="codigo_rastreo" class="form-control" 
                               value="{{ old('codigo_rastreo', $pedido->codigo_rastreo) }}" placeholder="Ej. TRK-123456">
                    </div>

                    {{-- Fechas (Colapsadas en row-2-cols si prefieres, o vertical) --}}
                    <div class="form-group">
                        <label for="fecha_envio">Fecha de Envío</label>
                        <input type="datetime-local" name="fecha_envio" id="fecha_envio" class="form-control"
                               value="{{ old('fecha_envio', optional($pedido->fecha_envio)->format('Y-m-d\TH:i')) }}">
                    </div>

                    {{-- Campos Administrativos (Total y Método) --}}
                    {{-- Usualmente no se editan, pero los dejamos accesibles por si acaso --}}
                    <details style="margin-top: 20px; color: var(--text-muted); cursor: pointer;">
                        <summary style="font-size: 0.9em;">Editar detalles financieros</summary>
                        <div style="margin-top: 15px; padding: 10px; background: rgba(0,0,0,0.03); border-radius: 8px;">
                             <div class="form-group">
                                <label style="font-size:0.85em">Método Pago</label>
                                <input type="text" name="metodo_pago" class="form-control" value="{{ $pedido->metodo_pago }}">
                            </div>
                            <div class="form-group">
                                <label style="font-size:0.85em">Total ($)</label>
                                <input type="number" step="0.01" name="total" class="form-control" value="{{ $pedido->total }}">
                            </div>
                        </div>
                    </details>

                    <div class="form-actions" style="margin-top: 25px;">
                        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                            Guardar Cambios
                        </button>
                        <a href="{{ route('admin.pedidos.index') }}" class="btn btn-secondary" style="width: 100%; margin-top: 10px; text-align: center; display: block;">
                            Volver
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </form>
@endsection