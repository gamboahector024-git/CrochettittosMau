<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Carrusel;

class TiendaController extends Controller
{
    public function index(Request $request)
    {
        // Obtener imágenes del carrusel
        $carruseles = Carrusel::where('activo', true)
            ->orderBy('orden')
            ->get();
        
        // Obtener productos y categorías para ambos tipos de usuarios
        $query = Producto::with(['promocionActiva', 'categoria']);
        
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
        
        // CORREGIDO: Usar 'filled'
        if ($request->filled('orden')) {
            switch ($request->orden) {
                case 'precio_asc':
                    $query->orderBy('precio', 'asc');
                    break;
                case 'precio_desc':
                    $query->orderBy('precio', 'desc');
                    break;
                case 'nombre_asc':
                    $query->orderBy('nombre', 'asc');
                    break;
                case 'nombre_desc':
                    $query->orderBy('nombre', 'desc');
                    break;
                default:
                    $query->orderBy('id_producto', 'desc');
            }
        } else {
            $query->orderBy('id_producto', 'desc');
        }
        
        $productos = $query->get();
        $categorias = Categoria::all();
        
        if (auth()->check()) {
            // Usuario autenticado - vista completa con funcionalidades
            return view('cliente.tienda', compact('productos', 'categorias', 'carruseles'));
        } else {
            // Visitante - mismo catálogo pero funcionalidades limitadas
            return view('visita.tienda', compact('productos', 'categorias', 'carruseles'));
        }
    }
}