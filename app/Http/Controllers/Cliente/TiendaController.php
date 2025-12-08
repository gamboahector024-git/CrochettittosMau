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
        // 1. Obtener imágenes del carrusel
        $carruseles = Carrusel::where('activo', true)
            ->orderBy('orden')
            ->get();
        
        // 2. Iniciar la consulta de productos
        $query = Producto::with(['promocionActiva', 'categoria']);
        
        // --- FILTROS ---

        // Búsqueda por texto
        if ($request->filled('busqueda')) {
            $query->where('nombre', 'like', '%'.$request->busqueda.'%');
        }
        
        // Categorías (Ahora acepta múltiples opciones)
        if ($request->filled('categorias')) {
            $query->whereHas('categoria', function($q) use ($request) {
                // whereIn busca si la categoría está DENTRO de la lista enviada
                $q->whereIn('nombre', $request->categorias);
            });
        }
        
        // Precio Mínimo
        if ($request->filled('precio_min')) {
            $query->where('precio', '>=', $request->precio_min);
        }
        
        // Precio Máximo
        if ($request->filled('precio_max')) {
            $query->where('precio', '<=', $request->precio_max);
        }
        
        // --- ORDENAMIENTO ---
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
                case 'recientes':
                default:
                    $query->orderBy('created_at', 'desc'); // Asumiendo que usas timestamps
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        // 3. Ejecutar consulta
        $productos = $query->get();
        $categorias = Categoria::all();
        
        // 4. Retornar vista según autenticación
        if (auth()->check()) {
            return view('cliente.tienda', compact('productos', 'categorias', 'carruseles'));
        } else {
            // Nota: Asegúrate de que esta vista exista o usa la misma 'cliente.tienda' si comparten diseño
            return view('visita.tienda', compact('productos', 'categorias', 'carruseles'));
        }
    }
}