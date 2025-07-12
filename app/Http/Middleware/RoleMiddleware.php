<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }

        // Get the authenticated user
        $user = Auth::user();

        // Check if user has the required role
        if ($role === 'admin' && $user->role !== 1) {
             abort(403, 'Unauthorized.');
        }

        if ($role === 'komando' && $user->role === 1) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
