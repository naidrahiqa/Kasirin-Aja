<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     * Pastikan hanya Admin yang bisa lewat.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request);
        }

        // Kalau yang masuk kasir, tendang dan suruh balik ke halaman POS
        return redirect()->route('pos.index')->with('error', 'Akses ditolak. Anda bukan Admin!');
    }
}
