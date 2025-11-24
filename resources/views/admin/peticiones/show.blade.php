@extends('layouts.admin')

@section('title', 'Detalle de Petición')

@section('content')
    <div class="content-header" style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-family: var(--font-heading); color: var(--accent); font-size: 2.5rem;">
                Petición #{{ $peticion->id_peticion }}
            </h1>
            <p style="color: var(--text-muted);">Cliente: <strong>{{ $peticion->usuario->nombre }}</strong></p>
        </div>
        <div>
            <span class="status-badge status-{{ Str::slug($peticion->estado) }}" style="font-size: 1rem; padding: 10px 20px;">
                {{ ucfirst($peticion->estado) }}
            </span>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="form-grid">
        
        {{-- COLUMNA IZQUIERDA: Detalle de lo que pide el cliente --}}
        <div class="left-column">
            
            {{-- 1. Tarjeta Principal de la Solicitud --}}
            <div class="card" style="margin-bottom: 25px;">
                <h3 style="font-size: 1.2rem; color: var(--text-dark); margin-bottom: 15px; display:flex; align-items:center; gap:10px;">
                    {{ $peticion->titulo }}
                </h3>
                
                <div style="background: rgba(0,0,0,0.03); padding: 15px; border-radius: 12px; margin-bottom: 20px;">
                    <strong style="display:block; color:var(--text-muted); font-size:0.9rem; margin-bottom:5px;">DESCRIPCIÓN DEL CLIENTE:</strong>
                    <p style="line-height: 1.6; color: var(--text-dark);">
                        {{ $peticion->descripcion }}
                    </p>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <strong>Categoría:</strong><br>
                        <span style="color: var(--text-muted);">{{ $peticion->categoria->nombre ?? 'General' }}</span>
                    </div>
                    <div>
                        <strong>Cantidad:</strong><br>
                        <span style="color: var(--text-muted);">{{ $peticion->cantidad }} unidad(es)</span>
                    </div>
                </div>
            </div>

            {{-- 2. Imagen de Referencia (Tipo Polaroid) --}}
            @if($peticion->imagen_referencia)
                <div class="card" style="margin-bottom: 25px; text-align: center;">
                    <h4 style="color: var(--text-muted); font-size: 0.9rem; text-align: left; margin-bottom: 10px;">IMAGEN DE REFERENCIA</h4>
                    <div style="padding: 10px; background: #fff; display: inline-block; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transform: rotate(-2deg);">
                        <img src="{{ asset($peticion->imagen_referencia) }}" style="max-width: 100%; max-height: 300px; border-radius: 4px;">
                    </div>
                </div>
            @endif

            {{-- 3. Dirección --}}
            @if($peticion->calle)
                <div class="card">
                    <h4 style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 10px;">DIRECCIÓN DE ENTREGA DESEADA</h4>
                    <address style="font-style: normal; color: var(--text-dark);">
                        {{ $peticion->calle }}, {{ $peticion->colonia }}<br>
                        {{ $peticion->municipio_ciudad }}, {{ $peticion->estado_direccion }}<br>
                        CP: {{ $peticion->codigo_postal }}
                    </address>
                </div>
            @endif
        </div>

        {{-- COLUMNA DERECHA: Tu Acción (Propuesta) --}}
        <div class="right-column">
            
            <div class="card" style="position: sticky; top: 20px; border-top: 5px solid var(--accent);">
                <h3 style="font-size: 1.2rem; color: var(--accent); margin-bottom: 20px;">
                    Enviar Propuesta
                </h3>

                <form action="{{ route('admin.peticiones.responder', $peticion->id_peticion) }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="precio">Precio Propuesto ($ MXN)</label>
                        <div style="position: relative;">
                            <span style="position: absolute; left: 15px; top: 12px; color: var(--text-muted);">$</span>
                            <input type="number" id="precio" name="precio_propuesto" step="0.01" min="0.01" class="form-control" 
                                   value="{{ old('precio_propuesto', $peticion->precio_propuesto) }}" 
                                   placeholder="0.00" style="padding-left: 30px; font-weight: bold; font-size: 1.1em;" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="respuesta">Detalles de la Propuesta</label>
                        <textarea id="respuesta" name="respuesta_admin" rows="6" class="form-control" 
                                  placeholder="Hola {{ $peticion->usuario->nombre }}, claro que puedo hacerlo. Tardaré aproximadamente 5 días..." required>{{ old('respuesta_admin', $peticion->respuesta_admin) }}</textarea>
                        <small style="color: var(--text-muted);">Describe materiales, tiempo de entrega y detalles.</small>
                    </div>

                    @if($peticion->estado === 'completada')
                        <div class="alert alert-success" style="margin-top: 20px;">
                            <strong>Pedido Generado</strong><br>
                            Esta petición ya se convirtió en venta.
                        </div>
                    @else
                        <div class="form-actions" style="flex-direction: column; gap: 10px;">
                            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                                {{ $peticion->respuesta_admin ? 'Actualizar Propuesta' : 'Enviar Cotización' }}
                            </button>
                            
                            <p style="font-size: 0.8rem; color: var(--text-muted); text-align: center; margin-top: 10px;">
                                Al enviar, el estado cambiará a <strong>"Aceptada"</strong> (por ti) y el cliente recibirá la notificación.
                            </p>
                        </div>
                    @endif
                </form>

                @if($peticion->estado === 'rechazada')
                     <div class="alert alert-danger" style="margin-top: 20px;">
                        El cliente rechazó esta propuesta.
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection