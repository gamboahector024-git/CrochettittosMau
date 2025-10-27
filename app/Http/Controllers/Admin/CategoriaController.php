<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::paginate(10);
        return view('admin.categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('admin.categorias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string'
        ]);

        Categoria::create($request->all());
        
        Session::flash('success', 'Categoría creada correctamente');
        return redirect()->route('admin.categorias.index');
    }

    public function edit($id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('admin.categorias.edit', compact('categoria'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string'
        ]);

        $categoria = Categoria::findOrFail($id);
        $categoria->update($request->all());
        
        Session::flash('success', 'Categoría actualizada correctamente');
        return redirect()->route('admin.categorias.index');
    }

    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->delete();
        
        Session::flash('success', 'Categoría eliminada correctamente');
        return redirect()->route('admin.categorias.index');
    }

    /**
     * Activa o desactiva una categoría.
     */
    public function toggleStatus(Categoria $categoria)
    {
        // Asume una columna booleana 'activa'
        $categoria->activa = !$categoria->activa;
        $categoria->save();

        Session::flash('success', 'Estado de la categoría actualizado correctamente.');
        return back();
    }

    /**
     * Elimina múltiples categorías en lote.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:categorias,id_categoria' // Valida que cada ID exista
        ]);

        Categoria::destroy($request->ids);

        Session::flash('success', 'Categorías seleccionadas eliminadas correctamente.');
        return back();
    }
}
