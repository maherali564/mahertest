<?php

namespace App\Services;

use App\Events\EmergencyDonationReceived;
use App\Models\EmergencyCampaign;
use App\Models\EmergencyDonation;
use App\Models\PaymentGateway;
use App\Services\Payment\StripeService;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class EmergencyDonationService
{
    public function __construct(
        protected GeoIPService $geoIP,
        protected CurrencyRateService $currency,
    ) {}

    /**
     * @return array{donation: EmergencyDonation, checkout_url: ?string}
     */
    public function donate(EmergencyCampaign $campaign, array $data, string $ip): array
    {
        $convertedAmount = $this->currency->convert(
            (float) $data['amount'],
            $data['currency'] ?? 'USD',
            $campaign->currency
        );

        $location = $this->geoIP->getLocation($ip);

        $donation = EmergencyDonation::create([
            'emergency_campaign_id' => $campaign->id,
            'donor_name' => strip_tags($data['donor_name']),
            'donor_email' => $data['donor_email'],
            'amount' => $data['amount'],
            'converted_amount' => $convertedAmount,
            'currency' => $data['currency'] ?? 'USD',
            'payment_method' => 'stripe',
            'payment_status' => 'pending',
            'is_anonymous' => $data['is_anonymous'] ?? false,
            'message' => isset($data['message']) ? strip_tags($data['message']) : null,
            'ip_address' => $ip,
            'user_agent' => request()->userAgent(),
            'donor_country' => $location['country'],
            'donor_city' => $location['city'],
            'donor_latitude' => $location['latitude'],
            'donor_longitude' => $location['longitude'],
        ]);

        $checkoutUrl = $this->createCheckoutSession($donation);

        return [
            'donation' => $donation,
            'checkout_url' => $checkoutUrl,
        ];
    }

    private function createCheckoutSession(EmergencyDonation $donation): ?string
    {
        $gateway = PaymentGateway::where('driver', 'stripe')->where('is_active', true)->first();
        if (!$gateway) {
            Log::warning('EmergencyDonation: no active Stripe gateway found, donation saved as pending');
            return null;
        }

        $config = $gateway->config ?? [];
        if (empty($config['secret_key'])) {
            Log::warning('EmergencyDonation: Stripe secret key not configured');
            return null;
        }

        try {
            $service = new StripeService($config);

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => strtolower($donation->currency),
                        'product_data' => ['name' => 'تبرع لـ ' . $campaignTitle($donation->campaign->getTranslation('title', app()->getLocale(), false) ?: 'حملة طوارئ')],
                        'unit_amount' => (int) round($donation->amount * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('emergency-campaigns.show', [
                    'locale' => app()->getLocale(),
                    'campaign' => $donation->campaign->slug,
                ], true) . '?donation=success',
                'cancel_url' => route('emergency-campaigns.show', [
                    'locale' => app()->getLocale(),
                    'campaign' => $donation->campaign->slug,
                ], true),
                'metadata' => [
                    'emergency_donation_id' => (string) $donation->id,
                    'type' => 'emergency',
                ],
            ]);

            $donation->update([
                'stripe_session_id' => $session->id,
                'payment_method' => 'stripe',
            ]);

            Log::info('EmergencyDonation Stripe session created', [
                'donation_id' => $donation->id,
                'session_id' => $session->id,
                'amount' => $donation->amount,
                'currency' => $donation->currency,
            ]);

            return $session->url;
        } catch (\Exception $e) {
            Log::error('EmergencyDonation Stripe session failed', [
                'donation_id' => $donation->id,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}

if (!function_exists('campaignTitle')) {
    function campaignTitle($title) {
        return $title ?: 'حملة طوارئ';
    }
}
