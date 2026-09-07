<?php

use App\Models\Carrito;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

it('registra un usuario con contraseña bcrypt y crea su carrito', function () {
    $respuesta = $this->postJson('/api/auth/register', [
        'nombre' => 'Usuario de prueba',
        'correo' => 'prueba@example.com',
        'password' => 'password123',
    ]);

    $respuesta
        ->assertCreated()
        ->assertJsonStructure([
            'user' => ['id', 'nombre', 'correo'],
            'token',
            'token_type',
            'expires_in',
        ]);

    $usuario = Usuario::where('correo', 'prueba@example.com')->firstOrFail();

    expect(Hash::check('password123', $usuario->password))->toBeTrue();
    expect(Carrito::where('usuario_id', $usuario->id)->count())->toBe(1);
});
