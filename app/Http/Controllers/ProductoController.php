<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\producto;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = producto::with('categoria')
            ->latest()
            ->paginate(10);

        return response()->json($productos);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'categoria_id' => ['required', 'integer', 'exists:categorias,id'],
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'precio' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'imagen' => ['nullable', 'string', 'max:255'],
        ]);

        $producto = producto::create($validated);

        return response()->json([
            'message' => 'Producto creado correctamente.',
            'producto' => $producto->load('categoria'),
        ], 201);
    }

    public function show(producto $producto)
    {
        return response()->json(
            $producto->load(['categoria', 'carritos'])
        );
    }

    public function update(Request $request, producto $producto)
    {
        $validated = $request->validate([
            'categoria_id' => ['required', 'integer', 'exists:categorias,id'],
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'precio' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'imagen' => ['nullable', 'string', 'max:255'],
        ]);

        $producto->update($validated);

        return response()->json([
            'message' => 'Producto actualizado correctamente.',
            'producto' => $producto->fresh()->load('categoria'),
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
