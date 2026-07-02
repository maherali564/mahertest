<?php

namespace Database\Seeders;

use App\Models\PaymentGateway;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentGatewaySeeder extends Seeder
{
    public function run(): void
    {
        // Stripe
        $stripe = PaymentGateway::create([
            'name' => 'Stripe',
            'slug' => 'stripe',
            'driver' => 'stripe',
            'type' => 'traditional',
            'config' => [
                'publishable_key' => '',
                'secret_key' => '',
                'webhook_secret' => '',
            ],
            'supported_currencies' => ['USD', 'EUR', 'GBP'],
            'min_amount' => 1,
            'max_amount' => 50000,
            'processing_fee' => 2.9,
            'sort_order' => 1,
            'is_active' => true,
        ]);
        $stripe->paymentMethods()->create([
            'name' => 'بطاقة ائتمان / خصم',
            'description' => 'الدفع عبر بطاقة فيزا أو ماستركارد',
            'icon' => '💳',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // PayPal
        $paypal = PaymentGateway::create([
            'name' => 'PayPal',
            'slug' => 'paypal',
            'driver' => 'paypal',
            'type' => 'traditional',
            'config' => [
                'client_id' => '',
                'client_secret' => '',
                'mode' => 'sandbox',
            ],
            'supported_currencies' => ['USD', 'EUR', 'GBP'],
            'min_amount' => 1,
            'max_amount' => 50000,
            'processing_fee' => 2.9,
            'sort_order' => 2,
            'is_active' => true,
        ]);
        $paypal->paymentMethods()->create([
            'name' => 'PayPal',
            'description' => 'الدفع عبر حساب PayPal',
            'icon' => '🅿️',
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }
}
