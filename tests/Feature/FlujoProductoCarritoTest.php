<?php

use App\Models\Carrito;
use App\Models\Categoria;
use App\Models\Item;
use App\Models\Producto;

it('crea un producto, lo agrega al carrito y permite eliminarlo', function () {
    $registro = $this->postJson('/api/auth/register', [
        'nombre' => 'Cliente carrito',
        'correo' => 'carrito@example.com',
        'password' => 'password123',
    ])->assertCreated();

    $token = $registro->json('token');
    $carritoId = Carrito::where('usuario_id', $registro->json('user.id'))->value('id');
    $categoria = Categoria::factory()->create(['nombre' => 'Accesorios']);

    $this->postJson('/api/productos', [
        'categoria_id' => $categoria->id,
        'nombre' => 'Teclado mecánico',
        'descripcion' => 'Producto de prueba',
        'precio' => 25000,
        'stock' => 10,
    ])->assertCreated();

    $producto = Producto::where('nombre', 'Teclado mecánico')->firstOrFail();

    $this->withToken($token)
        ->postJson('/api/items', [
            'carrito_id' => $carritoId,
            'producto_id' => $producto->id,
            'cantidad' => 2,
        ])
        ->assertCreated();

    expect(Item::where('carrito_id', $carritoId)->where('producto_id', $producto->id)->value('cantidad'))
        ->toBe(2);

    $this->withToken($token)
        ->deleteJson("/api/items/{$carritoId}/{$producto->id}")
        ->assertNoContent();

    expect(Item::where('carrito_id', $carritoId)->where('producto_id', $producto->id)->exists())
        ->toBeFalse();
});
