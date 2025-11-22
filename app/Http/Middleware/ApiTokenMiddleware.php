<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-Api-Key') ?? $request->query('api_token');
        $expected = Cache::remember('api_token', now()->addMinutes(30), function () {
            return optional(Organization::first())->api_token ?? env('API_TOKEN', 'demo-api-key');
        });

        if (!$expected || $token !== $expected) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        return $next($request);
    }
}
