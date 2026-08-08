<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class categoria extends Model
{
    protected $table = 'categorias';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];


    public function productos(): HasMany
    {
        return $this->hasMany(producto::class, 'categoria_id');
    }
}
