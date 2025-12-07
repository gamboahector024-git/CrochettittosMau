document.addEventListener('DOMContentLoaded', function () {
    // ==========================================
    // 1. LÓGICA DEL MODAL (Tu código original mejorado)
    // ==========================================
    const btns = document.querySelectorAll('#newPeticionButton'); 
    const modal = document.getElementById('peticionModal');
    const close = document.getElementById('closePeticionModal');
    const cancel = document.getElementById('cancelPeticion');

    function openModal(e) {
        if(e) e.preventDefault();
        if(modal) { 
            modal.style.display = 'flex'; 
        }
    }

    function closeModal() {
        if(modal) { 
            modal.style.display = 'none'; 
            // AGREGADO: Limpiamos la imagen si el usuario cierra el modal sin enviar
            removeImage(); 
        }
    }

    // Abrir modal (Mantenemos tu lógica para múltiples botones)
    if (btns.length > 0 && modal) {
        btns.forEach(function(btn) {
            btn.addEventListener('click', openModal);
        });
    }

    // Cerrar modal
    if (close) close.addEventListener('click', closeModal); 
    if (cancel) cancel.addEventListener('click', closeModal); 

    // Cerrar al hacer clic fuera
    window.addEventListener('click', function (e) {
        if (e.target === modal) { 
            closeModal(); 
        }
        // Manejar también el modal de login si existe
        const loginModal = document.getElementById('loginModal');
        if (loginModal && e.target === loginModal) { 
            loginModal.style.display = 'none'; 
        }
    });

    // ==========================================
    // 2. LÓGICA DE IMAGEN (DRAG & DROP) - NUEVO
    // ==========================================
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('imagen_referencia');
    const previewContainer = document.getElementById('previewContainer');
    const imagePreviewImg = document.getElementById('imagePreview');
    const removeImageBtn = document.getElementById('removeImageBtn');
    const fileNameText = document.getElementById('fileNameText');
    const fileSizeText = document.getElementById('fileSizeText');

    // Solo ejecutamos si existen los elementos en el HTML
    if (dropZone && fileInput && previewContainer) {

        // A. CONECTAR CLICK: Al hacer clic en la zona, abrimos el input oculto
        dropZone.addEventListener('click', () => fileInput.click());

        // B. DETECTAR CAMBIO: Cuando el usuario selecciona un archivo manual
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                showPreview(this.files[0]);
            }
        });

        // C. BOTÓN ELIMINAR: Borra la imagen seleccionada
        if (removeImageBtn) {
            removeImageBtn.addEventListener('click', (e) => {
                e.stopPropagation(); // Evita que el clic llegue al dropZone
                removeImage();
            });
        }

        // D. DRAG & DROP: Eventos de arrastrar
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        // Efecto visual al pasar por encima
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.add('drag-over'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.remove('drag-over'), false);
        });

        // Cuando sueltan el archivo
        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                fileInput.files = files; // Pasamos los archivos al input real
                showPreview(files[0]);
            }
        }
    }

    // ==========================================
    // 3. FUNCIONES DE AYUDA
    // ==========================================
    
    // Muestra la previsualización
    function showPreview(file) {
        // Validar que sea imagen
        if (!file.type.startsWith('image/')) {
            alert('Por favor selecciona solo archivos de imagen (JPG, PNG).');
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            // Ponemos la imagen en el src
            imagePreviewImg.src = e.target.result;
            
            // Ponemos nombre y peso
            if(fileNameText) fileNameText.textContent = file.name;
            if(fileSizeText) fileSizeText.textContent = (file.size / 1024).toFixed(2) + ' KB';

            // Ocultamos zona de carga, mostramos preview
            dropZone.style.display = 'none';
            previewContainer.classList.add('active');
        }
        reader.readAsDataURL(file);
    }

    // Resetea todo (se usa al dar click en borrar o al cerrar modal)
    function removeImage() {
        if (!fileInput) return;
        
        fileInput.value = ''; // Limpia el input
        
        if (previewContainer) previewContainer.classList.remove('active');
        if (dropZone) dropZone.style.display = 'flex';
        if (imagePreviewImg) imagePreviewImg.src = '';
    }
});