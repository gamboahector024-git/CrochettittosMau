<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarAdmin
{
    /**
     * Maneja la solicitud entrante.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Obtener usuario autenticado usando el guard 'web'
        $usuario = auth('web')->user();

        // Si no está autenticado o no es admin, redirige a la ruta correcta
        if (!$usuario || $usuario->rol !== 'admin') {
            return redirect()->route('login.form') // <-- usar login.form, NO login
                             ->with('error', 'Acceso denegado. Solo administradores.');
        }

        // Si pasa la validación, continúa
        return $next($request);
    }
}
