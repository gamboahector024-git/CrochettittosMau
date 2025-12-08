{{-- resources/views/cliente/carrito/checkout.blade.php --}}
@extends('layouts.cliente')

@section('title', 'Finalizar Compra - Crochettittos')

@section('content')
<div class="container cart-page-container">

    <div class="cart-header-title">
        <h1><i class="fas fa-check-circle"></i> Finalizar Compra</h1>
    </div>

    <form action="{{ route('carrito.procesar') }}" method="POST" id="checkout-form">
        @csrf

        <div class="cart-wrapper">
            
            {{-- COLUMNA IZQUIERDA: Datos --}}
            <div class="cart-items-column">
                
                {{-- Tarjeta 1: Dirección de Envío --}}
                <div class="glass-card mb-4">
                    <h3 class="section-title"><i class="fas fa-map-marker-alt"></i> Dirección de Envío</h3>
                    
                    <div class="checkout-form-grid">
                        <div class="form-group full-span">
                            <label for="calle">Calle y Número</label>
                            <input type="text" class="form-input" id="calle" name="calle" value="{{ old('calle') }}" required placeholder="Ej: Av. Central 123, Depto 4">
                        </div>

                        <div class="form-group">
                            <label for="colonia">Colonia</label>
                            <input type="text" class="form-input" id="colonia" name="colonia" value="{{ old('colonia') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="codigo_postal">Código Postal</label>
                            <input type="text" class="form-input" id="codigo_postal" name="codigo_postal" value="{{ old('codigo_postal') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="municipio_ciudad">Ciudad / Municipio</label>
                            <input type="text" class="form-input" id="municipio_ciudad" name="municipio_ciudad" value="{{ old('municipio_ciudad') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <input type="text" class="form-input" id="estado" name="estado" value="{{ old('estado') }}" required>
                        </div>
                    </div>
                </div>

                {{-- Tarjeta 2: Método de Pago --}}
                <div class="glass-card">
                    <h3 class="section-title"><i class="fas fa-wallet"></i> Método de Pago</h3>
                    
                    {{-- Selector de Método --}}
                    <div class="form-group">
                        <label for="metodo_pago">Selecciona un método</label>
                        {{-- Agregamos un evento onchange para mostrar/ocultar PayPal --}}
                        <select class="form-input" id="metodo_pago" name="metodo_pago" required onchange="togglePaymentMethod(this.value)">
                            <option value="">Seleccione un método</option>
                            <option value="efectivo">Efectivo (Contra entrega)</option>
                            <option value="transferencia">Transferencia Bancaria</option>
                            <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                            <option value="paypal">PayPal</option>
                        </select>
                    </div>
                    
                    {{-- Contenedor para tarjeta Stripe (si se usa) --}}
                    {{-- <div id="stripe-container" style="display: none; margin-top: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 8px; border: 1px solid #ddd;">...</div> --}}

                    {{-- FORMULARIO DE TARJETA (Oculto por defecto) --}}
                    {{-- Nota: quitamos la clase 'card-form-hidden' de CSS si queremos controlarlo full con JS inline, o usamos la clase toggling --}}
                    <div id="card-form-container" style="display: none; margin-top: 1.5rem;">
                        <h4 class="card-form-title">
                            <i class="far fa-credit-card"></i> Datos de la Tarjeta
                        </h4>
                        
                        {{-- Nombre del Titular --}}
                        <div class="form-group mb-3">
                            <label for="card_name">Nombre del Titular</label>
                            <div class="input-with-icon">
                                <input type="text" class="form-input" id="card_name" name="card_name" 
                                       placeholder="Ej: JUAN PEREZ GARCIA" 
                                       pattern="[A-Za-zñÑ\s]+" 
                                       title="Solo letras y espacios">
                                <i class="fas fa-user input-icon-right"></i>
                            </div>
                        </div>

                        {{-- Número de Tarjeta --}}
                        <div class="form-group mb-3">
                            <label for="card_number">Número de Tarjeta</label>
                            <div class="input-with-icon">
                                <input type="text" class="form-input" id="card_number" name="card_number" 
                                       placeholder="0000 0000 0000 0000" 
                                       maxlength="19">
                                <i class="fas fa-lock input-icon-right"></i>
                            </div>
                        </div>

                        {{-- Fecha de Vencimiento y CVV --}}
                        <div class="checkout-form-grid">
                            <div class="form-group">
                                <label for="card_expiry">Fecha de Vencimiento</label>
                                <div class="input-with-icon">
                                    <input type="text" class="form-input" id="card_expiry" name="card_expiry" 
                                           placeholder="MM/AA" 
                                           maxlength="5">
                                    <i class="far fa-calendar-alt input-icon-right"></i>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="card_cvv">Código de Seguridad</label>
                                <div class="input-with-icon cvv-tooltip">
                                    <input type="text" class="form-input" id="card_cvv" name="card_cvv" 
                                           placeholder="123" 
                                           maxlength="4">
                                    <i class="fas fa-shield-alt input-icon-right"></i>
                                    <span class="tooltip-text">
                                        El CVV es el número de 3 dígitos en el reverso de tu tarjeta.
                                        Para American Express, son 4 dígitos en el frente.
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Logos de Tarjetas Aceptadas --}}
                        <div class="card-icons-row">
                            <i class="fab fa-cc-visa" title="Visa" style="color: #1A1F71;"></i>
                            <i class="fab fa-cc-mastercard" title="Mastercard" style="color: #EB001B;"></i>
                            <i class="fab fa-cc-amex" title="American Express" style="color: #006FCF;"></i>
                            <i class="fab fa-cc-discover" title="Discover" style="color: #FF6000;"></i>
                        </div>
                        
                        {{-- Mensaje de Seguridad --}}
                        <div class="security-message">
                            <i class="fas fa-lock"></i>
                            Tus datos están protegidos con encriptación SSL. No almacenamos información de tu tarjeta.
                        </div>
                    </div>
                </div>
            </div>

            {{-- COLUMNA DERECHA: Resumen Sticky --}}
            <div class="cart-summary-column">
                <div class="summary-card glass-card sticky-element">
                    <h3>Resumen del Pedido</h3>
                    
                    <div class="mini-items-list">
                        @foreach($items as $item)
                            <div class="mini-item">
                                <div class="mini-item-info">
                                    <span class="item-name">{{ $item->producto->nombre }}</span>
                                    <span class="item-qty">x{{ $item->cantidad }}</span>
                                </div>
                                <span class="item-price">${{ number_format($item->producto->precio * $item->cantidad, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="summary-divider"></div>
                    
                    <div class="summary-details">
                        <div class="summary-row total">
                            <span>Total a Pagar:</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>

                        {{-- 
                           AQUÍ ESTÁ LA SOLUCIÓN:
                           1. Botón estándar para Efectivo/Transferencia/Tarjeta
                           2. Contenedor de PayPal (oculto por defecto)
                        --}}
                        <div class="form-actions" style="margin-top: 1.5rem; flex-direction: column; width: 100%;">
                            
                            {{-- Botón normal (visible por defecto) --}}
                            <button type="submit" id="btn-confirmar" class="checkout-button" style="width: 100%;">
                                Confirmar Compra
                            </button>

                        <div id="paypal-button-container" style="display: none; width: 100%;"></div>
                        
                        <a href="{{ route('carrito.index') }}" class="continue-shopping-link" style="margin-top: 10px;">
                            <i class="fas fa-arrow-left"></i> Volver al Carrito
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@push('scripts')
{{-- SDK de PayPal --}}
<script src="https://www.paypal.com/sdk/js?client-id={{ config('paypal.client_id') }}&currency=MXN&disable-funding=card"></script>

<script>
    // Hacer la función global explícitamente y agregar logs para depuración
    window.togglePaymentMethod = function(metodo) {
        console.log("Cambiando método de pago a:", metodo);
        
        const btnConfirmar = document.getElementById('btn-confirmar');
        const paypalContainer = document.getElementById('paypal-button-container');
        const cardContainer = document.getElementById('card-form-container');

        // Resetear visibilidad general
        cardContainer.style.display = 'none';
        
        if (metodo === 'paypal') {
            console.log("Mostrando contenedor de PayPal");
            btnConfirmar.style.display = 'none';
            paypalContainer.style.display = 'block';
        } else if (metodo === 'tarjeta') {
            btnConfirmar.style.display = 'block';
            paypalContainer.style.display = 'none';
            cardContainer.style.display = 'block';
        } else {
            btnConfirmar.style.display = 'block';
            paypalContainer.style.display = 'none';
        }
    };

    // Renderizar botón de PayPal con integración Server-Side
    if (typeof paypal !== 'undefined') {
        paypal.Buttons({
            createOrder: function(data, actions) {
                // Validación básica de formulario
                const form = document.getElementById('checkout-form');
                if (!form.reportValidity()) {
                    return Promise.reject(new Error("Por favor completa todos los campos del formulario."));
                }
                
                // Recolectar datos del formulario para enviar al backend
                const formData = new FormData(form);
                const payload = Object.fromEntries(formData.entries());

                return fetch('/paypal/create-payment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(payload)
                })
                .then(function(res) {
                    if (!res.ok) {
                        return res.json().then(error => Promise.reject(error));
                    }
                    return res.json();
                })
                .then(function(orderData) {
                    if (orderData.error) {
                        return Promise.reject(new Error(orderData.message)); // Lanza error si el backend falló
                    }
                    return orderData.id; // Retorna el ID de la orden de PayPal
                })
                .catch(function(err) {
                    console.error("Error creating order:", err);
                    alert("No se pudo iniciar el pago: " + (err.message || "Revisa la consola"));
                });
            },
            onApprove: function(data, actions) {
                return fetch('/paypal/capture-payment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        orderId: data.orderID
                    })
                })
                .then(function(res) {
                    if (!res.ok) {
                        return res.json().then(error => Promise.reject(error));
                    }
                    return res.json();
                })
                .then(function(details) {
                    if (details.redirect_url) {
                        window.location.href = details.redirect_url;
                    } else {
                        alert("Pago completado. Recargando...");
                        window.location.reload();
                    }
                })
                .catch(function(err) {
                    console.error("Error capturing order:", err);
                    alert("Error al procesar el pago: " + (err.message || "Intenta de nuevo"));
                });
            },
            onError: function (err) {
                console.error("PayPal onError:", err);
                // No mostrar alert intrusivo por errores menores, pero loguear
            }
        }).render('#paypal-button-container');
    } else {
        console.error("PayPal SDK no cargó. Verifica tu CLIENT_ID en el .env");
        const container = document.getElementById('paypal-button-container');
        if (container) {
            container.innerHTML = '<div style="color:red; padding:10px; border:1px solid red; background:#fff0f0; border-radius:5px;">Error: No se pudo cargar PayPal. Verifica la configuración.</div>';
        }
    }
</script>
@endpush