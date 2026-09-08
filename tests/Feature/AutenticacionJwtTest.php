<?php

use App\Models\Carrito;
use App\Models\Usuario;

it('permite iniciar sesión con credenciales válidas y protege la API sin token', function () {
    $usuario = Usuario::factory()
        ->conContraseña('password123')
        ->create([
            'nombre' => 'Cliente JWT',
            'correo' => 'jwt@example.com',
        ]);

    $login = $this->postJson('/api/auth/login', [
        'correo' => $usuario->correo,
        'password' => 'password123',
    ])->assertSuccessful();

    $token = $login->json('token');

    expect($token)->toBeString()->not->toBeEmpty();

    $this->getJson('/api/carritos')
        ->assertUnauthorized();

    $this->withToken($token)
        ->getJson('/api/carritos')
        ->assertSuccessful()
        ->assertJsonCount(1);

    expect(Carrito::where('usuario_id', $usuario->id)->count())
        ->toBe(1);
});
