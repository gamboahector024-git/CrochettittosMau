// Admin promociones functionality
function filtrarPromociones() {
    const filtro = document.getElementById('filtro-promociones').value;
    window.location.href = `/admin/promociones?filtro=${filtro}`;
}

document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.promocion-checkbox');
    const bulkBtn = document.getElementById('bulk-delete-btn');
    const bulkForm = document.getElementById('bulk-delete-form');
    const bulkIds = document.getElementById('bulk-delete-ids');

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            toggleBulkButton();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', toggleBulkButton);
    });

    function toggleBulkButton() {
        const checked = document.querySelectorAll('.promocion-checkbox:checked');
        if (bulkBtn) {
            bulkBtn.style.display = checked.length > 0 ? 'inline-block' : 'none';
            bulkBtn.textContent = `Eliminar ${checked.length}`;
        }
    }

    if (bulkBtn) {
        bulkBtn.addEventListener('click', function() {
            const checked = document.querySelectorAll('.promocion-checkbox:checked');
            if (checked.length === 0) return;
            
            if (confirm(`¿Eliminar ${checked.length} promoción(es)?`)) {
                const ids = Array.from(checked).map(cb => cb.value);
                bulkIds.value = JSON.stringify(ids);
                bulkForm.submit();
            }
        });
    }
});
