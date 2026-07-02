<?php

namespace App\Services;

use App\Events\EmergencyDonationReceived;
use App\Models\EmergencyCampaign;
use App\Models\EmergencyDonation;

class EmergencyDonationService
{
    public function __construct(
        protected GeoIPService $geoIP,
        protected CurrencyRateService $currency,
    ) {}

    public function donate(EmergencyCampaign $campaign, array $data, string $ip): EmergencyDonation
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
            'payment_status' => 'completed',
            'message' => isset($data['message']) ? strip_tags($data['message']) : null,
            'ip_address' => $ip,
            'user_agent' => request()->userAgent(),
            'donor_country' => $location['country'],
            'donor_city' => $location['city'],
            'donor_latitude' => $location['latitude'],
            'donor_longitude' => $location['longitude'],
        ]);

        $newTotal = EmergencyDonation::where('emergency_campaign_id', $campaign->id)
            ->where('payment_status', 'completed')
            ->sum('converted_amount');

        $donorCount = EmergencyDonation::where('emergency_campaign_id', $campaign->id)
            ->where('payment_status', 'completed')
            ->distinct('donor_email')
            ->count('donor_email');

        broadcast(new EmergencyDonationReceived($donation, $newTotal, $donorCount));

        return $donation;
    }
}
