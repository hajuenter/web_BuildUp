<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ApiKey;
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
        if (!$apiKey || !\App\Models\ApiKey::where('api_key', $apiKey)->exists()) {
            return response()->json(['message' => 'API Key tidak valid'], 401);
        }

        return $next($request);
    }
}
