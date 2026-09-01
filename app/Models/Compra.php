<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $fillable = [
        'usuario_id',
        'producto_id',
        'total',
        'cantidad',
        'estado',
        'precio_unitario',
        'subtotal',
        'impuesto',
        'envio',
        'metodo_pago',
        'direccion_envio',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'impuesto' => 'decimal:2',
            'envio' => 'decimal:2',
            'precio_unitario' => 'decimal:2',
            'cantidad' => 'integer',
            'estado' => 'string',
        ];
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
