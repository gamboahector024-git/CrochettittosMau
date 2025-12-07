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
                        <label for="metodo_pago">Selecciona cómo deseas pagar</label>
                        <div class="select-wrapper">
                            <select class="form-input" id="metodo_pago" name="metodo_pago" required>
                                <option value="">-- Seleccione una opción --</option>
                                <option value="efectivo">💵 Efectivo (Contra entrega)</option>
                                <option value="transferencia">🏦 Transferencia Bancaria</option>
                                <option value="tarjeta">💳 Tarjeta de Crédito / Débito</option>
                                <option value="paypal">🅿️ PayPal</option>
                            </select>
                        </div>
                    </div>

                    {{-- FORMULARIO DE TARJETA (Oculto por defecto) --}}
                    <div id="card-form-container" class="card-form-hidden">
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
                    </div>

                    {{-- Acciones --}}
                    <div class="form-actions" style="margin-top: 1.5rem;">
                        <button type="submit" id="btn-confirmar" class="checkout-button full-width">
                            Confirmar Compra <i class="fas fa-check"></i>
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
<script>
// Función para mostrar/ocultar el formulario de tarjeta
function togglePaymentMethod(method) {
    const cardForm = document.getElementById('card-form-container');
    const paypalContainer = document.getElementById('paypal-button-container');
    const confirmButton = document.getElementById('btn-confirmar');
    
    // Ocultar todo primero
    if (cardForm) cardForm.style.display = 'none';
    if (paypalContainer) paypalContainer.style.display = 'none';
    if (confirmButton) confirmButton.style.display = 'block';
    
    // Mostrar según el método seleccionado
    if (method === 'tarjeta') {
        if (cardForm) {
            cardForm.style.display = 'block';
            
            // Hacer campos obligatorios solo cuando se selecciona tarjeta
            const cardFields = ['card_name', 'card_number', 'card_expiry', 'card_cvv'];
            cardFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) field.required = true;
            });
        }
    } else if (method === 'paypal') {
        if (paypalContainer) paypalContainer.style.display = 'block';
        if (confirmButton) confirmButton.style.display = 'none';
    } else {
        // Para otros métodos (efectivo, transferencia), los campos de tarjeta no son obligatorios
        const cardFields = ['card_name', 'card_number', 'card_expiry', 'card_cvv'];
        cardFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.required = false;
                field.value = ''; // Limpiar valores si se cambia de método
            }
        });
    }
}

// Formatear número de tarjeta con espacios cada 4 dígitos
function formatCardNumber(input) {
    let value = input.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
    let formatted = '';
    
    for (let i = 0; i < value.length; i++) {
        if (i > 0 && i % 4 === 0) {
            formatted += ' ';
        }
        formatted += value[i];
    }
    
    input.value = formatted.substring(0, 19); // Máximo 16 dígitos + 3 espacios
}

// Formatear fecha de vencimiento (MM/AA)
function formatExpiryDate(input) {
    let value = input.value.replace(/\D/g, '');
    
    if (value.length >= 2) {
        input.value = value.substring(0, 2) + '/' + value.substring(2, 4);
    } else {
        input.value = value;
    }
}

// Validar fecha de vencimiento
function validateExpiryDate(value) {
    const regex = /^(0[1-9]|1[0-2])\/([0-9]{2})$/;
    if (!regex.test(value)) return false;
    
    const [month, year] = value.split('/').map(Number);
    const currentDate = new Date();
    const currentYear = currentDate.getFullYear() % 100;
    const currentMonth = currentDate.getMonth() + 1;
    
    // La tarjeta está vencida si el año es menor al actual
    // O si es el mismo año pero el mes ya pasó
    if (year < currentYear) return false;
    if (year === currentYear && month < currentMonth) return false;
    
    return true;
}

// Validar formulario antes de enviar
function validateCheckoutForm(event) {
    const paymentMethod = document.getElementById('metodo_pago').value;
    const cardForm = document.getElementById('card-form-container');
    
    // Si se seleccionó tarjeta, validar campos
    if (paymentMethod === 'tarjeta') {
        const cardFields = ['card_name', 'card_number', 'card_expiry', 'card_cvv'];
        let isValid = true;
        let errorMessage = '';
        
        // Validar cada campo
        cardFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field && !field.value.trim()) {
                isValid = false;
                field.style.borderColor = '#ff4757';
                errorMessage = 'Por favor, complete todos los datos de la tarjeta.';
            } else if (field) {
                field.style.borderColor = '';
            }
        });
        
        // Validar formato de número de tarjeta (debe tener al menos 13 dígitos)
        const cardNumber = document.getElementById('card_number');
        if (cardNumber && cardNumber.value) {
            const cleanNumber = cardNumber.value.replace(/\s/g, '');
            if (cleanNumber.length < 13 || cleanNumber.length > 16) {
                isValid = false;
                cardNumber.style.borderColor = '#ff4757';
                errorMessage = 'El número de tarjeta debe tener entre 13 y 16 dígitos.';
            }
        }
        
        // Validar formato de fecha (MM/AA) y que no esté vencida
        const expiryField = document.getElementById('card_expiry');
        if (expiryField && expiryField.value) {
            if (!validateExpiryDate(expiryField.value)) {
                isValid = false;
                expiryField.style.borderColor = '#ff4757';
                errorMessage = 'Fecha de vencimiento inválida o tarjeta vencida. Use MM/AA (ej: 12/25)';
            }
        }
        
        // Validar CVV (3 o 4 dígitos)
        const cvvField = document.getElementById('card_cvv');
        if (cvvField && cvvField.value) {
            const cvvRegex = /^\d{3,4}$/;
            if (!cvvRegex.test(cvvField.value)) {
                isValid = false;
                cvvField.style.borderColor = '#ff4757';
                errorMessage = 'El CVV debe tener 3 o 4 dígitos.';
            }
        }
        
        if (!isValid) {
            event.preventDefault();
            if (errorMessage) {
                alert(errorMessage);
            }
            return false;
        }
    }
    
    return true;
}

// Inicializar eventos cuando el DOM esté cargado
document.addEventListener('DOMContentLoaded', function() {
    // Evento para el select de método de pago
    const paymentSelect = document.getElementById('metodo_pago');
    if (paymentSelect) {
        paymentSelect.addEventListener('change', function() {
            togglePaymentMethod(this.value);
        });
        
        // Si ya hay un valor seleccionado (por ejemplo, al recargar la página)
        if (paymentSelect.value) {
            togglePaymentMethod(paymentSelect.value);
        }
    }
    
    // Eventos para formatear campos de tarjeta
    const cardNumberInput = document.getElementById('card_number');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function() {
            formatCardNumber(this);
        });
    }
    
    const expiryInput = document.getElementById('card_expiry');
    if (expiryInput) {
        expiryInput.addEventListener('input', function() {
            formatExpiryDate(this);
        });
    }
    
    // Solo permitir números en CVV
    const cvvInput = document.getElementById('card_cvv');
    if (cvvInput) {
        cvvInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').substring(0, 4);
        });
    }
    
    // Solo permitir letras y espacios en nombre del titular
    const cardNameInput = document.getElementById('card_name');
    if (cardNameInput) {
        cardNameInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^A-Za-zñÑ\s]/g, '');
        });
    }
    
    // Validar formulario al enviar
    const checkoutForm = document.getElementById('checkout-form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', validateCheckoutForm);
    }
});
</script>
@endpush