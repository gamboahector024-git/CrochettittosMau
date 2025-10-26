<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\PedidoController;
use App\Http\Controllers\Admin\CategoriaController;
use App\Http\Controllers\TiendaController;

/*
|--------------------------------------------------------------------------
| Rutas principales
|--------------------------------------------------------------------------
*/

// ✅ Ruta raíz: al iniciar el servidor muestra directamente la tienda
Route::get('/', [TiendaController::class, 'index'])->name('inicio');

/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/

// Login
Route::get('/login', [LoginController::class, 'mostrarLogin'])->name('login.form');
Route::post('/login', [LoginController::class, 'procesarLogin'])->name('login.procesar');

// Registro
Route::get('/registro', [LoginController::class, 'mostrarRegistro'])->name('registro.form');
Route::post('/registro', [LoginController::class, 'procesarRegistro'])->name('registro.guardar');

// Tienda (pública)
Route::get('/tienda', [TiendaController::class, 'index'])->name('tienda');

/*
|--------------------------------------------------------------------------
| Rutas protegidas (requieren sesión)
|--------------------------------------------------------------------------
*/

// Cerrar sesión
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Panel de administrador
|--------------------------------------------------------------------------
*/

// Rutas de Carrito (protegidas)
Route::middleware('auth')->prefix('carrito')->name('carrito.')->group(function () {
    Route::get('/', [CarritoController::class, 'index'])->name('index');
    Route::post('/agregar', [CarritoController::class, 'store'])->name('store');
    Route::put('/actualizar/{detalle}', [CarritoController::class, 'update'])->name('update');
    Route::delete('/eliminar/{detalle}', [CarritoController::class, 'destroy'])->name('destroy');
    Route::delete('/vaciar', [CarritoController::class, 'clear'])->name('clear');
});


// Panel principal del admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('usuarios', UsuarioController::class)->except(['show']);
    Route::resource('categorias', CategoriaController::class)->except(['show']);
    Route::resource('productos', ProductoController::class)->except(['show']);
    Route::resource('pedidos', PedidoController::class)->except(['create', 'store', 'destroy']);
});