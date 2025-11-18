/**
 * Manejo del modal de peticiones personalizadas
 */
document.addEventListener('DOMContentLoaded', function () {
    const btns = document.querySelectorAll('#newPeticionButton'); 
    const modal = document.getElementById('peticionModal');
    const close = document.getElementById('closePeticionModal');
    const cancel = document.getElementById('cancelPeticion');

    function openModal(e) {
        e.preventDefault();
        if(modal) { 
            modal.style.display = 'flex'; 
        }
    }

    function closeModal() {
        if(modal) { 
            modal.style.display = 'none'; 
        }
    }

    // Abrir modal
    if (btns.length > 0 && modal) {
        btns.forEach(function(btn) {
            btn.addEventListener('click', openModal);
        });
    }

    // Cerrar modal
    if (close) { 
        close.addEventListener('click', closeModal); 
    }
    if (cancel) { 
        cancel.addEventListener('click', closeModal); 
    }

    // Cerrar al hacer clic fuera del modal
    window.addEventListener('click', function (e) {
        if (e.target === modal) { 
            closeModal(); 
        }
        
        // También manejar el modal de login si existe
        const loginModal = document.getElementById('loginModal');
        if (e.target === loginModal) { 
            loginModal.style.display = 'none'; 
        }
    });
});
