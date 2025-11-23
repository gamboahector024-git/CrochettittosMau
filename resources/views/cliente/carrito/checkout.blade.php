@extends('layouts.cliente')

@section('title', 'Finalizar Compra - Crochettittos')

@section('content')
<div class="container">
    {{-- Contenedor principal con estilo Glassmorphism --}}
    <div class="profile-container">

        <h2 class="profile-title">Finalizar Compra</h2>

        <form action="{{ route('carrito.procesar') }}" method="POST" id="checkout-form">
            @csrf

            <div class="checkout-layout">
                
                {{-- COLUMNA IZQUIERDA: Datos de Envío --}}
                <div class="checkout-form-panel">
                    
                    <h3 class="form-subtitle">Dirección de Envío</h3>
                    
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="calle">Calle y Número</label>
                            <input type="text" class="form-input" id="calle" name="calle" value="{{ old('calle') }}" required placeholder="Ej: Av. Central 123">
                        </div>

                        <div class="form-group">
                            <label for="colonia">Colonia</label>
                            <input type="text" class="form-input" id="colonia" name="colonia" value="{{ old('colonia') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="municipio_ciudad">Municipio/Ciudad</label>
                            <input type="text" class="form-input" id="municipio_ciudad" name="municipio_ciudad" value="{{ old('municipio_ciudad') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="codigo_postal">Código Postal</label>
                            <input type="text" class="form-input" id="codigo_postal" name="codigo_postal" value="{{ old('codigo_postal') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <input type="text" class="form-input" id="estado" name="estado" value="{{ old('estado') }}" required>
                        </div>
                    </div>

                    <h3 class="form-subtitle">Método de Pago</h3>
                    <div class="form-group">
                        <label for="metodo_pago">Selecciona un método</label>
                        {{-- Agregamos un evento onchange para mostrar/ocultar PayPal --}}
                        <select class="form-input" id="metodo_pago" name="metodo_pago" required onchange="togglePaymentMethod(this.value)">
                            <option value="">Seleccione un método</option>
                            <option value="efectivo">Efectivo (Contra entrega)</option>
                            <option value="transferencia">Transferencia Bancaria</option>
                            <option value="paypal">PayPal / Tarjeta</option>
                        </select>
                    </div>

                </div>

                {{-- COLUMNA DERECHA: Resumen y Botones --}}
                <div class="checkout-summary-panel">
                    <div class="checkout-summary">
                        <h3 style="margin-bottom: 1rem; color: var(--text-dark);">Resumen del Pedido</h3>
                        
                        @foreach($items as $item)
                            <div class="summary-item">
                                <span>{{ $item->producto->nombre }} (x{{ $item->cantidad }})</span>
                                <span>${{ number_format($item->producto->precio * $item->cantidad, 2) }}</span>
                            </div>
                        @endforeach
                        
                        <div class="summary-total">
                            <strong>Total a Pagar:</strong>
                            <strong>${{ number_format($total, 2) }}</strong>
                        </div>

                        {{-- 
                           AQUÍ ESTÁ LA SOLUCIÓN:
                           1. Botón estándar para Efectivo/Transferencia
                           2. Contenedor de PayPal (oculto por defecto)
                        --}}
                        <div class="form-actions" style="margin-top: 1.5rem; flex-direction: column; width: 100%;">
                            
                            {{-- Botón normal (visible por defecto) --}}
                            <button type="submit" id="btn-confirmar" class="checkout-button" style="width: 100%;">
                                Confirmar Compra
                            </button>

                            {{-- Contenedor PayPal (oculto inicialmente) --}}
                            <div id="paypal-button-container" style="display: none; width: 100%; margin-top: 10px;"></div>
                            
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
{{-- SDK de PayPal --}}
<script src="https://www.paypal.com/sdk/js?client-id={{ config('paypal.client_id') }}&currency=MXN&disable-funding=card"></script>

<script>
    // Función simple para alternar entre el botón de confirmar y PayPal
    function togglePaymentMethod(metodo) {
        const btnConfirmar = document.getElementById('btn-confirmar');
        const paypalContainer = document.getElementById('paypal-button-container');

        if (metodo === 'paypal') {
            btnConfirmar.style.display = 'none';
            paypalContainer.style.display = 'block';
        } else {
            btnConfirmar.style.display = 'block';
            paypalContainer.style.display = 'none';
        }
    }

    // Renderizar botón de PayPal (si usas tu script externo checkout.js, asegúrate que apunte al ID correcto)
    if (typeof paypal !== 'undefined') {
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '{{ $total }}' // Total pasado desde el controlador
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    // Aquí puedes enviar el formulario o hacer una petición AJAX
                    // Para simplificar, agregamos un input oculto y enviamos el form
                    const form = document.getElementById('checkout-form');
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'paypal_order_id';
                    input.value = data.orderID;
                    form.appendChild(input);
                    
                    // Cambiar método a paypal antes de enviar si no estaba seleccionado
                    document.getElementById('metodo_pago').value = 'paypal';
                    
                    form.submit();
                });
            }
        }).render('#paypal-button-container');
    }
</script>
@endpush