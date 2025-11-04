<?php

namespace App\Http\Middleware;

use App\Models\Usuario;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            // No-op: removed last_activity / is_online updates
        }

        return $next($request);
    }
}