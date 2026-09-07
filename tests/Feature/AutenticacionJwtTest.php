<?php

use App\Models\Carrito;
use App\Models\Usuario;

it('permite iniciar sesión con credenciales válidas y protege la API sin token', function () {
    $this->postJson('/api/auth/register', [
        'nombre' => 'Cliente JWT',
        'correo' => 'jwt@example.com',
        'password' => 'password123',
    ])->assertCreated();

    $login = $this->postJson('/api/auth/login', [
        'correo' => 'jwt@example.com',
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

    expect(Carrito::where('usuario_id', Usuario::where('correo', 'jwt@example.com')->value('id'))->count())
        ->toBe(1);
});
