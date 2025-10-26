<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class TiendaController extends Controller
{
    public function index()
    {
        $productos_llaveros = Producto::whereHas('categoria', function($query) {
            $query->where('nombre', 'llaveros');
        })->get();
        $productos_flores = Producto::whereHas('categoria', function($query) {
            $query->where('nombre', 'flores');
        })->get();
        $productos_personalizados = Producto::whereHas('categoria', function($query) {
            $query->where('nombre', 'personalizados');
        })->get();

        return view('tienda', compact('productos_llaveros', 'productos_flores', 'productos_personalizados'));
    }
}
