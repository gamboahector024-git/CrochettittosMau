// Funciones para la gestión de productos en el admin
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar previsualización de imágenes en todas las páginas de productos
    initializeImagePreviews();
});

// Función para previsualizar imagen (llamada desde onclick en las vistas)
window.previewImage = function(input) {
    const preview = document.getElementById('imagePreview');
    const placeholder = document.getElementById('uploadPlaceholder');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            if (preview) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            if (placeholder) {
                placeholder.style.display = 'none';
            }
        };

        reader.readAsDataURL(input.files[0]);
    } else {
        // Si no hay archivo, mostrar placeholder y ocultar preview
        if (preview) {
            preview.style.display = 'none';
        }
        if (placeholder) {
            placeholder.style.display = 'block';
        }
    }
};

// Inicializar previsualizaciones automáticas para inputs que no usen onclick
function initializeImagePreviews() {
    const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    
    imageInputs.forEach(input => {
        // Solo agregar evento si no tiene onclick definido
        if (!input.hasAttribute('onclick')) {
            input.addEventListener('change', function() {
                previewImage(this);
            });
        }
    });
}
