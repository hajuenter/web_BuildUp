<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-KEY'); 

        if (!$apiKey || $apiKey !== config('app.api_key')) {
            return response()->json(['message' => 'Akses ditolak Api Key tidak valid'], 401);
        }
        return $next($request);
    }
}
