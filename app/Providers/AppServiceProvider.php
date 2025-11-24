<?php

namespace App\Providers;

use App\Models\Carrito;
use App\Models\Categoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::aliasMiddleware('track-user-activity', \App\Http\Middleware\TrackUserActivity::class);

        View::composer('layouts.cliente', function ($view) {
            $carritoCount = 0;
            if (Auth::check()) {
                $carrito = Carrito::where('id_usuario', Auth::user()->id_usuario)->first();
                $carritoCount = $carrito ? $carrito->detalles->sum('cantidad') : 0;
            }

            $categorias = Categoria::orderBy('nombre')->get();

            $view->with('carritoCount', $carritoCount)->with('categoriasGlobal', $categorias);
        });
    }
}
