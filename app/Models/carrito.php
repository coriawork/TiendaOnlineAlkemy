<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Carrito extends Model
{
    protected $table = 'carritos';
    
    protected $fillable = [ 
        'usuario_id',   
        'cantidad', 
        'precio_unitario',
    ];

    protected function casts(): array
    {
        return [
            'cantidad' => 'integer',
            'precio_unitario' => 'decimal:2',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(usuario::class, 'usuario_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(item::class, 'carrito_id');
    }

}
