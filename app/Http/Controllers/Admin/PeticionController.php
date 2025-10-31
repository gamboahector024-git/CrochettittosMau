<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peticion;
use App\Models\Pedido;
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
        $peticion->respuesta_admin = $request->respuesta_admin;
        $peticion->estado = $request->estado;
        $peticion->save();

        Session::flash('success', 'Respuesta y estado actualizados');
        return back();
    }
    
    public function toggleStatus(Peticion $peticion)
    {
        // Alternar entre en revisión <-> rechazada (como "archivado")
        if ($peticion->estado === 'rechazada') {
            $peticion->estado = 'en revisión';
        } else {
            $peticion->estado = 'rechazada';
        }
        $peticion->save();

        Session::flash('success', 'Estado de la petición actualizado');
        return back();
    }

    public function completar(Peticion $peticion)
    {
        if ($peticion->estado === 'completada') {
            Session::flash('success', 'La petición ya estaba completada.');
            return back();
        }
        if ($peticion->estado !== 'aceptada') {
            Session::flash('error', 'Solo se puede completar una petición que esté en estado ACEPTADA.');
            return back();
        }

        $peticion->estado = 'completada';
        $peticion->save();

        $direccion = optional($peticion->usuario)->direccion ?: 'Sin dirección especificada';
        \App\Models\Pedido::create([
            'id_usuario' => $peticion->id_usuario,
            'id_peticion' => $peticion->id_peticion,
            'total' => 0,
            'estado' => 'pendiente',
            'direccion_envio' => $direccion,
            'metodo_pago' => null,
            'empresa_envio' => null,
            'codigo_rastreo' => null,
            'fecha_envio' => null,
            'fecha_entrega_estimada' => null,
        ]);

        Session::flash('success', 'Petición completada y pedido generado.');
        return back();
    }

    public function bulkStatus(BulkStatusPeticionRequest $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:peticiones,id_peticion',
            'estado' => 'required|in:en revisión,aceptada,rechazada'
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