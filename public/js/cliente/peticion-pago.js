/**
 * Manejo de pago de peticiones con PayPal
 */
function initPeticionPayPal(peticionId, createUrl, returnUrl, cancelUrl, csrfToken) {
    paypal.Buttons({
        createOrder: function(data, actions) {
            return fetch(createUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.message);
                    throw new Error(data.message);
                }
                return data.id;
            });
        },
        onApprove: function(data, actions) {
            window.location.href = returnUrl + '?token=' + data.orderID;
        },
        onCancel: function(data) {
            window.location.href = cancelUrl;
        },
        onError: function(err) {
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
