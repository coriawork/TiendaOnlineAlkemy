<?php

use App\Models\Carrito;
use App\Models\Categoria;
use App\Models\Producto;

it('rechaza cantidades que superan el stock al agregar o actualizar un item', function () {
    $registro = $this->postJson('/api/auth/register', [
        'nombre' => 'Cliente de stock',
        'correo' => 'stock@example.com',
        'password' => 'password123',
    ])->assertCreated();

    $token = $registro->json('token');
    $carritoId = Carrito::where('usuario_id', $registro->json('user.id'))->value('id');
    $categoria = Categoria::create(['nombre' => 'Pruebas']);
    $producto = Producto::create([
        'categoria_id' => $categoria->id,
        'nombre' => 'Producto limitado',
        'descripcion' => 'Producto para probar stock',
        'precio' => 100,
        'stock' => 1,
    ]);

    $this->withToken($token)
        ->postJson('/api/items', [
            'carrito_id' => $carritoId,
            'producto_id' => $producto->id,
            'cantidad' => 1,
        ])
        ->assertCreated();

    $this->withToken($token)
        ->postJson('/api/items', [
            'carrito_id' => $carritoId,
            'producto_id' => $producto->id,
            'cantidad' => 1,
        ])
        ->assertStatus(400)
        ->assertJsonPath('message', 'La cantidad total supera el stock disponible.');

    $this->withToken($token)
        ->putJson("/api/items/{$carritoId}/{$producto->id}", [
            'cantidad' => 2,
        ])
        ->assertStatus(400)
        ->assertJsonPath('message', 'La cantidad solicitada supera el stock disponible.');
});
