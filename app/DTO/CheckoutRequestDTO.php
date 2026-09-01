<?php

namespace App\DTO;

use Illuminate\Http\Request;

final class CheckoutRequestDTO
{
    public function __construct(
        public readonly float $impuesto,
        public readonly float $envio,
        public readonly string $metodoPago,
        public readonly string $direccionEnvio,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $data = $request->validate([
            'metodo_pago' => ['required', 'string', 'max:255'],
            'direccion_envio' => ['required', 'string', 'max:255'],
            'impuesto' => ['nullable', 'numeric', 'min:0'],
            'envio' => ['nullable', 'numeric', 'min:0'],
        ]);

        return new self(
            impuesto: (float) ($data['impuesto'] ?? 0),
            envio: (float) ($data['envio'] ?? 0),
            metodoPago: (string) $data['metodo_pago'],
            direccionEnvio: (string) $data['direccion_envio'],
        );
    }
}
