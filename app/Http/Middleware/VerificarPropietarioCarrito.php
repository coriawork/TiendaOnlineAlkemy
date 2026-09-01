<?php

namespace App\Http\Middleware;

use App\Models\Carrito;
use Closure;
use Illuminate\Http\Request;

class VerificarPropietarioCarrito
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\JsonResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (! $user) {
            return response()->json([
                'message' => 'No autenticado.',
            ], 401);
        }

        $usuarioId = $request->route('usuario')?->id ?? $request->route('usuario');

        if ($usuarioId && (int) $usuarioId !== (int) $user->id) {
            return response()->json([
                'message' => 'No tienes permisos sobre este carrito o compra.',
            ], 403);
        }

        $carritoId = $request->route('carrito');

        if ($carritoId) {
            $carrito = Carrito::find($carritoId);

            if (! $carrito || (int) $carrito->usuario_id !== (int) $user->id) {
                return response()->json([
                    'message' => 'Este carrito no pertenece al usuario autenticado.',
                ], 403);
            }
        }

        return $next($request);
    }
}
