// Funcionalidades para la página de mostrar petición del cliente
document.addEventListener('DOMContentLoaded', function () {
    // Inicializar funcionalidades de pago de petición
    initializePeticionPayment();
});

// Función para inicializar el pago de petición (llamada desde la vista con configuración)
window.initializePeticionShowPage = function(config) {
    // Inicializar PayPal si está configurado
    if (window.initPeticionPayPalConfig && config) {
        window.initPeticionPayPalConfig(config);
    }
    
    // Inicializar controles de métodos de pago
    initializePeticionPaymentControls();
    
    // Inicializar validaciones de formulario de tarjeta
    initializePeticionCardValidations();
};

function initializePeticionPayment() {
    // Solo inicializar si no se ha configurado desde la vista
    if (!window.peticionConfigInitialized) {
        initializePeticionPaymentControls();
        initializePeticionCardValidations();
    }
}

function initializePeticionPaymentControls() {
    const metodoSelect = document.getElementById('peticion_metodo_pago');
    const cardForm = document.getElementById('peticion-card-form');
    const paypalContainer = document.getElementById('peticion-paypal-container');

    function togglePeticionMetodoPago(method) {
        if (cardForm) cardForm.style.display = 'none';
        if (paypalContainer) paypalContainer.style.display = 'none';

        if (method === 'tarjeta') {
            if (cardForm) cardForm.style.display = 'block';
        } else if (method === 'paypal') {
            if (paypalContainer) paypalContainer.style.display = 'block';
        }
    }

    if (metodoSelect) {
        metodoSelect.addEventListener('change', function () {
            togglePeticionMetodoPago(this.value);
        });
    }
    
    // Hacer la función global para acceso desde vistas
    window.togglePeticionMetodoPago = togglePeticionMetodoPago;
}

function initializePeticionCardValidations() {
    // Formateo básico para número, fecha y CVV como en checkout
    const cardNumberInput = document.getElementById('peticion_card_number');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function () {
            let value = this.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let formatted = '';
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) formatted += ' ';
                formatted += value[i];
            }
            this.value = formatted.substring(0, 19);
        });
    }

    const expiryInput = document.getElementById('peticion_card_expiry');
    if (expiryInput) {
        expiryInput.addEventListener('input', function () {
            let value = this.value.replace(/\D/g, '');
            if (value.length >= 2) {
                this.value = value.substring(0, 2) + '/' + value.substring(2, 4);
            } else {
                this.value = value;
            }
        });
    }

    const cvvInput = document.getElementById('peticion_card_cvv');
    if (cvvInput) {
        cvvInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').substring(0, 4);
        });
    }
}
