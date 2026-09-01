<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrito;
use App\Models\Item;

class CarritoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carritos = Carrito::all();
        return response()->json($carritos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|integer',
        ]);

        $carrito = Carrito::create($request->all());

        return response()->json($carrito, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Carrito $carrito)
    {
        return response()->json($carrito);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Carrito $carrito)
    {
        $request->validate([
            'usuario_id' => 'sometimes|required|integer',
        ]);

        $carrito->update($request->all());

        return response()->json($carrito);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function empty(Carrito $carrito)
    {
        Item::where('carrito_id', $carrito->id)->delete();

        return response()->json(['message' => 'Carrito vaciado correctamente.']);
    }

    public function CalcularTotal(Carrito $carrito)
    {
        $total = 0;
        foreach ($carrito->items as $item) {
            $cantidad = $item->cantidad;
            $producto = $item->producto->precio;
            $total += $cantidad * $producto;
        }
        return response()->json(['total' => $total]);
    }
}
