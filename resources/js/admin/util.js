// Utilidades generales para el admin
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar funcionalidades comunes del admin
    initializeSelectAll();
    initializePasswordToggles();
});

// Funcionalidad de "Seleccionar todos" para checkboxes
function initializeSelectAll() {
    const selectAllCheckbox = document.getElementById('select-all');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    
    if (selectAllCheckbox && rowCheckboxes.length > 0) {
        selectAllCheckbox.addEventListener('change', function() {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
        
        // Si se deselecciona algún checkbox individual, deseleccionar "select-all"
        rowCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (!this.checked) {
                    selectAllCheckbox.checked = false;
                } else {
                    // Si todos están seleccionados, seleccionar "select-all"
                    const allChecked = Array.from(rowCheckboxes).every(cb => cb.checked);
                    selectAllCheckbox.checked = allChecked;
                }
            });
        });
    }
}

// Mostrar/ocultar contraseñas en formularios del admin
function initializePasswordToggles() {
    const toggleButtons = document.querySelectorAll('.toggle-password-btn');

    toggleButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            if (!input) return;

            const icon = this.querySelector('i');
            const isPassword = input.getAttribute('type') === 'password';

            if (isPassword) {
                input.setAttribute('type', 'text');
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.setAttribute('type', 'password');
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
}

// Confirmar acciones de eliminación
window.confirmDelete = function(message) {
    message = message || '¿Estás seguro de que quieres eliminar este elemento?';
    return confirm(message);
};

// Confirmar acciones masivas
window.confirmBulkAction = function(action, count) {
    const messages = {
        delete: `¿Estás seguro de que quieres eliminar ${count} elemento(s)?`,
        activate: `¿Activar ${count} elemento(s)?`,
        deactivate: `¿Desactivar ${count} elemento(s)?`
    };
    
    const message = messages[action] || `¿Realizar esta acción en ${count} elemento(s)?`;
    return confirm(message);
};