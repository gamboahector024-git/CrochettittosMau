/**
 * Manejo del carrito de compras
 */
document.addEventListener('DOMContentLoaded', function() {
    // Manejar actualizaciones de cantidad
    const quantityForms = document.querySelectorAll('.quantity-form');
    
    quantityForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const quantityInput = this.querySelector('input[type="number"]');
            
            if (quantityInput && quantityInput.value < 1) {
                e.preventDefault();
                alert('La cantidad debe ser al menos 1');
                quantityInput.value = 1;
            }
        });
    });
});
