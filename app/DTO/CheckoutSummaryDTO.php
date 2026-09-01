<?php

namespace App\DTO;

final class CheckoutSummaryDTO
{
    public function __construct(
        public readonly float $subtotal,
        public readonly float $impuesto,
        public readonly float $envio,
        public readonly float $total,
        public readonly string $metodoPago,
        public readonly string $direccionEnvio,
    ) {
    }

    public function toArray(): array
    {
        return [
            'subtotal' => round($this->subtotal, 2),
            'impuesto' => round($this->impuesto, 2),
            'envio' => round($this->envio, 2),
            'total' => round($this->total, 2),
            'metodo_pago' => $this->metodoPago,
            'direccion_envio' => $this->direccionEnvio,
        ];
    }
}
