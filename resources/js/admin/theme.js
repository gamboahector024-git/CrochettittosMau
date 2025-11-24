/**
 * Manejo del tema oscuro/claro en el panel de administración
 */

// Detectar preferencia del sistema
function detectSystemTheme() {
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        return 'dark';
    }
    return 'light';
}

// Aplicar tema al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = document.documentElement.getAttribute('data-theme');
    
    // Si no hay tema guardado, usar el del sistema
    if (!savedTheme) {
        const systemTheme = detectSystemTheme();
        document.documentElement.setAttribute('data-theme', systemTheme);
    }
    
    // Suavizar transiciones después de cargar
    setTimeout(() => {
        document.body.style.transition = 'all 0.3s ease';
    }, 100);
});

// Escuchar cambios en la preferencia del sistema
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
    const currentTheme = document.documentElement.getAttribute('data-theme');
    
    // Solo cambiar si el usuario no ha establecido una preferencia manual
    if (!currentTheme || currentTheme === 'auto') {
        const newTheme = e.matches ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', newTheme);
    }
});
