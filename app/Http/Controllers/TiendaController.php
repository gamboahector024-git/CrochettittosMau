<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;

class TiendaController extends Controller
{
    public function index(Request $request)
    {
        // Obtener productos y categorías para ambos tipos de usuarios
        $query = Producto::query();
        
        if ($request->has('busqueda')) {
            $query->where('nombre', 'like', '%'.$request->busqueda.'%');
        }
        
        if ($request->has('categoria')) {
            $query->whereHas('categoria', function($q) use ($request) {
                $q->where('nombre', $request->categoria);
            });
        }
        
        if ($request->has('precio_min')) {
            $query->where('precio', '>=', $request->precio_min);
        }
        
        if ($request->has('precio_max')) {
            $query->where('precio', '<=', $request->precio_max);
        }
        
        $productos = $query->get();
        $categorias = Categoria::all();
        
        if (auth()->check()) {
            // Usuario autenticado - vista completa con funcionalidades
            return view('cliente.tienda', compact('productos', 'categorias'));
        } else {
            // Visitante - mismo catálogo pero funcionalidades limitadas
            return view('visita.tienda', compact('productos', 'categorias'));
        }
    }
}
