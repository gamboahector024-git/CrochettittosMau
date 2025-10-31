<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\{
    ProductoController,
    UsuarioController,
    PedidoController,
    CategoriaController,
    PeticionController,
    PromocionController
};
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\TiendaController;

/*
|--------------------------------------------------------------------------
| Rutas principales (públicas)
|--------------------------------------------------------------------------
*/

// Página principal y tienda
Route::get('/', [TiendaController::class, 'index'])->name('inicio');
Route::get('/tienda', [TiendaController::class, 'index'])->name('tienda');

// Autenticación
Route::get('/login', [LoginController::class, 'mostrarLogin'])->name('login.form');
Route::post('/login', [LoginController::class, 'procesarLogin'])->name('login.procesar');
Route::get('/registro', [LoginController::class, 'mostrarRegistro'])->name('registro.form');
Route::post('/registro', [LoginController::class, 'procesarRegistro'])->name('registro.guardar');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

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
});

/*
|--------------------------------------------------------------------------
| Panel de administración
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

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
    Route::resource('promociones', PromocionController::class);
    Route::post('promociones/{promocion}/toggle-status', [PromocionController::class, 'toggleStatus'])->name('promociones.toggle-status');
    Route::delete('promociones/bulk-delete', [PromocionController::class, 'bulkDelete'])->name('promociones.bulk-delete');
});
