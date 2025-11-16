<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrusel extends Model
{
    protected $table = 'carruseles';
    protected $fillable = ['imagen', 'orden'];
    public $timestamps = false;
}
