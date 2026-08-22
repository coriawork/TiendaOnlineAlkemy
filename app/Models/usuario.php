<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'correo',
        'password',
    ];

    protected $hidden = [
        'password',
    ];


    public function carritos(): HasMany
    {
        return $this->hasMany(Carrito::class, 'usuario_id');
    }
    public function compras(): HasMany
    {
        return $this->hasMany(Compra::class, 'usuario_id');
    }
}
