/**
 * Manejo del checkout y pago con PayPal usando paypal.Buttons
 * También incluye validación de formularios de tarjeta
 */

// Función para mostrar/ocultar el formulario de tarjeta
window.togglePaymentMethod = function(method) {
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
};

// Formatear número de tarjeta con espacios cada 4 dígitos
window.formatCardNumber = function(input) {
    let value = input.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
    let formatted = '';
    
    for (let i = 0; i < value.length; i++) {
        if (i > 0 && i % 4 === 0) {
            formatted += ' ';
        }
        formatted += value[i];
    }
    
    input.value = formatted.substring(0, 19); // Máximo 16 dígitos + 3 espacios
};

// Formatear fecha de vencimiento (MM/AA)
window.formatExpiryDate = function(input) {
    let value = input.value.replace(/\D/g, '');
    
    if (value.length >= 2) {
        input.value = value.substring(0, 2) + '/' + value.substring(2, 4);
    } else {
        input.value = value;
    }
};

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

// Inicialización principal
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar PayPal si está disponible
    initializePayPal();
    
    // Inicializar eventos de formulario
    initializePaymentForm();
    
    // Inicializar validaciones de tarjeta
    initializeCardValidations();
});

function initializePayPal() {
    const container = document.getElementById('paypal-button-container');
    if (!container || typeof paypal === 'undefined') {
        return;
    }

    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
    const csrf = tokenMeta ? tokenMeta.getAttribute('content') : '';

    paypal.Buttons({
        // Validamos la dirección y creamos la orden en el backend
        createOrder: function (data, actions) {
            const calleEl = document.getElementById('calle');
            const coloniaEl = document.getElementById('colonia');
            const municipioEl = document.getElementById('municipio_ciudad');
            const cpEl = document.getElementById('codigo_postal');
            const estadoEl = document.getElementById('estado');

            const calle = calleEl ? calleEl.value.trim() : '';
            const colonia = coloniaEl ? coloniaEl.value.trim() : '';
            const municipio_ciudad = municipioEl ? municipioEl.value.trim() : '';
            const codigo_postal = cpEl ? cpEl.value.trim() : '';
            const estado = estadoEl ? estadoEl.value.trim() : '';

            if (!calle || !colonia || !municipio_ciudad || !codigo_postal || !estado) {
                alert('Por favor completa la dirección antes de pagar con PayPal.');
                if (actions && typeof actions.reject === 'function') {
                    return actions.reject();
                }
                // Fallback: lanzar un error para cancelar la creación de la orden
                return Promise.reject(new Error('Dirección incompleta'));
            }

            return fetch('/paypal/create-payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    calle,
                    colonia,
                    municipio_ciudad,
                    codigo_postal,
                    estado
                })
            })
                .then(response => response.json().then(data => ({ ok: response.ok, data })))
                .then(({ ok, data }) => {
                    if (!ok || data.error) {
                        const message = (data && data.message) ? data.message : 'Error al crear la orden de PayPal';
                        alert(message);
                        throw new Error(message);
                    }

                    // El backend debe devolver el ID de la orden de PayPal
                    return data.id;
                });
        },
        onApprove: function (data, actions) {
            // Redirigimos al flujo de retorno del carrito
            window.location.href = '/paypal/return?token=' + data.orderID;
        },
        onCancel: function (data) {
            window.location.href = '/paypal/cancel';
        },
        onError: function (err) {
            console.error('Error en PayPal:', err);
            alert('Ocurrió un error al procesar el pago. Por favor intenta de nuevo.');
        },
        style: {
            layout: 'vertical',
            color: 'gold',
            shape: 'rect',
            label: 'pay'
        }
    }).render('#paypal-button-container');
}

function initializePaymentForm() {
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
    
    // Validar formulario al enviar
    const checkoutForm = document.getElementById('checkout-form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', validateCheckoutForm);
    }
}

function initializeCardValidations() {
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
}
