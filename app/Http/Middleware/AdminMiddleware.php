<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Sekarang PHP tahu apa itu 'Auth'
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }
        return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses admin.');
    }
}