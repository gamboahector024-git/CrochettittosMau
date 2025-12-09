<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peticion;
use App\Models\Pedido;
use App\Mail\PeticionPropuesta;
use Illuminate\Http\Request;
use App\Http\Requests\UpdatePeticionRequest;
use App\Http\Requests\BulkStatusPeticionRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
        $peticion->load('categoria', 'usuario');
        return view('admin.peticiones.show', compact('peticion'));
    }

    public function responder(Request $request, Peticion $peticion)
    {
        $validated = $request->validate([
            'respuesta_admin' => 'required|string|max:5000',
            'precio_propuesto' => 'required|numeric|min:0.01|max:999999.99',
        ]);

        // Al responder con precio, el estado cambia automáticamente a "aceptada"
        $peticion->update([
            'respuesta_admin' => $validated['respuesta_admin'],
            'precio_propuesto' => $validated['precio_propuesto'],
            'fecha_respuesta_admin' => now(),
            'estado' => 'aceptada' // Cambia a aceptada cuando el admin envía propuesta
        ]);

        try {
            $peticion->loadMissing('usuario');

            if ($peticion->usuario && !empty($peticion->usuario->email)) {
                Mail::to($peticion->usuario->email)
                    ->send(new PeticionPropuesta($peticion));
            }
        } catch (\Throwable $e) {
            Log::error('Error al enviar correo de propuesta de petición', [
                'peticion_id' => $peticion->id_peticion ?? null,
                'user_id' => $peticion->id_usuario ?? null,
                'error' => $e->getMessage(),
            ]);
        }

        Session::flash('success', 'Propuesta enviada al cliente. Precio: $' . number_format($peticion->precio_propuesto, 2));
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

        $usuario = $peticion->usuario;
        \App\Models\Pedido::create([
            'id_usuario' => $peticion->id_usuario,
            'id_peticion' => $peticion->id_peticion,
            'total' => 0,
            'estado' => 'pendiente',
            'calle' => $usuario->calle ?? 'N/A',
            'colonia' => $usuario->colonia ?? 'N/A',
            'municipio_ciudad' => $usuario->municipio_ciudad ?? 'N/A',
            'codigo_postal' => $usuario->codigo_postal ?? 'N/A',
            'estado_direccion' => $usuario->estado_direccion ?? 'N/A',
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
        Log::info('Solicitud recibida en bulkStatus', ['request_data' => $request->all()]);
        
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:peticiones,id_peticion',
            'estado' => 'required|in:en revisión,aceptada,rechazada'
        ]);

        if (empty($validated['ids'])) {
            Session::flash('error', 'No se seleccionaron peticiones para actualizar.');
            return back();
        }

        $updated = Peticion::whereIn('id_peticion', $validated['ids'])
            ->update(['estado' => $validated['estado']]);

        if ($updated > 0) {
            Session::flash('success', 'Estado actualizado para ' . $updated . ' peticiones seleccionadas.');
        } else {
            Session::flash('error', 'No se pudo actualizar el estado de las peticiones seleccionadas.');
        }
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

        if (empty($validated['ids'])) {
            Session::flash('error', 'No se seleccionaron peticiones para eliminar.');
            return back();
        }

        $deleted = Peticion::whereIn('id_peticion', $validated['ids'])->delete();
        \Log::info('bulkDelete peticiones', ['ids' => $validated['ids'], 'deleted' => $deleted]);

        if ($deleted > 0) {
            Session::flash('success', $deleted . ' peticiones eliminadas.');
        } else {
            Session::flash('error', 'No se eliminó ninguna petición.');
        }
        return back();
    }
}