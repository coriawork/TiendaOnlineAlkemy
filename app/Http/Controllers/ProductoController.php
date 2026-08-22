<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\producto;
use App\Models\categoria;
use App\Http\Requests\ProductoRequest;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = producto::with('categoria')
            ->latest()
            ->paginate(10);

        return response()->json($productos);
    }

    public function store(ProductoRequest $request)
    {

        Producto::create([
            'categoria_id' => $request->categoria_id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
        ]);

        return response()->json([
            'message' => 'Producto creado correctamente.',
        ], 201);
    }

    public function show(producto $producto)
    {
        return response()->json(
            $producto->load(['categoria'])
        );
    }
    

    public function update(Request $request, producto $producto)
    {
        $producto->update([
            'categoria_id' => $request->categoria_id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
        ]);


        return response()->json([
            'message' => 'Producto actualizado correctamente.',
        ]);
    }
    public function restarStock(producto $producto, int $cantidad)
    {
        if ($producto->stock >= $cantidad) {
            $producto->stock -= $cantidad;
            $producto->save();
            return response()->json(['message' => 'Stock actualizado correctamente.', 'nuevo_stock' => $producto->stock]);
        } else {
            return response()->json(['message' => 'No hay suficiente stock para restar la cantidad solicitada.'], 400);
        }
    }

    public function destroy(producto $producto)
    {
        $producto->delete();

        return response()->json([
            'message' => 'Producto eliminado correctamente.',
        ]);
    }
}
