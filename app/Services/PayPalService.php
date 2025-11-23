<?php

namespace App\Services;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalHttp\HttpRequest;

class PayPalService
{
    private $client;

    public function __construct()
    {
        $mode = config('paypal.settings.mode');
        $clientId = config('paypal.client_id');
        $clientSecret = config('paypal.client_secret');

        $environment = $mode === 'live'
            ? new ProductionEnvironment($clientId, $clientSecret)
            : new SandboxEnvironment($clientId, $clientSecret);

        $this->client = new PayPalHttpClient($environment);
    }

    public function createOrder($amount, $invoiceId = null)
    {
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        
        $purchaseUnit = [
            'amount' => [
                'currency_code' => 'MXN',
                'value' => $amount
            ]
        ];
        
        // Agregar invoice_id si se proporciona
        if ($invoiceId) {
            $purchaseUnit['invoice_id'] = $invoiceId;
        }
        
        $request->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [$purchaseUnit],
            'application_context' => [
                'return_url' => url('/paypal/return'),
                'cancel_url' => url('/paypal/cancel'),
                'brand_name' => 'Crochettittos',
                'user_action' => 'PAY_NOW',
                'shipping_preference' => 'NO_SHIPPING'
            ]
        ];

        return $this->client->execute($request);
    }

    public function captureOrder($orderId)
    {
        $request = new OrdersCaptureRequest($orderId);
        return $this->client->execute($request);
    }

    /**
     * Verifica la firma del webhook de PayPal
     * 
     * @param array $headers Cabeceras del request
     * @param string $body Body del request (JSON string)
     * @return bool
     */
    public function verifyWebhookSignature(array $headers, string $body): bool
    {
        $webhookId = config('paypal.webhook_id');
        
        // Si no hay webhook_id configurado, saltar verificación en desarrollo
        if (!$webhookId) {
            \Log::warning('PayPal webhook_id no configurado, saltando verificación de firma');
            return true;
        }

        try {
            $request = new HttpRequest('/v1/notifications/verify-webhook-signature', 'POST');
            $request->headers = ['Content-Type' => 'application/json'];
            
            $request->body = json_encode([
                'auth_algo' => $headers['paypal-auth-algo'] ?? $headers['PAYPAL-AUTH-ALGO'] ?? null,
                'cert_url' => $headers['paypal-cert-url'] ?? $headers['PAYPAL-CERT-URL'] ?? null,
                'transmission_id' => $headers['paypal-transmission-id'] ?? $headers['PAYPAL-TRANSMISSION-ID'] ?? null,
                'transmission_sig' => $headers['paypal-transmission-sig'] ?? $headers['PAYPAL-TRANSMISSION-SIG'] ?? null,
                'transmission_time' => $headers['paypal-transmission-time'] ?? $headers['PAYPAL-TRANSMISSION-TIME'] ?? null,
                'webhook_id' => $webhookId,
                'webhook_event' => json_decode($body, true),
            ]);

            $response = $this->client->execute($request);
            $verification = $response->result->verification_status ?? 'FAILURE';
            
            return $verification === 'SUCCESS';
        } catch (\Exception $e) {
            \Log::error('Error verificando firma de webhook PayPal', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
