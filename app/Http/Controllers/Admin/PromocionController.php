<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promocion;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PromocionController extends Controller
{
    public function index(Request $request)
    {
        $filtro = $request->get('filtro', 'todos'); // todos, con_promocion, sin_promocion
        
        $query = Producto::with(['promocionActiva', 'ultimaPromocion']);
        
        switch ($filtro) {
            case 'con_promocion':
                $query->whereHas('promocionActiva');
                break;
            case 'sin_promocion':
                $query->whereDoesntHave('promocionActiva');
                break;
            case 'todos':
            default:
                // Mostrar todos los productos
                break;
        }
        
        $productos = $query->orderByDesc('id_producto')->paginate(10);
        
        // Estadísticas para mostrar en la vista
        $stats = [
            'total_productos' => Producto::count(),
            'con_promocion' => Producto::whereHas('promocionActiva')->count(),
            'sin_promocion' => Producto::whereDoesntHave('promocionActiva')->count(),
        ];
        
        return view('admin.promociones.index', compact('productos', 'filtro', 'stats'));
    }

    public function create(Request $request)
    {
        $idProd = $request->query('id_producto');
        if (!$idProd) {
            Session::flash('error', 'Selecciona un producto desde la lista para crear una promoción.');
            return redirect()->route('admin.promociones.index');
        }
        
        try {
            $producto = Producto::where('stock', '>', 0)->findOrFail($idProd);
            return view('admin.promociones.create', compact('producto'));
        } catch (\Exception $e) {
            Session::flash('error', 'Producto no encontrado o sin stock disponible.');
            return redirect()->route('admin.promociones.index');
        }
    }

    public function store(Request $request)
    {
        // Debug: Log todos los datos recibidos
        Log::info('Store promotion request data:', $request->all());
        
        try {
            $data = $request->validate([
                'titulo' => 'required|string|max:150',
                'descripcion' => 'nullable|string',
                'tipo' => 'required|in:porcentaje,fijo',
                'valor' => 'required|numeric|min:0',
                'id_producto' => 'required|exists:productos,id_producto',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            ]);

            Log::info('Validated data:', $data);

            // Verificar que el producto tenga stock
            $producto = Producto::find($data['id_producto']);
            if (!$producto) {
                Log::error('Producto no encontrado: ' . $data['id_producto']);
                Session::flash('error', 'El producto seleccionado no existe.');
                return back()->withInput();
            }
            
            if ($producto->stock <= 0) {
                Log::error('Producto sin stock: ' . $producto->nombre);
                Session::flash('error', 'El producto seleccionado no tiene stock disponible.');
                return back()->withInput();
            }

            // Activar por defecto solo si la vigencia incluye hoy
            $isVigente = Carbon::now()->between(
                Carbon::parse($data['fecha_inicio'])->startOfDay(),
                Carbon::parse($data['fecha_fin'])->endOfDay()
            );
            $data['activa'] = $isVigente;

            Log::info('Creating promotion with data:', $data);
            $nueva = Promocion::create($data);

            // Si la nueva promoción está vigente y activa, desactivar otras promociones vigentes activas del mismo producto
            if ($isVigente) {
                Promocion::where('id_producto', $nueva->id_producto)
                    ->where('id_promocion', '!=', $nueva->id_promocion)
                    ->where('activa', true)
                    ->whereDate('fecha_inicio', '<=', Carbon::now()->toDateString())
                    ->whereDate('fecha_fin', '>=', Carbon::now()->toDateString())
                    ->update(['activa' => false]);
            }

            Session::flash('success', 'Promoción creada correctamente.');
            return redirect()->route('admin.promociones.index');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error:', $e->errors());
            Session::flash('error', 'Error en la validación: ' . implode(', ', $e->validator->errors()->all()));
            return back()->withInput()->withErrors($e->validator);
        } catch (\Exception $e) {
            Log::error('Error creating promotion: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            Session::flash('error', 'Error al crear la promoción: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    public function edit(Promocion $promocion)
    {
        $producto = $promocion->producto; // relación definida en el modelo
        return view('admin.promociones.edit', compact('promocion', 'producto'));
    }

    public function update(Request $request, Promocion $promocion)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:porcentaje,fijo',
            'valor' => 'required|numeric|min:0',
            'id_producto' => 'required|exists:productos,id_producto',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        // No modificar 'activa' en update; se gestiona con toggleStatus

        $promocion->update($data);

        // Si la promoción está activa y ahora la vigencia incluye hoy, asegurar unicidad sobre otras vigentes
        $now = Carbon::now();
        $vigenteAhora = $now->between(
            $promocion->fecha_inicio->copy()->startOfDay(),
            $promocion->fecha_fin->copy()->endOfDay()
        );
        if ($promocion->activa && $vigenteAhora) {
            Promocion::where('id_producto', $promocion->id_producto)
                ->where('id_promocion', '!=', $promocion->id_promocion)
                ->where('activa', true)
                ->whereDate('fecha_inicio', '<=', $now->toDateString())
                ->whereDate('fecha_fin', '>=', $now->toDateString())
                ->update(['activa' => false]);
        }

        Session::flash('success', 'Promoción actualizada correctamente.');
        return redirect()->route('admin.promociones.index');
    }

    public function destroy(Promocion $promocion)
    {
        try {
            $tituloPromocion = $promocion->titulo;
            $nombreProducto = $promocion->producto->nombre ?? 'Producto desconocido';
            
            $promocion->delete();
            
            Session::flash('success', "Promoción '{$tituloPromocion}' del producto '{$nombreProducto}' eliminada correctamente.");
            
            // Redirigir con filtro para mostrar solo productos con promoción si venía de ese filtro
            $filtro = request()->get('filtro', 'todos');
            return redirect()->route('admin.promociones.index', ['filtro' => $filtro]);
            
        } catch (\Illuminate\Database\QueryException $e) {
            // Error específico de base de datos
            if ($e->getCode() === '23000') {
                Log::error('Foreign key constraint error deleting promotion: ' . $e->getMessage());
                Session::flash('error', 'No se puede eliminar la promoción porque está siendo utilizada en otros registros.');
            } else {
                Log::error('Database error deleting promotion: ' . $e->getMessage());
                Session::flash('error', 'Error de base de datos al eliminar la promoción. Contacta al administrador del sistema.');
            }
        } catch (\Exception $e) {
            Log::error('Unexpected error deleting promotion: ' . $e->getMessage());
            Session::flash('error', 'Error inesperado al eliminar la promoción. Por favor, inténtalo de nuevo.');
        }
        
        return redirect()->route('admin.promociones.index');
    }

    public function toggleStatus(Promocion $promocion)
    {
        $promocion->activa = !$promocion->activa;
        $promocion->save();

        // Si se activó y está vigente actualmente, desactivar otras promociones vigentes activas del mismo producto
        if ($promocion->activa) {
            $now = Carbon::now();
            $vigenteAhora = $now->between(
                $promocion->fecha_inicio->copy()->startOfDay(),
                $promocion->fecha_fin->copy()->endOfDay()
            );
            if ($vigenteAhora) {
                Promocion::where('id_producto', $promocion->id_producto)
                    ->where('id_promocion', '!=', $promocion->id_promocion)
                    ->where('activa', true)
                    ->whereDate('fecha_inicio', '<=', $now->toDateString())
                    ->whereDate('fecha_fin', '>=', $now->toDateString())
                    ->update(['activa' => false]);
            }
        }
        Session::flash('success', 'Estado de la promoción actualizado.');
        return back();
    }

    public function bulkDelete(Request $request)
    {
        try {
            // Decodificar el JSON si viene como string
            $ids = $request->input('ids');
            if (is_string($ids)) {
                $ids = json_decode($ids, true);
            }

            $request->merge(['ids' => $ids]);
            
            $request->validate([
                'ids' => 'required|array|min:1',
                'ids.*' => 'exists:promociones,id_promocion',
            ]);

            // Obtener información de las promociones antes de eliminarlas
            $promociones = Promocion::with('producto')->whereIn('id_promocion', $ids)->get();
            $count = $promociones->count();
            
            // Eliminar las promociones
            Promocion::destroy($ids);
            
            Session::flash('success', "Se eliminaron {$count} promoción" . ($count > 1 ? 'es' : '') . " correctamente.");
            
            // Redirigir con filtro si existe
            $filtro = $request->get('filtro', 'todos');
            return redirect()->route('admin.promociones.index', ['filtro' => $filtro]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Session::flash('error', 'Error en la validación: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error in bulk delete: ' . $e->getMessage());
            Session::flash('error', 'Error al eliminar las promociones seleccionadas.');
        }
        
        return back();
    }
}
