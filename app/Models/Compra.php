<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $fillable = ['usuario_id', 'producto_id','total', 'cantidad','estado','precio_unitario'];

    public function casts()
    {
        return [
            'total' => 'decimal:2',
            'cantidad' => 'integer',
            'estado' => 'enum:pendiente,completada,cancelada',
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
