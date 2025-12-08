<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfGuest
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            session()->put('url.intended', $request->url());
            return redirect()->route('login.form')
                   ->with('warning', 'Para continuar con tu compra, por favor inicia sesión o regístrate.');
        }

        return $next($request);
    }
}
