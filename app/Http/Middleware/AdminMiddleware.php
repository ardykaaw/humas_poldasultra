<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Akses tidak diizinkan. Anda bukan administrator.');
        }

        return $next($request);
    }
} 