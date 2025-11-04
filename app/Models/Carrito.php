<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carrito extends Model
{
    protected $primaryKey = 'id_carrito';
    protected $table = 'carritos';
    
    protected $fillable = [
        'id_usuario'
    ];

    protected $casts = [
        'last_reminder_sent_at' => 'datetime',
    ];

    // Cargar siempre los detalles y el producto asociado
    protected $with = ['detalles.producto.categoria'];

    // Relación con Usuario
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    // Relación con Detalles del Carrito
    public function detalles(): HasMany
    {
        return $this->hasMany(CarritoDetalle::class, 'id_carrito', 'id_carrito');
    }

    // Relación con Productos (a través de detalles)
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'carrito_detalles', 'id_carrito', 'id_producto')
            ->withPivot(['cantidad']);
    }
}