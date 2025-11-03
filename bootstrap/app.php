<?php 

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request; // <-- 1. AÑADE ESTA LÍNEA AL INICIO

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
         /**
          * Aquí se registran tus middlewares personalizados
          */

         // Registrar alias de middleware
         $middleware->alias([
             'admin' => \App\Http\Middleware\VerificarAdmin::class,
         ]);

         // --- 2. AÑADE ESTA LÍNEA AQUÍ ---
         // Esto le dice a Laravel que tu ruta de login se llama 'login.form'
         $middleware->redirectGuestsTo(fn (Request $request) => route('login.form'));
         // ---------------------------------

    })
    ->withExceptions(function (Exceptions $exceptions): void {
         //
    })
    ->create();