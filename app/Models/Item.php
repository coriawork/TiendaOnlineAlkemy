<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';

    public $timestamps = false;

    protected $fillable = ['carrito_id', 'producto_id', 'cantidad'];

    protected $casts = [
        'carrito_id' => 'integer',
        'producto_id' => 'integer',
        'cantidad' => 'integer',
    ];

    public function carrito()
    {
        return $this->belongsTo(Carrito::class, 'carrito_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
