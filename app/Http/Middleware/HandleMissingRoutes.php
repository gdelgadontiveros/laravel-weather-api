<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleMissingRoutes
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->status() === 404 && $request->wantsJson()) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'The requested route does not exist',
                'request' => [
                    'url' => $request->fullUrl(),
                    'method' => $request->method()
                ],
                'suggestions' => [
                    'Check your route spelling',
                    'Verify the HTTP method (GET, POST, etc.)',
                    'Review API documentation at '.url('/docs')
                ]
            ], 404);
        }

        return $response;
    }
}
