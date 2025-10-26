<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->input('q');
        $categoria = $request->input('categoria');

        $query = Producto::query();

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('nombre', 'like', "%{$q}%")
                    ->orWhere('descripcion', 'like', "%{$q}%");
            });
        }

        if ($categoria) {
            $query->where('categoria', $categoria);
        }

        $productos = $query->latest()->paginate(10)->withQueryString();

        return view('admin.productos.index', compact('productos', 'q', 'categoria'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.productos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('productos', 'public');
            $data['imagen_url'] = 'storage/' . $path;
        }

        unset($data['imagen']);

        $data['stock'] = $request->input('stock'); // Agregar stock

        Producto::create($data);

        Session::flash('success', 'Producto creado correctamente');
        return redirect()->route('admin.productos.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        return view('admin.productos.edit', compact('producto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Producto $producto)
    {
        $data = $request->validated();

        if ($request->hasFile('imagen')) {
            if ($producto->imagen_url) {
                $relative = str_starts_with($producto->imagen_url, 'storage/') ? substr($producto->imagen_url, 8) : $producto->imagen_url;
                Storage::disk('public')->delete($relative);
            }
            $path = $request->file('imagen')->store('productos', 'public');
            $data['imagen_url'] = 'storage/' . $path;
        }

        unset($data['imagen']);

        $data['stock'] = $request->input('stock'); // Agregar stock

        $producto->update($data);

        Session::flash('success', 'Producto actualizado correctamente');
        return redirect()->route('admin.productos.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        if ($producto->imagen_url) {
            $relative = str_starts_with($producto->imagen_url, 'storage/') ? substr($producto->imagen_url, 8) : $producto->imagen_url;
            Storage::disk('public')->delete($relative);
        }

        $producto->delete();

        Session::flash('success', 'Producto eliminado correctamente');
        return redirect()->route('admin.productos.index');
    }
}
