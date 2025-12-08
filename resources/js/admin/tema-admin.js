// Tema del panel de administración (modo claro/oscuro)
document.addEventListener('DOMContentLoaded', function () {
    // Aplicar tema desde cookie al cargar la página
    const theme = getCookie('theme') || 'light';
    document.documentElement.setAttribute('data-theme', theme);
    
    // Toggle del tema
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Enviar formulario para cambiar tema
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = themeToggle.href;
            
            const token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            form.appendChild(token);
            document.body.appendChild(form);
            form.submit();
        });
    }
});

// Función auxiliar para obtener cookies
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}
