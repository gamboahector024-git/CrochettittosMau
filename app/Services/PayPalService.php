<?php

namespace App\Services;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

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

    public function createOrder($amount)
    {
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => 'MXN',
                    'value' => $amount
                ]
            ]],
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
}
