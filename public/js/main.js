// Espera a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', () => {

    // Referencias a los modales
    const productModal = document.getElementById("productModal");
    const loginModal = document.getElementById("loginModal");

    // Función para abrir el modal del producto
    window.openModal = function (name, price, description, imageUrl) {
        if (!productModal) return;

        document.getElementById("modalName").textContent = name;
        document.getElementById("modalPrice").textContent = '$' + price;
        document.getElementById("modalDescription").textContent = description;
        document.getElementById("modalImage").src = imageUrl;

        openModalWindow(productModal);
    };

    // Función para abrir el modal de login
    window.openLoginModal = function () {
        if (!loginModal) return;
        openModalWindow(loginModal);
    };

    // Función genérica para abrir un modal
    function openModalWindow(modal) {
        modal.style.display = "block";
        document.body.style.overflow = "hidden"; // Evita el scroll de fondo
    }

    // Función genérica para cerrar un modal
    function closeModalWindow(modal) {
        modal.style.display = "none";
        document.body.style.overflow = "auto"; // Restaura el scroll
    }

    // Cerrar modales al hacer clic en el botón con clase "close-button"
    document.querySelectorAll(".close-button").forEach(btn => {
        btn.addEventListener("click", (e) => {
            const modal = e.target.closest(".modal");
            if (modal) closeModalWindow(modal);
        });
    });

    // Cerrar al hacer clic fuera del contenido
    window.addEventListener("click", (event) => {
        if (event.target.classList.contains("modal")) {
            closeModalWindow(event.target);
        }
    });

    // Cerrar con tecla Escape
    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape") {
            [productModal, loginModal].forEach(modal => {
                if (modal && modal.style.display === "block") {
                    closeModalWindow(modal);
                }
            });
        }
    });

});
