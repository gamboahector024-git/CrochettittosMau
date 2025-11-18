@extends('layouts.admin')

@section('title', 'Petición #' . $peticion->id_peticion)

@section('content')
    <div class="content-header">
        <h1>Petición #{{ $peticion->id_peticion }}</h1>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 28px;">
        
        <div class="card">
            <h2 class="sub-header">Detalle de la petición</h2>
            <div class="pedido-info-grid">
                <div class="info-item">
                    <strong>Título:</strong> {{ $peticion->titulo }}
                </div>
                
                @if($peticion->categoria)
                    <div class="info-item">
                        <strong>Categoría:</strong> {{ $peticion->categoria->nombre }}
                    </div>
                @endif
                <div class="info-item">
                    <strong>Cantidad solicitada:</strong> {{ $peticion->cantidad }} {{ $peticion->cantidad == 1 ? 'unidad' : 'unidades' }}
                </div>
                
                <div class="info-item info-item-full">
                    <strong>Descripción:</strong>
                    <p>{{ $peticion->descripcion }}</p>
                </div>
                
                <!-- Dirección de entrega -->
                @if($peticion->calle)
                    <div class="info-item info-item-full direccion-entrega">
                        <strong>Dirección de entrega:</strong>
                        <p>
                            {{ $peticion->calle }}, {{ $peticion->colonia }}<br>
                            {{ $peticion->municipio_ciudad }}, {{ $peticion->estado_direccion }}<br>
                            CP: {{ $peticion->codigo_postal }}
                        </p>
                    </div>
                @endif
                
                @if($peticion->respuesta_admin)
                    <div class="info-item info-item-full alert alert-secondary">
                        <strong>Respuesta actual:</strong>
                        <p>{{ $peticion->respuesta_admin }}</p>
                        @if($peticion->precio_propuesto)
                            <p><strong>Precio propuesto:</strong> ${{ number_format($peticion->precio_propuesto, 2) }}</p>
                            <p><small>Enviado: {{ $peticion->fecha_respuesta_admin?->format('d/m/Y H:i') }}</small></p>
                        @endif
                    </div>
                @endif

                @if($peticion->imagen_referencia)
                    <div class="info-item">
                        <strong>Imagen de referencia:</strong><br>
                        <img src="{{ asset($peticion->imagen_referencia) }}" alt="Imagen de referencia" class="form-image-preview">
                    </div>
                @endif
            </div>
        </div>

        <div>
            <div class="card" style="margin-bottom: 25px;">
                <div class="pedido-info-grid" style="grid-template-columns: 1fr;">
                    <div class="info-item">
                        <strong>Estado:</strong>
                        <span class="status-badge status-{{ str_replace(' ', '-', $peticion->estado) }}">{{ ucfirst($peticion->estado) }}</span>
                    </div>
                    <div class="info-item">
                        <strong>Fecha:</strong> {{ $peticion->created_at?->format('d/m/Y H:i') ?? 'No especificado' }}
                    </div>
                    <div class="info-item">
                        <strong>Solicitante:</strong>
                        {{ $peticion->usuario->nombre }}
                        <small>({{ $peticion->usuario->email }})</small>
                    </div>
                </div>
            </div>

            <div class="form-container">
                <h2 class="sub-header" style="margin-top: 0;">Enviar Propuesta al Cliente</h2>
                <form action="{{ route('admin.peticiones.responder', $peticion->id_peticion) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="respuesta">Respuesta / Descripción del trabajo</label>
                        <textarea id="respuesta" name="respuesta_admin" rows="5" class="form-control" required placeholder="Describe el trabajo que realizarás, materiales, tiempo estimado, etc.">{{ old('respuesta_admin', $peticion->respuesta_admin) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="precio">Precio propuesto (MXN) *</label>
                        <input type="number" id="precio" name="precio_propuesto" step="0.01" min="0.01" class="form-control" value="{{ old('precio_propuesto', $peticion->precio_propuesto) }}" required placeholder="0.00">
                    </div>

                    <div class="nota-informativa">
                        <strong>Nota:</strong> Al enviar la propuesta, el estado cambiará automáticamente a <strong>"Aceptada"</strong> y el cliente podrá decidir si acepta o rechaza.
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            {{ $peticion->respuesta_admin ? 'Actualizar Propuesta' : 'Enviar Propuesta al Cliente' }}
                        </button>
                    </div>
                </form>

                @if($peticion->estado === 'completada')
                    <div class="alert alert-success">
                        <strong>Petición completada</strong><br>
                        <small>El cliente aceptó la propuesta y realizó el pago. El pedido fue generado automáticamente.</small>
                    </div>
                @elseif($peticion->estado === 'rechazada')
                    <div class="alert alert-danger">
                        <strong>Petición rechazada</strong><br>
                        <small>El cliente rechazó la propuesta.</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection