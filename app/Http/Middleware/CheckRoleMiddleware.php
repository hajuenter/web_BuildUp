<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil role user yang sedang login
        $user = Auth::user();

        // Cek apakah user memiliki role "admin" atau "petugas"
        if (!$user || !in_array($user->role, ['admin', 'petugas'])) {
            abort(403, 'Unauthorized'); // Jika bukan admin/petugas, tolak akses
        }
        return $next($request);
    }
}
