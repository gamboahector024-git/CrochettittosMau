<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peticion;
use Illuminate\Http\Request;
use App\Http\Requests\UpdatePeticionRequest;
use App\Http\Requests\BulkStatusPeticionRequest;
use Illuminate\Support\Facades\Session;

class PeticionController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $estado = $request->input('estado');
        $from = $request->input('from');
        $to = $request->input('to');

        $query = Peticion::with('usuario')->latest();

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('titulo', 'like', "%{$q}%")
                    ->orWhere('descripcion', 'like', "%{$q}%")
                    ->orWhereHas('usuario', function($user) use ($q) {
                        $user->where('nombre', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%");
                    });
            });
        }

        if ($estado) {
            $query->where('estado', $estado);
        }

        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        $peticiones = $query->paginate(10)->withQueryString();
        return view('admin.peticiones.index', compact('peticiones', 'q', 'estado', 'from', 'to'));
    }

    public function show(Peticion $peticion)
    {
        return view('admin.peticiones.show', compact('peticion'));
    }

    public function responder(UpdatePeticionRequest $request, Peticion $peticion)
    {
        $request->validate([
            'respuesta_admin' => 'required|string',
            'estado' => 'required|in:pendiente,en revisión,aceptada,rechazada,completada'
        ]);

        $peticion->update([
            'respuesta_admin' => $request->respuesta_admin,
            'estado' => $request->estado
        ]);

        Session::flash('success', 'Respuesta y estado actualizados');
        return back();
    }
    
    public function toggleStatus(Peticion $peticion)
    {
        // Alternar entre pendiente <-> rechazada (como "archivado")
        if ($peticion->estado === 'rechazada') {
            $peticion->estado = 'pendiente';
        } else {
            $peticion->estado = 'rechazada';
        }
        $peticion->save();

        Session::flash('success', 'Estado de la petición actualizado');
        return back();
    }

    public function bulkStatus(BulkStatusPeticionRequest $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:peticiones,id_peticion',
            'estado' => 'required|in:pendiente,en revisión,aceptada,rechazada,completada'
        ]);

        Peticion::whereIn('id_peticion', $request->ids)
            ->update(['estado' => $request->estado]);

        Session::flash('success', 'Estado actualizado para las peticiones seleccionadas');
        return back();
    }

    public function destroy(Peticion $peticion)
    {
        try {
            $peticion->delete();
            Session::flash('success', 'Petición eliminada correctamente');
        } catch (\Exception $e) {
            Session::flash('error', 'Error al eliminar: '.$e->getMessage());
        }
        
        return back();
    }

    public function bulkDelete(Request $request)
    {
        // Normalizar ids: aceptar 'ids' como array o cadena CSV
        $ids = $request->input('ids', []);
        if (is_string($ids)) {
            $ids = array_filter(explode(',', $ids));
        }
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        $request->merge(['ids' => $ids]);

        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:peticiones,id_peticion'
        ]);

        $deleted = Peticion::whereIn('id_peticion', $validated['ids'])->delete();
        \Log::info('bulkDelete peticiones', ['ids' => $validated['ids'], 'deleted' => $deleted]);

        if ($deleted > 0) {
            Session::flash('success', $deleted.' peticiones eliminadas');
        } else {
            Session::flash('error', 'No se eliminó ninguna petición.');
        }
        return back();
    }
}