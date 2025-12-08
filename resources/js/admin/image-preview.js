// Previsualización de imágenes en formularios del admin
document.addEventListener('DOMContentLoaded', function () {
    // Manejar todos los inputs de tipo file para imágenes
    const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    
    imageInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Buscar contenedor de previsualización asociado
                const previewContainer = input.parentElement.querySelector('.image-preview-container') ||
                                       input.closest('.form-group').querySelector('.image-preview-container') ||
                                       document.getElementById(input.id + '-preview');
                
                if (previewContainer) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewContainer.innerHTML = `
                            <img src="${e.target.result}" alt="Vista previa" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #e2e8f0;">
                        `;
                        previewContainer.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            }
        });
    });
    
    // Limpiar previsualización cuando se resetea el formulario
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('reset', function() {
            const previews = form.querySelectorAll('.image-preview-container');
            previews.forEach(preview => {
                preview.innerHTML = '';
                preview.style.display = 'none';
            });
        });
    });
});
