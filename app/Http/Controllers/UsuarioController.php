<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

use App\Http\Controllers\CarritoController;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = Usuario::all();
        return response()->json($usuarios);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:usuarios,correo',
            'password' => 'required|string|min:6',
        ]);
        
        $usuario = Usuario::create($request->all());
      
        new CarritoController()->store(new Request(['usuario_id' => $usuario->id]));

        return response()->json($usuario, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Usuario $usuario)
    {
        return response()->json($usuario);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Usuario $usuario)
    {
        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'correo' => 'sometimes|required|email|unique:usuarios,correo,' . $usuario->id,
            'password' => 'sometimes|required|string|min:6',
        ]);

        $usuario->update($request->all());

        return response()->json($usuario);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuario $usuario)
    {
        $usuario->delete();
        return response()->json(null, 204);
    }
}
