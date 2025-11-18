/**
 * Manejo del checkout y pago con PayPal usando paypal.Buttons
 */
document.addEventListener('DOMContentLoaded', () => {
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
});
