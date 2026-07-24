<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // <--- TAMBAHKAN INI

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Ubah auth()->check() dan auth()->user() menjadi Auth::
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}