<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pago;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Log;

class PagoController extends Controller
{
    // Obtener todos los pagos
    public function index()
    {
        return Pago::with('pedido')->get();
    }

    // Crear un nuevo pago
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pedido' => 'required|exists:pedidos,id_pedido',
            'metodo' => ['required', Rule::in(['tarjeta', 'paypal', 'efectivo'])],
            'monto' => 'required|numeric|min:0',
            'estado' => ['nullable', Rule::in(['pendiente', 'completado', 'fallido'])]
        ]);

        return DB::transaction(function () use ($validated) {
            return Pago::create($validated);
        });
    }

    // Obtener un pago específico
    public function show(Pago $pago)
    {
        return $pago->load('pedido');
    }

    // Actualizar un pago
    public function update(Request $request, Pago $pago)
    {
        $validated = $request->validate([
            'metodo' => ['sometimes', Rule::in(['tarjeta', 'paypal', 'efectivo'])],
            'monto' => 'sometimes|numeric|min:0',
            'estado' => ['sometimes', Rule::in(['pendiente', 'completado', 'fallido'])]
        ]);

        $pago->update($validated);
        return $pago->fresh();
    }

    // Eliminar un pago
    public function destroy(Pago $pago)
    {
        $pago->delete();
        return response()->noContent();
    }

    // Pagos de un pedido específico (endpoint adicional)
    public function byPedido(Pedido $pedido)
    {
        return $pedido->pagos;
    }


    public function createStripePaymentIntent(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100', // MXN en centavos
            // 'pedido_id' => 'required|exists:pedidos,id_pedido' // Eliminamos validación estricta por ahora
        ]);

        try {
            Log::info('Iniciando createStripePaymentIntent');
            $stripeSecret = config('services.stripe.secret');
            
            if (!$stripeSecret) {
                Log::error('Stripe Secret es nulo en config');
                throw new \Exception('La clave secreta de Stripe no está configurada o es nula.');
            }

            Log::info('Instanciando StripeClient');
            $stripe = new StripeClient($stripeSecret);

            Log::info('Creando PaymentIntent', ['amount' => $request->amount]);
            $intent = $stripe->paymentIntents->create([
                'amount' => $request->amount,
                'currency' => 'mxn',
                'metadata' => [
                    'pedido_id_temp' => $request->pedido_id // Guardamos como temporal
                ]
            ]);

            Log::info('PaymentIntent creado exitosamente');
            return response()->json([
                'clientSecret' => $intent->client_secret,
                'paymentIntentId' => $intent->id
            ]);

        } catch (\Throwable $e) {
            Log::error('Error critico en createStripePaymentIntent: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return response()->json(['error' => 'Error interno: ' . $e->getMessage()], 500);
        }
    }

    // Nuevo: Webhook Stripe
    public function handleStripeWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );
        } catch (\Exception $e) {
            Log::error('Stripe webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        // Manejar eventos importantes
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $this->handlePaymentSuccess($paymentIntent);
                break;
            // ... otros casos
        }

        return response()->json(['success' => true]);
    }

    protected function handlePaymentSuccess($paymentIntent)
    {
        // Registrar pago en tu DB
        Pago::create([
            'id_pedido' => $paymentIntent->metadata->pedido_id,
            'metodo' => 'stripe',
            'monto' => $paymentIntent->amount / 100, // Convertir a MXN
            'referencia' => $paymentIntent->id,
            'estado' => 'completado'
        ]);

        // Opcional: Actualizar estado del pedido
        Pedido::find($paymentIntent->metadata->pedido_id)
            ->update(['estado' => 'procesando']);
    }
}
