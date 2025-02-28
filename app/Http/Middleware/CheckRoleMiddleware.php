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
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Cek apakah user mengakses route "admin"
        if ($request->is('admin/*') && $user->role !== 'admin') {
            abort(403, 'Unauthorized - You are not an admin');
        }

        // Cek apakah user mengakses route "petugas"
        if ($request->is('petugas/*') && $user->role !== 'petugas') {
            abort(403, 'Unauthorized - You are not a petugas');
        }

        return $next($request);

        // if (!Auth::check()) {
        //     return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        // }

        // $user = Auth::user();

        // if (Str::startsWith($request->path(), 'admin') && $user->role !== 'admin') {
        //     return redirect('/')->with('error', 'Anda tidak memiliki akses sebagai admin.');
        // }

        // if (Str::startsWith($request->path(), 'petugas') && $user->role !== 'petugas') {
        //     return redirect('/')->with('error', 'Anda tidak memiliki akses sebagai petugas.');
        // }

        // return $next($request);
    }
}
