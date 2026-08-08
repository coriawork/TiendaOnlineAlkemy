<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'categoria_id',
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'imagen',
    ];

    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'stock' => 'integer',
        ];
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(categoria::class, 'categoria_id');
    }

    public function carritos(): HasMany
    {
        return $this->hasMany(carrito::class, 'producto_id');
    }
}
