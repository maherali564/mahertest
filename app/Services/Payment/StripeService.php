<?php

namespace App\Services\Payment;

use App\Models\Donation;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeService
{
    protected array $config;

    /**
     * @param array $config Must contain 'secret_key', 'publishable_key', and optionally 'webhook_secret'.
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        if (empty($config['secret_key'])) {
            throw new RuntimeException('Stripe secret key is not configured');
        }
        Stripe::setApiKey($config['secret_key']);
    }

    /**
     * Create a Stripe Checkout Session for a donation.
     * Supports one-time payments and recurring (subscription) donations.
     * Stores the session ID as transaction_id on the donation.
     * @return string The Checkout Session URL to redirect the donor to.
     */
    public function createCheckoutSession(Donation $donation): string
    {
        if (empty($this->config['publishable_key'])) {
            throw new RuntimeException('Stripe publishable key is not configured');
        }

        $unitAmount = (int) round($donation->amount * 100);
        Log::info('Creating Stripe Checkout Session', [
            'donation_id' => $donation->id,
            'amount' => $donation->amount,
            'unit_amount_cents' => $unitAmount,
            'currency' => $donation->currency,
        ]);

        $sessionParams = [
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => strtolower($donation->currency),
                    'product_data' => ['name' => 'تبرع - ' . $donation->donor_name],
                    'unit_amount' => $unitAmount,
                ],
                'quantity' => 1,
            ]],
            'success_url' => route('payment.success', ['locale' => $donation->locale, 'donation' => $donation->id], true),
            'cancel_url' => route('payment.cancel', ['locale' => $donation->locale, 'donation' => $donation->id, 'token' => $donation->access_token], true),
            'metadata' => ['donation_id' => $donation->id],
        ];

        if ($donation->is_recurring && $donation->recurring_interval) {
            $intervalMap = [
                'monthly' => 'month',
                'quarterly' => 'month',
                'yearly' => 'year',
            ];
            $intervalCountMap = [
                'monthly' => 1,
                'quarterly' => 3,
                'yearly' => 1,
            ];
            $interval = $intervalMap[$donation->recurring_interval] ?? 'month';
            $intervalCount = $intervalCountMap[$donation->recurring_interval] ?? 1;

            $sessionParams['mode'] = 'subscription';
            $sessionParams['line_items'][0]['price_data']['recurring'] = [
                'interval' => $interval,
                'interval_count' => $intervalCount,
            ];
            unset($sessionParams['payment_method_types']);
            $sessionParams['subscription_data'] = [
                'metadata' => ['donation_id' => $donation->id],
            ];
        } else {
            $sessionParams['mode'] = 'payment';
        }

        $session = Session::create($sessionParams);

        $updateData = ['transaction_id' => $session->id];
        if ($donation->is_recurring && isset($session->subscription)) {
            $updateData['stripe_subscription_id'] = $session->subscription;
        }
        $donation->update($updateData);

        return $session->url;
    }

    /**
     * Verify a Stripe webhook signature using the configured webhook secret.
     * @return array The parsed Stripe event, or empty array on failure.
     */
    public function verifyWebhook(string $payload, string $sigHeader): array
    {
        $endpointSecret = $this->config['webhook_secret'] ?? '';
        if (empty($endpointSecret)) {
            throw new RuntimeException('Stripe webhook secret is not configured');
        }

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
            return $event->toArray();
        } catch (\Exception) {
            return [];
        }
    }
}
