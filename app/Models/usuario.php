<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class Usuario extends Authenticatable implements JWTSubject
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

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function carritos(): HasMany
    {
        return $this->hasMany(Carrito::class, 'usuario_id');
    }

    public function compras(): HasMany
    {
        return $this->hasMany(Compra::class, 'usuario_id');
    }
}
