<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if user is authenticated and has the required role
        if (!$request->user() || $request->user()->role !== $role) {
            abort(403, 'Unauthorized action. Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
