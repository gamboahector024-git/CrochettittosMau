<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visita;

class RegistrarVisita
{
    public function handle(Request $request, Closure $next)
    {
        // Registrar visita solo si no es una ruta de admin
        if (!str_starts_with($request->path(), 'admin')) {
            Visita::create([
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
        }

        return $next($request);
    }
}
