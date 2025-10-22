// Espera a que todo el contenido del DOM esté cargado antes de ejecutar el script
document.addEventListener('DOMContentLoaded', function() {

    // Obtiene los elementos del DOM que vamos a necesitar
    const modal = document.getElementById("productModal");
    const closeButton = document.querySelector(".close-button");

    /**
     * Función global para abrir el modal y mostrar los detalles del producto.
     * Se asigna al objeto window para que pueda ser llamada desde el atributo onclick en el HTML.
     * @param {string} name - El nombre del producto.
     * @param {string} price - El precio del producto.
     * @param {string} description - La descripción del producto.
     * @param {string} imageUrl - La URL de la imagen del producto.
     */
    window.openModal = function(name, price, description, imageUrl) {
        // Verifica si el modal existe antes de intentar manipularlo
        if (modal) {
            // Rellena el contenido del modal con los datos del producto
            document.getElementById("modalName").textContent = name;
            document.getElementById("modalPrice").textContent = '$' + price;
            document.getElementById("modalDescription").textContent = description;
            document.getElementById("modalImage").src = imageUrl;
            
            // Muestra el modal
            modal.style.display = "block";
        }
    }

    // Evento para cerrar el modal al hacer clic en el botón (X)
    if (closeButton) {
        closeButton.onclick = function() {
            modal.style.display = "none";
        }
    }

    // Evento para cerrar el modal si el usuario hace clic fuera del contenido
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
});