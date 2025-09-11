<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // For now, we'll allow all authenticated users to access admin routes
        // In a real application, you would check for admin role/permissions
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // You can add role checking here
        // if (!auth()->user()->is_admin) {
        //     abort(403, 'Access denied');
        // }

        return $next($request);
    }
}
