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
                'resources/js/cliente/peticion-show.js',
                'resources/js/cliente/faq.js',
                'resources/js/cliente/checkout.js',

                // JS admin
                'resources/js/admin/layout.js',
                'resources/js/admin/promociones.js',
                'resources/js/admin/tema-admin.js',
                'resources/js/admin/theme.js',
                'resources/js/admin/image-preview.js',
                'resources/js/admin/dashboard.js',
                'resources/js/admin/peticiones.js',
                'resources/js/admin/carrusel.js',
                'resources/js/admin/productos.js',
                'resources/js/admin/util.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
