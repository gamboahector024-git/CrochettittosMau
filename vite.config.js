import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',

                'resources/css/admin.css',
                'resources/css/login.css',
                'resources/css/registro.css',
                'resources/css/faq.css',

                // JS cliente
                'resources/js/cliente/tienda-carousel.js',
                'resources/js/cliente/carrito.js',
                'resources/js/cliente/peticion-pago.js',

                // JS admin
                'resources/js/admin/layout.js',
                'resources/js/admin/promociones.js',
                // (si usas también admin/carrusel.js, admin/peticiones.js, admin/theme.js, podemos agregarlos después)
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
