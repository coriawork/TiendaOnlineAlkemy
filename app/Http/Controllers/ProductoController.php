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
    public function restarStock(producto|int $producto, int $cantidad)
    {
        $productoModel = $producto instanceof producto ? $producto : producto::findOrFail($producto);

        if ($productoModel->stock < $cantidad) {
            return response()->json(['message' => 'No hay suficiente stock para restar la cantidad solicitada.'], 400);
        }

        $productoModel->stock -= $cantidad;
        $productoModel->save();

        return response()->json([
            'message' => 'Stock actualizado correctamente.',
            'nuevo_stock' => $productoModel->stock,
        ]);
    }

    public function destroy(producto $producto)
    {
        $producto->delete();

        return response()->json([
            'message' => 'Producto eliminado correctamente.',
        ]);
    }
}
