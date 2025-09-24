<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StripCsrfFromApi
{
    public function handle(Request $request, Closure $next)
    {
        // Remove CSRF-related headers if present
        $request->headers->remove('X-CSRF-TOKEN');
        $request->headers->remove('X-XSRF-TOKEN');

        // Ensure API returns JSON
        if (!$request->headers->has('Accept')) {
            $request->headers->set('Accept', 'application/json');
        }

        return $next($request);
    }
}


