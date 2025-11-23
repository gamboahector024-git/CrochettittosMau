<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Peticion;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\NuevaPeticion;
use App\Models\Usuario;
use App\Http\Requests\StorePeticionRequest;

class PeticionController extends Controller
{
    /**
     * Store a newly created Peticion from authenticated user.
     */
    public function store(StorePeticionRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();

        $data = [
            'id_usuario' => $user->id_usuario,
            'id_categoria' => $validated['id_categoria'],
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'],
            'cantidad' => $validated['cantidad'],
            'calle' => $validated['calle'],
            'colonia' => $validated['colonia'],
            'municipio_ciudad' => $validated['municipio_ciudad'],
            'codigo_postal' => $validated['codigo_postal'],
            'estado_direccion' => $validated['estado_direccion'],
            'estado' => 'en revisión', // Estado inicial: en revisión
            'respuesta_cliente' => 'pendiente', // Cliente aún no ha respondido
        ];

        if ($request->hasFile('imagen_referencia')) {
            $file = $request->file('imagen_referencia');
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $filename = time() . '_' . Str::slug($name) . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads/peticiones');
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }
            $file->move($destination, $filename);
            $data['imagen_referencia'] = 'uploads/peticiones/' . $filename;
        }

        try {
            $peticion = Peticion::create($data);

            // Notificar por correo a los administradores
            try {
                $adminEmails = Usuario::where('rol', 'admin')->whereNotNull('email')->pluck('email')->unique()->toArray();
                if (!empty($adminEmails)) {
                    foreach ($adminEmails as $email) {
                        Mail::to($email)->send(new NuevaPeticion($peticion));
                    }
                } else {
                    Log::info('No admin emails found to notify for new peticion', ['peticion_id' => $peticion->id_peticion ?? null]);
                }
            } catch (\Throwable $mEx) {
                Log::error('Error sending nueva peticion emails: ' . $mEx->getMessage(), ['peticion_id' => $peticion->id_peticion ?? null]);
            }

            // Después de crear la petición, redirigimos a "Mis peticiones"
            return redirect()
                ->route('cliente.peticiones.index')
                ->with('success', 'Tu petición ha sido enviada.');
        } catch (\Exception $e) {
            Log::error('Error guardando petición: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al enviar la petición.');
        }
    }

    /**
     * Display a listing of the authenticated user's peticiones.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $peticiones = Peticion::with('categoria')
            ->where('id_usuario', $user->id_usuario)
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Agregar número de petición secuencial para el cliente
        $currentPage = $peticiones->currentPage();
        $perPage = $peticiones->perPage();
        $totalItems = Peticion::where('id_usuario', $user->id_usuario)->count();
        
        $peticiones->getCollection()->transform(function ($peticion, $index) use ($currentPage, $perPage, $totalItems) {
            $peticion->numero_peticion_cliente = $totalItems - ($currentPage - 1) * $perPage - $index;
            return $peticion;
        });

        return view('cliente.peticiones.index', compact('peticiones'));
    }

    /**
     * Display the specified peticion (only if belongs to the user).
     */
    public function show(Peticion $peticion)
    {
        $user = Auth::user();
        if ($peticion->id_usuario !== $user->id_usuario) {
            abort(403);
        }

        return view('cliente.peticiones.show', compact('peticion'));
    }

    /**
     * Rechazar la propuesta del admin
     */
    public function rechazar(Peticion $peticion)
    {
        $user = Auth::user();
        
        // Verificar que la petición pertenece al usuario
        if ($peticion->id_usuario !== $user->id_usuario) {
            abort(403);
        }

        // Verificar que hay una propuesta del admin
        if (!$peticion->precio_propuesto || !$peticion->respuesta_admin) {
            return back()->with('error', 'No hay propuesta para rechazar.');
        }

        // Verificar que no haya respondido ya
        if ($peticion->respuesta_cliente !== 'pendiente') {
            return back()->with('error', 'Ya has respondido a esta propuesta.');
        }

        $peticion->update([
            'respuesta_cliente' => 'rechazada',
            'fecha_respuesta_cliente' => now(),
            'estado' => 'rechazada'
        ]);

        Session::flash('success', 'Has rechazado la propuesta. Puedes crear una nueva petición si lo deseas.');
        return redirect()->route('cliente.peticiones.index');
    }
}
