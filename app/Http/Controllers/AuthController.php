<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'correo' => ['required', 'email', 'unique:usuarios,correo'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $usuario = Usuario::create([
            'nombre' => $validated['nombre'],
            'correo' => $validated['correo'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = JWTAuth::fromUser($usuario);

        return response()->json([
            'user' => [
                'id' => $usuario->id,
                'nombre' => $usuario->nombre,
                'correo' => $usuario->correo,
            ],
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'correo' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials = [
            'correo' => $validated['correo'],
            'password' => $validated['password'],
        ];

        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'message' => 'Credenciales inválidas.',
            ], 401);
        }

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
        ]);
    }

    public function me()
    {
        return response()->json(Auth::guard('api')->user());
    }

    public function logout()
    {
        auth()->guard('api')->logout();

        return response()->json(['message' => 'Sesión cerrada correctamente.']);
    }

    public function refresh()
    {
        $token = auth()->guard('api')->refresh();

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
        ]);
    }
}
