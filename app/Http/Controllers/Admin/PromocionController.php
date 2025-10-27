<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promocion;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class PromocionController extends Controller
{
    public function index()
    {
        $productos = Producto::with(['promocionActiva'])
            ->orderByDesc('id_producto')
            ->paginate(10);
        return view('admin.promociones.index', compact('productos'));
    }

    public function create()
    {
        $productos = Producto::where('stock', '>', 0)->get();
        return view('admin.promociones.create', compact('productos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:porcentaje,fijo',
            'valor' => 'required|numeric|min:0',
            'id_producto' => [
                'required',
                Rule::exists('productos', 'id_producto')->where(function ($q) {
                    $q->where('stock', '>', 0);
                }),
            ],
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'activa' => 'boolean',
        ]);

        Promocion::create($data);

        Session::flash('success', 'Promoción creada correctamente.');
        return redirect()->route('admin.promociones.index');
    }

    public function edit(Promocion $promocion)
    {
        $productos = Producto::where('stock', '>', 0)->get();
        return view('admin.promociones.edit', compact('promocion', 'productos'));
    }

    public function update(Request $request, Promocion $promocion)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:porcentaje,fijo',
            'valor' => 'required|numeric|min:0',
            'id_producto' => [
                'required',
                Rule::exists('productos', 'id_producto')->where(function ($q) {
                    $q->where('stock', '>', 0);
                }),
            ],
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'activa' => 'boolean',
        ]);

        $promocion->update($data);

        Session::flash('success', 'Promoción actualizada correctamente.');
        return redirect()->route('admin.promociones.index');
    }

    public function destroy(Promocion $promocion)
    {
        $promocion->delete();
        Session::flash('success', 'Promoción eliminada correctamente.');
        return redirect()->route('admin.promociones.index');
    }

    public function toggleStatus(Promocion $promocion)
    {
        $promocion->activa = !$promocion->activa;
        $promocion->save();
        Session::flash('success', 'Estado de la promoción actualizado.');
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:promociones,id_promocion',
        ]);

        Promocion::destroy($request->ids);
        Session::flash('success', 'Promociones seleccionadas eliminadas.');
        return back();
    }
}
