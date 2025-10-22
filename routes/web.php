<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Rutas principales
|--------------------------------------------------------------------------
*/

// ✅ Ruta raíz: al iniciar el servidor muestra directamente la tienda
Route::get('/', function () {
    return view('tienda');
})->name('inicio');

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

// Tienda (pública o con restricción según necesites)
Route::get('/tienda', function () {
    return view('tienda');
})->name('tienda');

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

// Panel principal del admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
    Route::get('/admin/productos', [AdminController::class, 'productos'])->name('admin.productos');
    Route::get('/admin/pedidos', [AdminController::class, 'pedidos'])->name('admin.pedidos');
});