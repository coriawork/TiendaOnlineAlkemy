<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SeguridadApi
{
    public function handle(Request $request, Closure $next): Response
    {
        if (
            $request->isMethodSafe() === false
            && $request->hasCookie(config('session.cookie'))
            && ! $request->bearerToken()
        ) {
            return response()->json([
                'message' => 'Las solicitudes de modificación a la API deben autenticarse mediante un token Bearer.',
            ], 419);
        }

        $response = $next($request);
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('Referrer-Policy', 'no-referrer');
        $response->headers->set(
            'Content-Security-Policy',
            "default-src 'none'; frame-ancestors 'none'; base-uri 'none'; form-action 'none'",
        );

        return $response;
    }
}
