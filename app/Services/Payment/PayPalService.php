<?php

namespace App\Services\Payment;

use App\Models\Donation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class PayPalService
{
    protected array $config;
    protected string $baseUrl;

    /**
     * @param array $config Must contain 'client_id', 'client_secret'. Optionally 'mode' (sandbox|live), 'webhook_id'.
     */
    public function __construct(array $config)
    {
        if (empty($config['client_id']) || empty($config['client_secret'])) {
            throw new RuntimeException('PayPal client ID and secret must be configured');
        }
        $this->config = $config;
        $this->baseUrl = ($config['mode'] ?? 'sandbox') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }

    /**
     * Get a PayPal OAuth 2.0 access token using client credentials.
     * @return string|null The access token, or null on failure.
     */
    protected function getAccessToken(): ?string
    {
        $response = Http::withBasicAuth(
            $this->config['client_id'] ?? '',
            $this->config['client_secret'] ?? ''
        )->asForm()->post("{$this->baseUrl}/v1/oauth2/token", [
            'grant_type' => 'client_credentials',
        ]);

        return $response->successful() ? $response->json('access_token') : null;
    }

    /**
     * Create a PayPal order for a donation and return the approval URL.
     * Stores the order ID as transaction_id on the donation.
     * @return string|null PayPal approval URL, or null on failure.
     */
    public function createOrder(Donation $donation): ?string
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return null;
        }

        $response = Http::withToken($token)->post("{$this->baseUrl}/v2/checkout/orders", [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'reference_id' => (string) $donation->id,
                'description' => 'تبرع - ' . $donation->donor_name,
                'amount' => [
                    'currency_code' => $donation->currency,
                    'value' => number_format($donation->amount, 2, '.', ''),
                ],
            ]],
            'payment_source' => [
                'paypal' => [
                    'experience_context' => [
                        'return_url' => route('payment.success', ['locale' => $donation->locale, 'donation' => $donation->id], true),
                        'cancel_url' => route('payment.cancel', ['locale' => $donation->locale, 'donation' => $donation->id, 'token' => $donation->access_token], true),
                    ],
                ],
            ],
        ]);

        if (!$response->successful()) {
            return null;
        }

        $data = $response->json();
        $donation->update(['transaction_id' => $data['id'] ?? null]);

        foreach ($data['links'] ?? [] as $link) {
            if (($link['rel'] ?? '') === 'payer-action') {
                return $link['href'];
            }
        }

        return null;
    }

    /**
     * Capture a PayPal order that has been approved by the donor.
     * @return array|null The capture response, or null on failure.
     */
    public function captureOrder(string $orderId): ?array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return null;
        }

        $response = Http::withToken($token)->post(
            "{$this->baseUrl}/v2/checkout/orders/{$orderId}/capture"
        );

        return $response->successful() ? $response->json() : null;
    }

    /**
     * Verify a PayPal webhook signature by calling the PayPal verification API.
     * Requires webhook_id in config.
     * @return bool True if the signature is valid and verified by PayPal.
     */
    public function verifyWebhookSignature(string $payload, array $headers): bool
    {
        $webhookId = $this->config['webhook_id'] ?? '';
        if (empty($webhookId)) {
            throw new RuntimeException('PayPal webhook ID is not configured');
        }

        $token = $this->getAccessToken();
        if (!$token) {
            return false;
        }

        $response = Http::withToken($token)->post(
            "{$this->baseUrl}/v1/notifications/verify-webhook-signature",
            [
                'auth_algo' => $headers['PAYPAL-AUTH-ALGO'] ?? '',
                'cert_url' => $headers['PAYPAL-CERT-URL'] ?? '',
                'transmission_id' => $headers['PAYPAL-TRANSMISSION-ID'] ?? '',
                'transmission_sig' => $headers['PAYPAL-TRANSMISSION-SIG'] ?? '',
                'transmission_time' => $headers['PAYPAL-TRANSMISSION-TIME'] ?? '',
                'webhook_id' => $webhookId,
                'webhook_event' => json_decode($payload, true),
            ]
        );

        $result = $response->successful() ? $response->json() : [];
        $verified = ($result['verification_status'] ?? '') === 'SUCCESS';

        if (!$verified) {
            Log::warning('PayPal webhook signature verification failed', [
                'status' => $result['verification_status'] ?? 'unknown',
            ]);
        }

        return $verified;
    }
}
