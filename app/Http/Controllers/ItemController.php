<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    private function findItemByCompositeKey(int $carritoId, int $productoId): Item
    {
        return Item::where('carrito_id', $carritoId)
            ->where('producto_id', $productoId)
            ->firstOrFail();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::all();

        return response()->json($items);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1',
            'carrito_id' => 'required|exists:carritos,id',
            'producto_id' => 'required|exists:productos,id',
        ]);

        $producto = Producto::find($request->producto_id);

        if (! $producto || $producto->stock < $request->cantidad) {
            return response()->json(['message' => 'No hay suficiente stock para el producto solicitado.'], 400);
        }

        $item = Item::where('carrito_id', $request->carrito_id)
            ->where('producto_id', $request->producto_id)
            ->first();

        if ($item) {
            $item->cantidad += $request->cantidad;
            $item->save();

            return response()->json($item, 200);
        }

        $item = Item::create($request->all());

        return response()->json($item, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $carrito_id, int $producto_id)
    {
        $item = $this->findItemByCompositeKey($carrito_id, $producto_id);

        return response()->json($item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $carrito_id, int $producto_id)
    {
        $request->validate([
            'cantidad' => 'sometimes|required|integer|min:1',
        ]);

        Item::where('carrito_id', $carrito_id)
            ->where('producto_id', $producto_id)
            ->update($request->only('cantidad'));

        $item = $this->findItemByCompositeKey($carrito_id, $producto_id);

        return response()->json($item);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $carrito_id, int $producto_id)
    {
        Item::where('carrito_id', $carrito_id)
            ->where('producto_id', $producto_id)
            ->delete();

        return response()->json(null, 204);
    }
}
