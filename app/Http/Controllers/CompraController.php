<?php

namespace App\Http\Controllers;

use App\DTO\CheckoutRequestDTO;
use App\DTO\CheckoutSummaryDTO;
use App\Models\Compra;
use App\Models\Carrito;
use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $compras = Compra::all();

        return response()->json($compras);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Compra $compra)
    {
        $request->validate([
            'usuario_id' => 'sometimes|required|integer',
            'total' => 'sometimes|required|numeric|min:0',
        ]);

        $compra->update($request->all());

        return response()->json($compra);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Compra $compra)
    {
        $compra->delete();

        return response()->json(null, 204);
    }

    public function checkout(Request $request, Usuario $usuario)
    {
        $payload = CheckoutRequestDTO::fromRequest($request);

        $carrito = Carrito::where('usuario_id', $usuario->id)->first();

        if (! $carrito) {
            return response()->json(['message' => 'El usuario no tiene un carrito activo.'], 404);
        }

        $items = $carrito->items()->with('producto')->get();

        if ($items->isEmpty()) {
            return response()->json(['message' => 'El carrito está vacío.'], 400);
        }

        $subtotal = 0.0;

        foreach ($items as $item) {
            $producto = $item->producto;

            if (! $producto || $producto->stock < $item->cantidad) {
                return response()->json([
                    'message' => 'No hay suficiente stock para completar el checkout.',
                    'producto_id' => $item->producto_id,
                ], 409);
            }

            $subtotal += (float) $item->cantidad * (float) $producto->precio;
        }

        $summary = new CheckoutSummaryDTO(
            subtotal: $subtotal,
            impuesto: (float) $payload->impuesto,
            envio: (float) $payload->envio,
            total: $subtotal + $payload->impuesto + $payload->envio,
            metodoPago: $payload->metodoPago,
            direccionEnvio: $payload->direccionEnvio,
        );

        DB::transaction(function () use ($items, $usuario, $carrito, $summary) {
            foreach ($items as $item) {
                $producto = $item->producto;

                Compra::create([
                    'usuario_id' => $usuario->id,
                    'producto_id' => $item->producto_id,
                    'cantidad' => $item->cantidad,
                    'total' => $item->cantidad * $producto->precio,
                    'subtotal' => $summary->subtotal,
                    'impuesto' => $summary->impuesto,
                    'envio' => $summary->envio,
                    'precio_unitario' => $producto->precio,
                    'metodo_pago' => $summary->metodoPago,
                    'direccion_envio' => $summary->direccionEnvio,
                    'estado' => 'completada',
                ]);

                $producto->decrement('stock', $item->cantidad);
                $item->delete();
            }

            $carrito->delete();
        });

        return response()->json($summary->toArray());
    }

    public function getComprasByUsuario(Usuario $usuario)
    {
        $compras = Compra::where('usuario_id', $usuario->id)->get();

        return response()->json($compras);
    }
}
