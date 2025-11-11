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

class PeticionController extends Controller
{
    /**
     * Store a newly created Peticion from authenticated user.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'imagen_referencia' => 'nullable|image|max:2048',
        ]);

        $data = [
            'id_usuario' => $user->id_usuario,
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'],
            'estado' => 'en revisión',
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

            return back()->with('success', 'Tu petición ha sido enviada.');
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
        $peticiones = Peticion::where('id_usuario', $user->id_usuario)
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

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
}
