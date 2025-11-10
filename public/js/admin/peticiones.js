// Admin peticiones functionality
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
