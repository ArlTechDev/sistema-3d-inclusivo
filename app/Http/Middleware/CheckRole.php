<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $roles)
    {
        $allowedRoles = array_map('trim', explode(',', $roles));

        if (! auth()->check() || ! in_array(auth()->user()->rol, $allowedRoles, true)) {
            abort(403, 'Acceso denegado');
        }

        return $next($request);
    }
}
