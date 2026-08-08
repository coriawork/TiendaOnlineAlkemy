<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class usuario extends Model
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
        return $this->hasMany(carrito::class, 'usuario_id');
    }
}
