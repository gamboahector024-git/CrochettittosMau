<?php

namespace App\Http\Controllers\Admin;

use App\Models\Promocion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PromocionController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin');
    }

    public function index()
    {
        return Promocion::with(['productos'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|unique:promociones|max:50',
            'descuento' => 'required|numeric|min:0|max:100',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'estado' => 'required|in:activa,inactiva'
        ]);

        return Promocion::create($validated);
    }

    public function update(Request $request, Promocion $promocion)
    {
        $validated = $request->validate([
            'descuento' => 'sometimes|numeric|min:0|max:100',
            'fecha_fin' => 'sometimes|date|after:fecha_inicio',
            'estado' => 'sometimes|in:activa,inactiva'
        ]);

        $promocion->update($validated);
        return $promocion;
    }

    // Método para asignar productos a promoción
    public function asignarProductos(Promocion $promocion, Request $request)
    {
        $promocion->productos()->sync($request->productos);
        return $promocion->load('productos');
    }
}
