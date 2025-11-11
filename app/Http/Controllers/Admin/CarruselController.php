<?php

namespace App\Http\Controllers\Admin;

use App\Models\Carrusel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CarruselController extends Controller
{
    /**
     * Mostrar lista de imágenes del carrusel
     */
    public function index()
    {
        $carruseles = Carrusel::orderBy('orden')->get();
        return view('admin.carrusel.index', compact('carruseles'));
    }

    /**
     * Mostrar formulario para crear nueva imagen del carrusel
     */
    public function create()
    {
        return view('admin.carrusel.create');
    }

    /**
     * Almacenar nueva imagen del carrusel
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'orden' => 'nullable|integer'
        ]);

        try {
            // Guardar imagen
            if ($request->hasFile('imagen')) {
                $archivo = $request->file('imagen');
                $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $archivo->move(public_path('uploads/carrusel'), $nombreArchivo);
                $validated['imagen'] = 'uploads/carrusel/' . $nombreArchivo;
            }

            Carrusel::create($validated);

            return redirect()->route('admin.carrusel.index')
                ->with('success', 'Imagen del carrusel creada exitosamente');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al guardar la imagen: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para editar imagen del carrusel
     */
    public function edit($id)
    {
        $carrusel = Carrusel::findOrFail($id);
        return view('admin.carrusel.edit', compact('carrusel'));
    }

    /**
     * Actualizar imagen del carrusel
     */
    public function update(Request $request, $id)
    {
        $carrusel = Carrusel::findOrFail($id);

        $validated = $request->validate([
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'orden' => 'nullable|integer'
        ]);

        try {
            // Si hay nueva imagen, eliminar la anterior
            if ($request->hasFile('imagen')) {
                $rutaAnterior = public_path($carrusel->imagen);
                if (file_exists($rutaAnterior)) {
                    unlink($rutaAnterior);
                }

                $archivo = $request->file('imagen');
                $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $archivo->move(public_path('uploads/carrusel'), $nombreArchivo);
                $validated['imagen'] = 'uploads/carrusel/' . $nombreArchivo;
            }

            $carrusel->update($validated);

            return redirect()->route('admin.carrusel.index')
                ->with('success', 'Imagen del carrusel actualizada exitosamente');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al actualizar la imagen: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar imagen del carrusel
     */
    public function destroy($id)
    {
        try {
            $carrusel = Carrusel::findOrFail($id);
            
            // Eliminar archivo físico
            $rutaArchivo = public_path($carrusel->imagen);
            if (file_exists($rutaArchivo)) {
                unlink($rutaArchivo);
            }

            $carrusel->delete();

            return redirect()->route('admin.carrusel.index')
                ->with('success', 'Imagen del carrusel eliminada exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la imagen: ' . $e->getMessage());
        }
    }
}
