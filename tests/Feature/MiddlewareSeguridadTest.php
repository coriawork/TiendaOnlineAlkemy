<?php

use App\Models\Carrito;
use App\Models\Usuario;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

it('rechaza una ruta protegida sin token JWT', function () {
    $this->getJson('/api/carritos')
        ->assertUnauthorized()
        ->assertJsonPath('message', 'No se proporcionó un token JWT válido.');
});

it('rechaza una ruta protegida con un token JWT inválido', function () {
    $this->withToken('token-invalido')
        ->getJson('/api/carritos')
        ->assertUnauthorized()
        ->assertJsonPath('message', 'El token JWT es inválido.');
});

it('rechaza con 403 el acceso de un usuario al carrito de otra persona', function () {
    $usuario = Usuario::factory()->create();
    $otroUsuario = Usuario::factory()->create();
    $token = JWTAuth::fromUser($usuario);
    $carritoAjeno = Carrito::where('usuario_id', $otroUsuario->id)->firstOrFail();

    $this->withToken($token)
        ->getJson("/api/carritos/{$carritoAjeno->id}")
        ->assertForbidden()
        ->assertJsonPath('message', 'Este carrito no pertenece al usuario autenticado.');
});

it('rechaza con 403 agregar un item al carrito de otra persona', function () {
    $usuario = Usuario::factory()->create();
    $otroUsuario = Usuario::factory()->create();
    $token = JWTAuth::fromUser($usuario);
    $carritoAjeno = Carrito::where('usuario_id', $otroUsuario->id)->firstOrFail();

    $this->withToken($token)
        ->postJson('/api/items', [
            'carrito_id' => $carritoAjeno->id,
            'producto_id' => 1,
            'cantidad' => 1,
        ])
        ->assertForbidden()
        ->assertJsonPath('message', 'Este carrito no pertenece al usuario autenticado.');
});
