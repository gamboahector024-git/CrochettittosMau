<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\{
    ProductoController,
    UsuarioController,
    PedidoController,
    CategoriaController,
    PeticionController,
    PromocionController,
    CarruselController,
    FaqController,
    PagoController
};
use App\Http\Controllers\Cliente\{
    CarritoController,
    TiendaController,
    PerfilController,
    PeticionController as ClientePeticionController,
    PedidoController as ClientePedidoController
};
use App\Http\Controllers\PayPalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie; // <-- AGREGAR ESTA LÍNEA

/*
|--------------------------------------------------------------------------
| Rutas principales
|--------------------------------------------------------------------------
*/

Route::middleware(['web', 'track-user-activity'])->group(function () {
    // Página principal y tienda
    Route::get('/', [TiendaController::class, 'index'])->name('tienda');
    Route::get('/tienda', [TiendaController::class, 'index']);

    // Página pública de FAQs
    Route::get('/faq', [\App\Http\Controllers\Cliente\FaqController::class, 'index'])->name('faq');

    // Autenticación
    Route::get('/login', [LoginController::class, 'mostrarLogin'])->name('login.form');
    Route::post('/login', [LoginController::class, 'procesarLogin'])->name('login.procesar');
    Route::get('/registro', [LoginController::class, 'mostrarRegistro'])->name('registro.form');
    Route::post('/registro', [LoginController::class, 'procesarRegistro'])->name('registro.guardar');
    Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Carrito (usuarios autenticados)
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth')->prefix('carrito')->name('carrito.')->group(function () {
        Route::get('/', [CarritoController::class, 'index'])->name('index');
        Route::post('/agregar', [CarritoController::class, 'store'])->name('store');
        Route::put('/actualizar/{detalle}', [CarritoController::class, 'update'])->name('update');
        Route::delete('/eliminar/{detalle}', [CarritoController::class, 'destroy'])->name('destroy');
        Route::delete('/vaciar', [CarritoController::class, 'clear'])->name('clear');
        Route::get('/checkout', [CarritoController::class, 'checkout'])->name('checkout');
        Route::post('/procesar', [CarritoController::class, 'procesarPedido'])->name('procesar');
    });

    // Webhook de PayPal (sin CSRF y sin auth)
    Route::post('/paypal/webhook', [PayPalController::class, 'webhook'])
        ->name('paypal.webhook')
        ->withoutMiddleware([ValidateCsrfToken::class]);

    // Webhook de Stripe (sin CSRF y sin auth)
    Route::post('/stripe/webhook', [PagoController::class, 'handleStripeWebhook'])
        ->name('stripe.webhook')
        ->withoutMiddleware([ValidateCsrfToken::class]);

    /*
    |--------------------------------------------------------------------------
    | Perfil de usuario
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth')->prefix('perfil')->name('perfil.')->group(function () {
        Route::get('/', [PerfilController::class, 'index'])->name('index');
        Route::get('/editar', [PerfilController::class, 'edit'])->name('edit');
        Route::put('/actualizar', [PerfilController::class, 'update'])->name('update');
    });

    // Peticiones personalizadas (usuarios autenticados)
    Route::middleware('auth')->group(function () {
        Route::post('/peticiones', [ClientePeticionController::class, 'store'])->name('peticiones.store');
    });

    // Rutas para que el cliente vea sus peticiones
    Route::middleware('auth')->prefix('mis-peticiones')->name('cliente.peticiones.')->group(function () {
        Route::get('/', [ClientePeticionController::class, 'index'])->name('index');
        Route::get('/{peticion}', [ClientePeticionController::class, 'show'])->name('show');
        Route::post('/{peticion}/rechazar', [ClientePeticionController::class, 'rechazar'])->name('rechazar');
    });

    /*
    |--------------------------------------------------------------------------
    | Pedidos del cliente
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth')->prefix('mis-pedidos')->name('cliente.pedidos.')->group(function () {
        Route::get('/', [ClientePedidoController::class, 'index'])->name('index');
        Route::get('/{pedido}', [ClientePedidoController::class, 'show'])->name('show');
    });


    /*
    |--------------------------------------------------------------------------
    | Pagos (PayPal y Stripe)
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth')->group(function () {
        // Stripe
        Route::post('/stripe/payment-intent', [PagoController::class, 'createStripePaymentIntent'])->name('stripe.payment-intent');

        // PayPal
        Route::post('/paypal/create-payment', [PayPalController::class, 'createPayment']);
        Route::post('/paypal/capture-payment', [PayPalController::class, 'capturePayment']);
        Route::get('/paypal/return', [PayPalController::class, 'handleReturn']);
        Route::get('/paypal/cancel', [PayPalController::class, 'handleCancel']);
        
        // Pago de peticiones personalizadas
        Route::post('/paypal/peticion/{id_peticion}/create-payment', [PayPalController::class, 'createPeticionPayment'])->name('paypal.peticion.create');
        Route::get('/paypal/peticion/return', [PayPalController::class, 'capturePeticionPayment'])->name('paypal.peticion.return');
        Route::get('/paypal/peticion/cancel', [PayPalController::class, 'cancelPeticionPayment'])->name('paypal.peticion.cancel');
    });

    /*
    |--------------------------------------------------------------------------
    | Panel de administración
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/dashboard/stats', [AdminController::class, 'dashboardStats'])->name('dashboard.stats');

        // Toggle del tema (modo oscuro) - VERSIÓN CON COOKIES
        Route::post('/theme/toggle', function (Request $request) {
            $currentTheme = Cookie::get('theme', 'light');
            $newTheme = $currentTheme === 'light' ? 'dark' : 'light';
            
            return back()->withCookie(cookie('theme', $newTheme, 60*24*30)); // 30 días
        })->name('theme.toggle');

        // Usuarios
        Route::resource('usuarios', UsuarioController::class)->except(['show']);
        Route::delete('usuarios/bulk-delete', [UsuarioController::class, 'bulkDelete'])->name('usuarios.bulk-delete');

        // Categorías
        Route::resource('categorias', CategoriaController::class)->except(['show']);
        Route::delete('categorias/bulk-delete', [CategoriaController::class, 'bulkDelete'])->name('categorias.bulk-delete');

        // Productos
        Route::resource('productos', ProductoController::class)->except(['show']);
        Route::delete('productos/bulk-delete', [ProductoController::class, 'bulkDelete'])->name('productos.bulk-delete');

        // Pedidos
        Route::resource('pedidos', PedidoController::class)->except(['create', 'store', 'destroy']);
        Route::delete('pedidos/bulk-delete', [PedidoController::class, 'bulkDelete'])->name('pedidos.bulk-delete');

        // Peticiones
        Route::resource('peticiones', PeticionController::class)
            ->only(['index', 'show', 'destroy'])
            ->parameters(['peticiones' => 'peticion']);
        Route::post('peticiones/{peticion}/toggle-status', [PeticionController::class, 'toggleStatus'])->name('peticiones.toggle-status');
        Route::post('peticiones/bulk-status', [PeticionController::class, 'bulkStatus'])->name('peticiones.bulk-status');
        Route::post('peticiones/bulk-delete', [PeticionController::class, 'bulkDelete'])->name('peticiones.bulk-delete');
        Route::post('peticiones/{peticion}/responder', [PeticionController::class, 'responder'])->name('peticiones.responder');
        Route::post('peticiones/{peticion}/completar', [PeticionController::class, 'completar'])->name('peticiones.completar');
        
        // Promociones
        Route::delete('promociones/bulk-delete', [PromocionController::class, 'bulkDelete'])->name('promociones.bulk-delete');
        Route::resource('promociones', PromocionController::class)
            ->parameters(['promociones' => 'promocion']);
        Route::post('promociones/{promocion}/toggle-status', [PromocionController::class, 'toggleStatus'])->name('promociones.toggle-status');
        
        // Carrusel
        Route::resource('carrusel', CarruselController::class)->except(['show']);
        Route::patch('carrusel/{carrusel}/toggle', [CarruselController::class, 'toggle'])->name('carrusel.toggle');

        // FAQs
        Route::resource('faqs', FaqController::class)->except(['show']);

    });
});
