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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$slugs): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // hapa $slugs ni array ya roles ulioweka kwenye middleware, mfano: ['admin'] au ['admin', 'teacher']
        if (!$user->hasRole($slugs)) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
