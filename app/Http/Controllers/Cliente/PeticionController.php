<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Peticion;
use App\Models\Pedido;
use App\Models\Pago;
use Illuminate\Support\Facades\DB;
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
            // Evitar que se duplique la misma petición si el usuario envía
            // el formulario dos veces muy rápido con los mismos datos.
            $existing = Peticion::where('id_usuario', $user->id_usuario)
                ->where('titulo', $data['titulo'])
                ->where('descripcion', $data['descripcion'])
                ->where('estado', 'en revisión')
                ->where('created_at', '>=', now()->subMinutes(1))
                ->first();

            if ($existing) {
                return redirect()
                    ->route('cliente.peticiones.index')
                    ->with('success', 'Ya habías enviado una petición igual recientemente. Revisa tu lista de peticiones.');
            }

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

    /**
     * Simular pago con tarjeta para una petición aceptada por el cliente.
     */
    public function pagarTarjeta(Request $request, Peticion $peticion)
    {
        $user = Auth::user();

        // Verificar que la petición pertenece al usuario
        if ($peticion->id_usuario !== $user->id_usuario) {
            abort(403);
        }

        // Verificar que hay una propuesta del admin y que el cliente aún no ha respondido
        if (!$peticion->precio_propuesto || !$peticion->respuesta_admin || $peticion->respuesta_cliente !== 'pendiente') {
            return back()->with('error', 'La petición no está disponible para pago.');
        }

        $request->validate([
            'card_name'   => 'required|string|max:255',
            'card_number' => 'required|string|min:13|max:19',
            'card_expiry' => 'required|string|max:5',
            'card_cvv'    => 'required|string|min:3|max:4',
        ]);

        try {
            $monto = (float) $peticion->precio_propuesto;

            $pedido = DB::transaction(function () use ($user, $peticion, $monto) {
                // Crear pedido vinculado a la petición
                $pedido = Pedido::create([
                    'id_usuario'        => $user->id_usuario,
                    'id_peticion'       => $peticion->id_peticion,
                    'invoice_id'        => 'CARD-PET-' . $peticion->id_peticion . '-' . Str::uuid()->toString(),
                    'total'             => $monto,
                    'estado'            => 'procesando',
                    'calle'             => $peticion->calle,
                    'colonia'           => $peticion->colonia,
                    'municipio_ciudad'  => $peticion->municipio_ciudad,
                    'codigo_postal'     => $peticion->codigo_postal,
                    'estado_direccion'  => $peticion->estado_direccion,
                    'metodo_pago'       => 'tarjeta',
                    'fecha_pedido'      => now(),
                ]);

                // Registrar pago simulado con tarjeta
                Pago::create([
                    'id_pedido'  => $pedido->id_pedido,
                    'metodo'     => 'tarjeta',
                    'monto'      => $monto,
                    'referencia' => 'CARD-' . $pedido->id_pedido,
                    'estado'     => 'completado',
                ]);

                // Actualizar petición: cliente aceptó y se completó
                $peticion->update([
                    'respuesta_cliente'       => 'aceptada',
                    'fecha_respuesta_cliente' => now(),
                    'estado'                  => 'completada',
                ]);

                return $pedido;
            });

            return redirect()->route('cliente.pedidos.show', $pedido->id_pedido)
                ->with('success', 'Pago con tarjeta registrado. Tu pedido personalizado está en proceso.');
        } catch (\Throwable $e) {
            Log::error('Error en pago con tarjeta de petición: ' . $e->getMessage(), [
                'peticion_id' => $peticion->id_peticion ?? null,
                'user_id'     => $user->id_usuario ?? null,
            ]);

            return back()->with('error', 'Ocurrió un error al procesar el pago. Intenta de nuevo.');
        }
    }
}
