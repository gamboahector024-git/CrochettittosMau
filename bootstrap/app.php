<?php 

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

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

        // También puedes agregar globales o de grupo si los necesitas:
        // $middleware->web(prepend: [\App\Http\Middleware\OtroMiddleware::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
