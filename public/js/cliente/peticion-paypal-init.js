/**
 * Inicialización de PayPal para peticiones personalizadas
 * Este archivo recibe los datos de configuración desde el blade
 */

window.initPeticionPayPalConfig = function(config) {
    if (typeof initPeticionPayPal === 'function') {
        initPeticionPayPal(
            config.peticionId,
            config.createUrl,
            config.returnUrl,
            config.cancelUrl,
            config.csrfToken
        );
    } else {
        console.error('La función initPeticionPayPal no está disponible. Asegúrate de cargar peticion-pago.js primero.');
    }
};
