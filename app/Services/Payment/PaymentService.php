<?php

namespace App\Services\Payment;

use App\Models\Donation;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class PaymentService
{
    protected ?PaymentGateway $gateway;
    protected array $config;

    /**
     * @param PaymentGateway $gateway The active gateway with its config (keys, endpoints, etc.).
     */
    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
        $this->config = $gateway->config ?? [];
    }

    /**
     * Factory: create a PaymentService instance from a donation's payment method gateway.
     * @throws RuntimeException if the donation has no associated gateway.
     */
    public static function fromDonation(Donation $donation): self
    {
        $gateway = $donation->paymentMethod?->gateway;

        if (!$gateway) {
            Log::warning('Payment gateway not found for donation', ['donation_id' => $donation->id]);
            throw new RuntimeException(__('payment.gateway_not_found'));
        }

        return new static($gateway);
    }

    /**
     * Initialize a payment by dispatching to the correct gateway handler.
     * @return array ['type' => 'redirect'|'instructions', 'url'? => string, 'data'? => array, 'message' => string]
     */
    public function initPayment(Donation $donation): array
    {
        $driver = $this->gateway->driver;

        return match ($driver) {
            'stripe' => $this->initStripe($donation),
            'paypal' => $this->initPayPal($donation),
            default => throw new RuntimeException("بوابة دفع غير مدعومة: $driver"),
        };
    }

    /**
     * Redirect the donor to Stripe Checkout.
     */
    protected function initStripe(Donation $donation): array
    {
        $service = new StripeService($this->config);
        $url = $service->createCheckoutSession($donation);

        return [
            'type' => 'redirect',
            'url' => $url,
            'message' => 'جاري تحويلك إلى بوابة الدفع Stripe...',
        ];
    }

    /**
     * Redirect the donor to PayPal for approval.
     * @throws RuntimeException if PayPal order creation fails.
     */
    protected function initPayPal(Donation $donation): array
    {
        $service = new PayPalService($this->config);
        $url = $service->createOrder($donation);

        if (!$url) {
            throw new RuntimeException("فشل الاتصال ببوابة PayPal");
        }

        return [
            'type' => 'redirect',
            'url' => $url,
            'message' => 'جاري تحويلك إلى بوابة الدفع PayPal...',
        ];
    }

}
