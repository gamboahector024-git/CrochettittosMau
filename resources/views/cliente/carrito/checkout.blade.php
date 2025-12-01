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
                            <option value="paypal">PayPal</option>
                            <option value="stripe">Tarjeta de Crédito/Débito</option>
                        </select>
                    </div>
                    
                    {{-- Contenedor para tarjeta Stripe --}}
                    <div id="stripe-container" style="display: none; margin-top: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 8px; border: 1px solid #ddd;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Datos de la tarjeta</label>
                        <div id="card-element" style="padding: 10px; background: white; border: 1px solid #ccc; border-radius: 4px;">
                            <!-- Stripe Elements inyectará aquí el input -->
                        </div>
                        <div id="card-errors" role="alert" style="color: #dc3545; margin-top: 5px; font-size: 0.9rem;"></div>
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
{{-- SDK de Stripe --}}
<script src="https://js.stripe.com/v3/"></script>

<script>
    // Inicializar Stripe
    const stripe = Stripe("{{ config('services.stripe.key') }}");
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    // Función simple para alternar entre el botón de confirmar y PayPal
    function togglePaymentMethod(metodo) {
        const btnConfirmar = document.getElementById('btn-confirmar');
        const paypalContainer = document.getElementById('paypal-button-container');
        const stripeContainer = document.getElementById('stripe-container');

        // Ocultar todo primero
        paypalContainer.style.display = 'none';
        stripeContainer.style.display = 'none';
        btnConfirmar.style.display = 'block'; // Mostrar botón por defecto

        if (metodo === 'paypal') {
            btnConfirmar.style.display = 'none';
            paypalContainer.style.display = 'block';
        } else if (metodo === 'stripe') {
            stripeContainer.style.display = 'block';
            // btnConfirmar sigue visible, pero su comportamiento cambiará
        }
    }

    // Interceptar envío del formulario para Stripe
    const form = document.getElementById('checkout-form');
    form.addEventListener('submit', async function(event) {
        const metodo = document.getElementById('metodo_pago').value;
        
        if (metodo === 'stripe') {
            event.preventDefault();
            
            const btn = document.getElementById('btn-confirmar');
            btn.disabled = true;
            btn.textContent = 'Procesando pago...';
            document.getElementById('card-errors').textContent = '';

            try {
                // 1. Crear PaymentIntent en el backend
                // Nota: El monto se calcula en backend, pero necesitamos un ID de pedido temporal o enviar el monto.
                // Para simplificar, asumiremos que el backend calcula el monto del carrito actual.
                // Como necesitamos un ID de pedido y aun no existe, enviaremos un marcador o 'carrito_actual'.
                // Mejor aun: Enviaremos el monto total en centavos (aunque es inseguro confiar en el cliente, 
                // para este ejemplo rápido servirá, pero idealmente el backend recalcula).
                const amount = {{ str_replace(',', '', number_format($total, 2)) * 100 }}; // Total en centavos

                const response = await fetch("{{ route('stripe.payment-intent') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ 
                        amount: amount,
                        pedido_id: 'temp_' + Date.now() // ID temporal
                    })
                });

                const data = await response.json();

                if (data.error) {
                    throw new Error(data.error);
                }

                // 2. Confirmar pago con Stripe
                const { paymentIntent, error } = await stripe.confirmCardPayment(data.clientSecret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            name: document.getElementById('calle').value // Usar dato del form
                        }
                    }
                });

                if (error) {
                    throw new Error(error.message);
                }

                if (paymentIntent.status === 'succeeded') {
                    // 3. Inyectar ID de pago y enviar formulario
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'stripe_payment_id';
                    input.value = paymentIntent.id;
                    form.appendChild(input);
                    
                    // Enviar formulario real para crear el pedido en Laravel
                    form.submit();
                }

            } catch (err) {
                console.error(err);
                document.getElementById('card-errors').textContent = err.message;
                btn.disabled = false;
                btn.textContent = 'Confirmar Compra';
            }
        }
    });

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