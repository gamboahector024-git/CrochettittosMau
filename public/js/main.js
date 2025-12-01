// Espera a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', () => {

    // Referencias a los modales
    const productModal = document.getElementById("productModal");
    const loginModal = document.getElementById("loginModal");

    // Función para abrir el modal del producto
    window.openModal = function (productId, name, price, description, imageUrl, categoryName, originalPrice, discountBadgeText) {
        if (!productModal) return;

        // Asignar valores a los elementos del modal
        document.getElementById("modalProductId").value = productId;
        document.getElementById("modalName").textContent = name;
        document.getElementById("modalPrice").textContent = '$' + price;
        document.getElementById("modalDescription").textContent = description;
        document.getElementById("modalImage").src = imageUrl;
        document.getElementById("modalCategory").textContent = categoryName ? `Categoría: ${categoryName}` : '';

        // Resetear cantidad a 1
        const quantityInput = document.getElementById("modalQuantity");
        if (quantityInput) {
            quantityInput.value = 1;
        }

        // Lógica para mostrar/ocultar precios y badge de descuento
        const originalPriceEl = document.getElementById("modalOriginalPrice");
        const discountBadgeEl = document.getElementById("modalDiscountBadge");

        if (originalPrice && originalPrice !== price) {
            originalPriceEl.textContent = '$' + originalPrice;
            originalPriceEl.style.display = 'inline';
        } else {
            originalPriceEl.style.display = 'none';
        }

        if (discountBadgeText) {
            discountBadgeEl.textContent = discountBadgeText;
            discountBadgeEl.style.display = 'inline-block';
        } else {
            discountBadgeEl.style.display = 'none';
        }

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

    // ========== NUEVO CÓDIGO PARA EL CARRITO ==========
    
    // Manejar el envío del formulario de agregar al carrito
    const addToCartForm = document.querySelector('.modal-form');
    
    if (addToCartForm) {
        addToCartForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const productoId = document.getElementById('modalProductId').value;
            const cantidad = document.getElementById('modalQuantity').value;
            
            console.log('Agregando al carrito:', { productoId, cantidad });
            
            // Crear FormData
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('id_producto', productoId);
            formData.append('cantidad', cantidad);
            
            // Enviar la solicitud
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (response.redirected) {
                    // Si hay redirección, seguirla
                    window.location.href = response.url;
                    return;
                }
                return response.json().catch(() => null);
            })
            .then(data => {
                if (data && data.redirect) {
                    window.location.href = data.redirect;
                }
            })
            .catch(error => {
                console.error('Error al agregar al carrito:', error);
                // Fallback: enviar el formulario de forma tradicional
                this.submit();
            });
        });
    }
    
    // También manejar clic en botones "Comprar" de las tarjetas
    const buyButtons = document.querySelectorAll('.buy-button');
    buyButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Solo procesar si no está dentro de un formulario de cantidad
            if (!this.closest('.quantity-form')) {
                e.preventDefault();
                const productId = this.dataset.productId;
                
                if (productId) {
                    // Crear formulario temporal para enviar
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("carrito.store") }}';
                    
                    const tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = '_token';
                    tokenInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    const productInput = document.createElement('input');
                    productInput.type = 'hidden';
                    productInput.name = 'id_producto';
                    productInput.value = productId;
                    
                    const quantityInput = document.createElement('input');
                    quantityInput.type = 'hidden';
                    quantityInput.name = 'cantidad';
                    quantityInput.value = 1;
                    
                    form.appendChild(tokenInput);
                    form.appendChild(productInput);
                    form.appendChild(quantityInput);
                    document.body.appendChild(form);
                    
                    form.submit();
                }
            }
        });
    });

    // Manejar cambio de cantidad en el modal
    const quantityInput = document.getElementById('modalQuantity');
    if (quantityInput) {
        quantityInput.addEventListener('change', function() {
            // Validar que sea mínimo 1
            if (this.value < 1) {
                this.value = 1;
            }
        });
    }

    // Debug para verificar que todo funciona
    console.log('Carrito system loaded successfully');
    console.log('Add to cart form found:', !!addToCartForm);
    console.log('Buy buttons found:', buyButtons.length);

});
document.addEventListener('DOMContentLoaded', function() {
    
    const menuBtn = document.getElementById('mobile-menu-btn');
    const navMenu = document.getElementById('nav-menu');

    if (menuBtn && navMenu) {
        menuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            navMenu.classList.toggle('active');
            
            // Cambiar icono
            const icon = menuBtn.querySelector('i');
            if (navMenu.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });

        // Cerrar al dar clic fuera
        document.addEventListener('click', function(e) {
            if (!navMenu.contains(e.target) && !menuBtn.contains(e.target)) {
                navMenu.classList.remove('active');
                const icon = menuBtn.querySelector('i');
                if(icon) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        });
    }
});