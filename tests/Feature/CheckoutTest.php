<?php

use App\Models\Carrito;
use App\Models\Categoria;
use App\Models\Compra;
use App\Models\Item;
use App\Models\Producto;

it('ejecuta el checkout, registra la compra, descuenta stock y vacía el carrito', function () {
    $registro = $this->postJson('/api/auth/register', [
        'nombre' => 'Cliente checkout',
        'correo' => 'checkout@example.com',
        'password' => 'password123',
    ])->assertCreated();

    $token = $registro->json('token');
    $usuarioId = $registro->json('user.id');
    $carritoId = Carrito::where('usuario_id', $usuarioId)->value('id');
    $categoria = Categoria::factory()->create(['nombre' => 'Hogar']);
    $producto = Producto::factory()
        ->for($categoria, 'categoria')
        ->create([
            'nombre' => 'Lámpara de prueba',
            'precio' => 1500,
            'stock' => 5,
        ]);

    $this->withToken($token)
        ->postJson('/api/items', [
            'carrito_id' => $carritoId,
            'producto_id' => $producto->id,
            'cantidad' => 2,
        ])
        ->assertCreated();

    $this->withToken($token)
        ->postJson("/api/compras/{$usuarioId}/checkout", [
            'metodo_pago' => 'tarjeta',
            'direccion_envio' => 'Calle 123',
            'impuesto' => 300,
            'envio' => 500,
        ])
        ->assertSuccessful()
        ->assertJsonPath('subtotal', 3000)
        ->assertJsonPath('total', 3800)
        ->assertJsonPath('metodo_pago', 'tarjeta');

    expect(Compra::where('usuario_id', $usuarioId)->where('producto_id', $producto->id)->count())
        ->toBe(1);
    expect(Producto::find($producto->id)->stock)->toBe(3);
    expect(Item::where('carrito_id', $carritoId)->exists())->toBeFalse();
    expect(Carrito::find($carritoId))->toBeNull();
});
