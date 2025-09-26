<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminSessionMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if admin is logged in
        if (!Session::has('admin_logged_in')) {
            // Return JSON for API/AJAX requests
            if ($request->expectsJson() || $request->ajax() || str_contains((string) $request->header('Accept'), 'application/json')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login to access admin panel.'
                ], 401);
            }
            return redirect('/admin/login')->with('error', 'Please login to access admin panel.');
        }

        return $next($request);
    }
}
