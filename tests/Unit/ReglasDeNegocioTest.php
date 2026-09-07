<?php

use App\DTO\CheckoutSummaryDTO;
use App\Models\Producto;
use App\Models\Usuario;
use App\Rules\PrecioValido;

it('calcula y redondea el total del resumen del checkout', function () {
    $resumen = new CheckoutSummaryDTO(
        subtotal: 1250.456,
        impuesto: 262.595,
        envio: 99.999,
        total: 1613.05,
        metodoPago: 'tarjeta',
        direccionEnvio: 'Calle 123',
    );

    expect($resumen->toArray())->toMatchArray([
        'subtotal' => 1250.46,
        'impuesto' => 262.6,
        'envio' => 100.0,
        'total' => 1613.05,
        'metodo_pago' => 'tarjeta',
        'direccion_envio' => 'Calle 123',
    ]);
});

it('rechaza precios superiores al límite de negocio', function () {
    $mensajes = [];
    $regla = new PrecioValido;

    $regla->validate('precio', 1000001, function (string $mensaje) use (&$mensajes): void {
        $mensajes[] = $mensaje;
    });

    expect($mensajes)->toHaveCount(1);
    expect($mensajes[0])->toContain('no puede superar');
});

it('acepta un precio dentro del límite de negocio', function () {
    $regla = new PrecioValido;
    $regla->validate('precio', 1000000, function (): never {
        throw new RuntimeException('Un precio válido no debe activar la regla.');
    });

    expect(true)->toBeTrue();
});

it('mantiene ocultas las contraseñas y castea el stock como entero', function () {
    $usuario = new Usuario;
    $producto = new Producto(['stock' => '5']);

    expect($usuario->getHidden())->toContain('password');
    expect($producto->stock)->toBeInt()->toBe(5);
});
