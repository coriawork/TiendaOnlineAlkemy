<?php

namespace App\Http\Middleware;

use App\Models\Carrito;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VerificarPropietarioCarrito
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response|JsonResponse)  $next
     * @return Response|JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'message' => 'No autenticado.',
            ], 401);
        }

        $usuarioId = $request->route('usuario')?->id ?? $request->route('usuario');

        if ($usuarioId && (int) $usuarioId !== (int) $user->getAuthIdentifier()) {
            return response()->json([
                'message' => 'No tienes permisos sobre este carrito o compra.',
            ], 403);
        }

        $carritoId = $request->route('carrito')
            ?? $request->route('carrito_id')
            ?? $request->input('carrito_id');

        if ($carritoId) {
            $carrito = Carrito::find($carritoId);

            if (! $carrito || (int) $carrito->usuario_id !== (int) $user->getAuthIdentifier()) {
                return response()->json([
                    'message' => 'Este carrito no pertenece al usuario autenticado.',
                ], 403);
            }
        }

        return $next($request);
    }
}
