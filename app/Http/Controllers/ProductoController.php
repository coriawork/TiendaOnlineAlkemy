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

        return view('productos', compact('productos'));
    }
    public function create()
    {
        $categorias = categoria::all();
        return view('productoCrear', compact('categorias'));
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

        return view('productos', [
            'productos' => producto::with('categoria')->latest()->paginate(10),
            'message' => ["message" => 'Producto creado correctamente.', "type" => "success"],
        ]);
    }

    public function show(producto $producto)
    {
        return response()->json(
            $producto->load(['categoria', 'carritos'])
        );
    }
    
    public function edit(producto $producto)
    {
        $categorias = categoria::all();
        return view('productoEditar', compact('producto', 'categorias'));
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


        return view('productos', [
            'productos' => producto::with('categoria')->latest()->paginate(10),
            'message' => ["message" => 'Producto actualizado correctamente.', "type" => "success"],
        ]);
    }

    public function destroy(producto $producto)
    {
        $producto->delete();

        return view('productos', [
            'productos' => producto::with('categoria')->latest()->paginate(10),
            'message' => ["message" => 'Producto eliminado correctamente.', "type" => "success"],
        ]);
    }
}
