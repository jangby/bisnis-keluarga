<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsStaff
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah user sudah login?
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 2. Cek apakah role-nya 'pelanggan'?
        // Jika YA, tendang balik ke halaman menu
        if (Auth::user()->role === 'pelanggan') {
            return redirect()->route('front.index'); 
        }

        // 3. Jika bukan pelanggan (Owner/Staf), silakan lewat
        return $next($request);
    }
}