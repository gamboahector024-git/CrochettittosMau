<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios'; // Nombre de la tabla
    protected $primaryKey = 'id_usuario'; // Clave primaria
    public $timestamps = false; // No tenemos created_at / updated_at

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'password_hash',
        'direccion',
        'telefono',
        'rol',
        'fecha_registro',
        'ultima_actividad',
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected $casts = [
        'ultima_actividad' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'id_usuario';
    }

    // En app/Models/Usuario.php
    public function carrito()
    {
        return $this->hasOne(Carrito::class, 'id_usuario', 'id_usuario');
    }

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class, 'id_usuario', 'id_usuario');
    }

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // Estado activo sin depender de columnas específicas
    public function isActive(): bool
    {
        return true;
    }
}
