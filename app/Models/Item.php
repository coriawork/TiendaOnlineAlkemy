<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['carrito_id', 'producto_id', 'cantidad'];


    public function carrito()
    {
        return $this->belongsTo(Carrito::class);
    }
    public $timestamps = false;

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

}
