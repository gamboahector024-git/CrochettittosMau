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
        
        // CORREGIDO: Usar 'filled' para asegurarse de que el campo tiene un valor
        if ($request->filled('busqueda')) {
            $query->where('nombre', 'like', '%'.$request->busqueda.'%');
        }
        
        // CORREGIDO: Usar 'filled'
        if ($request->filled('categoria')) {
            $query->whereHas('categoria', function($q) use ($request) {
                $q->where('nombre', $request->categoria);
            });
        }
        
        // CORREGIDO: Esto soluciona tu error 'Illegal operator'
        if ($request->filled('precio_min')) {
            $query->where('precio', '>=', $request->precio_min);
        }
        
        // CORREGIDO: Esto soluciona tu error 'Illegal operator'
        if ($request->filled('precio_max')) {
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