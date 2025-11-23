/**
 * Admin peticiones functionality
 */

// Seleccionar/deseleccionar todos los checkboxes
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('select-all');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            document.querySelectorAll('input.row-checkbox').forEach(cb => {
                cb.checked = selectAll.checked;
            });
        });
    }
});

/**
 * Eliminar una petición individual
 */
function submitRowDelete(url) {
    if (!confirm('¿Eliminar esta petición?')) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;
    
    const token = document.createElement('input');
    token.type = 'hidden';
    token.name = '_token';
    token.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    const method = document.createElement('input');
    method.type = 'hidden';
    method.name = '_method';
    method.value = 'DELETE';
    
    form.appendChild(token);
    form.appendChild(method);
    document.body.appendChild(form);
    form.submit();
}
