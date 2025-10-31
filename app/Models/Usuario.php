<?php

namespace App\Models;

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
        'last_activity',
        'is_online'
    ];

    protected $hidden = [
        'password_hash',
    ];

    public function getRouteKeyName(): string
    {
        return 'id_usuario';
    }

    // Verifica si el usuario está activo (últimos 5 minutos)
    public function isActive(): bool
    {
        return $this->is_online || 
               ($this->last_activity && $this->last_activity > now()->subMinutes(5));
    }
}
