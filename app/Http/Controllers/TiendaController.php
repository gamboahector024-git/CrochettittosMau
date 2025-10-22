<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class TiendaController extends Controller
{
    public function index()
    {
        $productos_llaveros = Producto::where('categoria', 'llaveros')->get();
        $productos_flores = Producto::where('categoria', 'flores')->get();
        $productos_personalizados = Producto::where('categoria', 'personalizados')->get();

        return view('tienda', compact('productos_llaveros', 'productos_flores', 'productos_personalizados'));
    }
}
