@extends('layouts.admin')

@section('title', 'Detalle de Pedido')

@section('content')
    <div class="content-header">
        <h1>Pedido #{{ $pedido->id_pedido }}</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card" style="margin-bottom: 25px;">
        <div class="pedido-info-grid">
            <div class="info-item">
                <strong>Cliente:</strong>
                {{ $pedido->usuario->nombre ?? '—' }} {{ $pedido->usuario->apellido ?? '' }}
                @if(!empty($pedido->usuario->email))
                    <small>({{ $pedido->usuario->email }})</small>
                @endif
            </div>
            <div class="info-item">
                <strong>Fecha:</strong> {{ $pedido->fecha_pedido }}
            </div>
            
            @if($pedido->peticion || $pedido->id_peticion)
            <div class="info-item info-item-full">
                <div class="alert alert-secondary" style="display:flex; align-items-center; gap:12px; flex-wrap:wrap; margin:0;">
                    @if($pedido->peticion)
                        <div>
                            <strong>Petición origen:</strong>
                            #{{ $pedido->peticion->id_peticion }} — {{ $pedido->peticion->titulo }}
                        </div>
                        <span class="status-badge status-{{ str_replace(' ', '-', $pedido->peticion->estado) }}">{{ ucfirst($pedido->peticion->estado) }}</span>
                    @else
                        <div><strong>Petición origen:</strong> #{{ $pedido->id_peticion }}</div>
                    @endif
                    <a class="btn btn-secondary" href="{{ route('admin.peticiones.show', $pedido->peticion->id_peticion ?? $pedido->id_peticion) }}" style="padding: 8px 12px;">Ver petición</a>
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="card" style="margin-bottom: 25px;">
        <form action="{{ route('admin.pedidos.update', $pedido) }}" method="POST" class="status-update-form">
            @csrf
            @method('PUT')
            
            <div class="info-item info-item-full">
                <strong>Dirección de envío:</strong>
                <address>
                    {{ $pedido->calle }}<br>
                    {{ $pedido->colonia }}<br>
                    {{ $pedido->municipio_ciudad }}, {{ $pedido->codigo_postal }}<br>
                    {{ $pedido->estado_direccion }}
                </address>
            </div>
            
            <div class="form-group">
                <label for="total"><strong>Total:</strong></label>
                <input type="number" step="0.01" min="0" id="total" name="total" class="form-control" value="{{ old('total', $pedido->total) }}" />
            </div>
            
            <div class="form-group">
                <label for="estado"><strong>Estado:</strong></label>
                @php($estados = ['pendiente','procesando','enviado','entregado','cancelado'])
                <select id="estado" name="estado" class="form-control">
                    @foreach($estados as $e)
                        <option value="{{ $e }}" {{ $pedido->estado === $e ? 'selected' : '' }}>{{ ucfirst($e) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="metodo_pago"><strong>Método pago:</strong></label>
                <input type="text" id="metodo_pago" name="metodo_pago" class="form-control" value="{{ old('metodo_pago', $pedido->metodo_pago) }}" placeholder="tarjeta / transferencia / ..." />
            </div>

            <div class="form-group">
                <label for="empresa_envio"><strong>Empresa envío:</strong></label>
                <input type="text" id="empresa_envio" name="empresa_envio" class="form-control" value="{{ old('empresa_envio', $pedido->empresa_envio) }}" placeholder="DHL / FedEx / ..." />
            </div>

            <div class="form-group">
                <label for="codigo_rastreo"><strong>Código rastreo:</strong></label>
                <input type="text" id="codigo_rastreo" name="codigo_rastreo" class="form-control" value="{{ old('codigo_rastreo', $pedido->codigo_rastreo) }}" placeholder="ABC123..." />
            </div>

            <div class="form-group">
                <label for="fecha_envio"><strong>Fecha envío:</strong></label>
                <input type="datetime-local" id="fecha_envio" name="fecha_envio" class="form-control" value="{{ old('fecha_envio', optional($pedido->fecha_envio)->format('Y-m-d\TH:i')) }}" />
            </div>

            <div class="form-group">
                <label for="fecha_entrega_estimada"><strong>Entrega estimada:</strong></label>
                <input type="date" id="fecha_entrega_estimada" name="fecha_entrega_estimada" class="form-control" value="{{ old('fecha_entrega_estimada', optional($pedido->fecha_entrega_estimada)->format('Y-m-d')) }}" />
            </div>
            
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
    </div>

    <h2 class="sub-header">{{ $pedido->peticion ? 'Detalles de la Petición' : 'Productos del Pedido' }}</h2>
    <div class="card">
        @if($pedido->peticion)
            <div class="pedido-info-grid">
                <div class="info-item"><strong>Título:</strong> {{ $pedido->peticion->titulo }}</div>
                <div class="info-item"><strong>Estado:</strong> {{ ucfirst($pedido->peticion->estado) }}</div>
                <div class="info-item info-item-full"><strong>Descripción:</strong> <p>{{ $pedido->peticion->descripcion }}</p></div>
                @if(!empty($pedido->peticion->respuesta_admin))
                    <div class="info-item info-item-full"><strong>Respuesta admin:</strong> <p>{{ $pedido->peticion->respuesta_admin }}</p></div>
                @endif
                @if(!empty($pedido->peticion->imagen_referencia))
                    <div class="info-item">
                        <strong>Imagen de referencia:</strong><br>
                        <img src="{{ asset('storage/'.$pedido->peticion->imagen_referencia) }}" alt="Referencia" class="form-image-preview">
                    </div>
                @endif
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pedido->detalles as $detalle)
                        <tr>
                            <td data-label="Producto">{{ $detalle->producto->nombre ?? '—' }}</td>
                            <td data-label="Cantidad">{{ $detalle->cantidad }}</td>
                            <td data-label="Precio Unitario">$ {{ number_format($detalle->precio_unitario, 2) }}</td>
                            <td data-label="Subtotal">$ {{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-row">Sin productos en este pedido.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @endif
    </div>

    <div class="pedido-total">
        <strong>Total:</strong> $ {{ number_format($pedido->total, 2) }}
    </div>
@endsection