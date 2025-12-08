document.addEventListener('DOMContentLoaded', function() {

    // ==========================================
    // 1. LÓGICA DEL MENÚ MÓVIL (SIDEBAR)
    // ==========================================
    const mobileBtn = document.getElementById('btn-toggle');
    const sidebar = document.getElementById('sidebar');

    if(mobileBtn && sidebar) {
        // Al dar clic en el botón hamburguesa
        mobileBtn.addEventListener('click', function(e) {
            e.stopPropagation(); // Evita clics fantasmas
            sidebar.classList.toggle('mobile-open');
        });

        // Cerrar menú automáticamente al hacer clic fuera de él (Solo en móvil)
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) { 
                // Si el clic NO fue en el sidebar NI en el botón
                if (!sidebar.contains(e.target) && !mobileBtn.contains(e.target)) {
                    sidebar.classList.remove('mobile-open');
                }
            }
        });
    }

    // ==========================================
    // 2. AUTO-CERRAR ALERTAS (Notificaciones)
    // ==========================================
    const alerts = document.querySelectorAll('.alert');
    if (alerts.length > 0) {
        setTimeout(() => {
            alerts.forEach(alert => {
                alert.style.transition = "opacity 0.5s ease";
                alert.style.opacity = '0'; // Desvanecer
                setTimeout(() => alert.remove(), 500); // Eliminar del HTML
            });
        }, 5000); // Esperar 5 segundos antes de borrar
    }
});