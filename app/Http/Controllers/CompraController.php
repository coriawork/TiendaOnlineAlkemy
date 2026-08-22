<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use Illuminate\Http\Request;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\ProductoController;
use App\Models\Carrito;
use App\Models\Producto;
use App\Models\Usuario;
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

    public function checkout(Usuario $usuario)
    {   
        $carrito = Carrito::where('usuario_id', $usuario->id)->first();
        
        //voy a recorrer los items del carrito y crear una compra por cada item
        foreach ($carrito->items as $item) {
            $producto = Producto::find($item->producto_id);
            if ($producto->stock < $item->cantidad) {
                continue; // Saltea este item si no hay suficiente stock
            }
            Compra::create([        
                'usuario_id' => $carrito->usuario_id,
                'producto_id' => $item->producto_id,
                'total' => $item->cantidad * $producto->precio,
                'cantidad' => $item->cantidad,
                'precio_unitario' => $producto->precio,
            ]);
            new ProductoController()->restarStock($item->producto_id, $item->cantidad);
            new ItemController()->destroy($item);
        }
        return response()->json(['message' => 'Checkout realizado con éxito para el carrito ID: ' . $carrito->id]);
    }

    function getComprasByUsuario(Usuario $usuario)
    {
        $compras = Compra::where('usuario_id', $usuario->id)->get();
        return response()->json($compras);
    }

}
