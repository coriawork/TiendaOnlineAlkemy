<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AutenticarJwtApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\JsonResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $token = JWTAuth::parseToken();
            $user = $token->authenticate();

            if (! $user) {
                return response()->json([
                    'message' => 'Token JWT inválido o no autenticado.',
                ], 401);
            }

            auth()->setUser($user);

            return $next($request);
        } catch (TokenExpiredException $e) {
            return response()->json([
                'message' => 'El token JWT ha expirado.',
            ], 401);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'message' => 'El token JWT es inválido.',
            ], 401);
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'No se proporcionó un token JWT válido.',
            ], 401);
        }
    }
}
