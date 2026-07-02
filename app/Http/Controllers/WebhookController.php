<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\PaymentGateway;
use App\Services\Payment\PayPalService;
use App\Services\Payment\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Check idempotency: return the donation if it exists and is not yet completed,
     * or null if the webhook should be skipped (already processed or missing ID).
     */
    private function getIdempotencyCheck(string $transactionId): ?Donation
    {
        if (empty($transactionId)) {
            Log::warning('Webhook missing transaction_id');
            return null;
        }

        $existing = Donation::where('transaction_id', $transactionId)->first();
        if ($existing && $existing->status === 'completed') {
            Log::info('Duplicate webhook ignored', ['transaction_id' => $transactionId]);
            return null;
        }

        return $existing;
    }

    /**
     * Verify that the amount received from the gateway matches the expected donation amount.
     * Returns false if they differ (integer comparison to avoid floating-point issues).
     */
    private function verifyAmountMatch(float $webhookAmount, Donation $donation): bool
    {
        if ((int) round($webhookAmount) !== (int) round((float) $donation->amount)) {
            Log::warning('Amount mismatch in webhook', [
                'expected' => $donation->amount,
                'received' => $webhookAmount,
                'donation_id' => $donation->id,
            ]);
            return false;
        }
        return true;
    }

    /**
     * Create a new completed donation as a copy of the parent recurring donation.
     * Used by Stripe invoice.paid and PayPal PAYMENT.SALE.COMPLETED webhooks.
     */
    private function createRecurringDonation(Donation $parent, array $eventData, string $transactionId, string $subscriptionField = 'stripe_subscription_id'): Donation
    {
        $data = [
            'donor_id' => $parent->donor_id,
            'donor_name' => $parent->donor_name,
            'email' => $parent->email,
            'phone' => $parent->phone,
            'amount' => $eventData['amount'],
            'currency' => $eventData['currency'] ?? $parent->currency,
            'payment_method_id' => $parent->payment_method_id,
            'transaction_id' => $transactionId,
            'status' => 'completed',
            'is_recurring' => true,
            'recurring_interval' => $parent->recurring_interval,
            $subscriptionField => $eventData['subscription_id'],
            'project_id' => $parent->project_id,
            'post_id' => $parent->post_id,
            'story_id' => $parent->story_id,
            'is_anonymous' => $parent->is_anonymous,
            'locale' => $parent->locale,
            'donated_at' => now(),
        ];

        return Donation::create($data);
    }

    /**
     * Handle Stripe webhook events: checkout.session.completed (one-time),
     * invoice.paid (recurring), and customer.subscription.deleted.
     * Verifies signature, idempotency, and amount before completing a donation.
     */
    public function stripe(Request $request)
    {
        $gateway = PaymentGateway::where('driver', 'stripe')->where('is_active', true)->first();
        if (!$gateway) {
            Log::warning('Stripe webhook: active gateway not found');
            return response('Gateway not found', 404);
        }

        $service = new StripeService($gateway->config ?? []);
        $event = $service->verifyWebhook($request->getContent(), $request->header('Stripe-Signature'));

        if (empty($event)) {
            Log::warning('Stripe webhook: invalid signature');
            return response('Invalid signature', 400);
        }

        $type = $event['type'] ?? '';
        Log::info('Stripe webhook received', ['type' => $type]);

        if ($type === 'checkout.session.completed') {
            $sessionId = $event['data']['object']['id'] ?? '';
            $donation = $this->getIdempotencyCheck($sessionId);
            if (!$donation) {
                return response('OK', 200);
            }

            $amountReceived = ($event['data']['object']['amount_total'] ?? 0) / 100;
            Log::info('Stripe webhook amount check', [
                'donation_id' => $donation->id,
                'expected' => $donation->amount,
                'received' => $amountReceived,
                'amount_total_raw' => $event['data']['object']['amount_total'] ?? null,
            ]);
            if (!$this->verifyAmountMatch($amountReceived, $donation)) {
                return response('Amount mismatch', 400);
            }

            $subscriptionId = $event['data']['object']['subscription'] ?? null;
            $updateData = ['status' => 'completed'];
            if ($subscriptionId) {
                $updateData['stripe_subscription_id'] = $subscriptionId;
            }
            $donation->update($updateData);
            Log::info('Donation completed via Stripe webhook', ['donation_id' => $donation->id]);
        }

        if ($type === 'invoice.paid') {
            $subscriptionId = $event['data']['object']['subscription'] ?? '';
            if ($subscriptionId) {
                $parent = Donation::where('stripe_subscription_id', $subscriptionId)->first();
                if ($parent) {
                    $new = $this->createRecurringDonation($parent, [
                        'amount' => ($event['data']['object']['amount_paid'] ?? 0) / 100,
                        'currency' => $parent->currency,
                        'subscription_id' => $subscriptionId,
                    ], $event['data']['object']['id'] ?? ('inv_' . uniqid()));
                    Log::info('Recurring donation created via Stripe subscription', ['donation_id' => $new->id]);
                }
            }
        }

        if ($type === 'customer.subscription.deleted') {
            $subscriptionId = $event['data']['object']['id'] ?? '';
            if ($subscriptionId) {
                Donation::where('stripe_subscription_id', $subscriptionId)
                    ->where('is_recurring', true)
                    ->update(['is_recurring' => false, 'recurring_interval' => null]);
                Log::info('Recurring donation cancelled via Stripe', ['subscription_id' => $subscriptionId]);
            }
        }

        return response('OK', 200);
    }

    /**
     * Handle PayPal webhook events: CHECKOUT.ORDER.APPROVED (one-time) and
     * PAYMENT.SALE.COMPLETED (recurring). Verifies signature via PayPal API,
     * checks idempotency and amount, then captures or creates the donation.
     */
    public function paypal(Request $request)
    {
        $gateway = PaymentGateway::where('driver', 'paypal')->where('is_active', true)->first();
        if (!$gateway) {
            Log::warning('PayPal webhook: active gateway not found');
            return response('Gateway not found', 404);
        }

        $payload = $request->getContent();
        $headers = getallheaders();

        $service = new PayPalService($gateway->config ?? []);
        if (!$service->verifyWebhookSignature($payload, $headers)) {
            Log::error('PayPal webhook signature verification failed');
            return response('Invalid signature', 400);
        }

        $event = json_decode($payload, true);
        $eventType = $event['event_type'] ?? '';

        Log::info('PayPal webhook received', ['event_type' => $eventType]);

        if ($eventType === 'CHECKOUT.ORDER.APPROVED') {
            $orderId = $event['resource']['id'] ?? '';
            $donation = $this->getIdempotencyCheck($orderId);
            if (!$donation) {
                return response('OK', 200);
            }

            $capture = $service->captureOrder($orderId);

            if ($capture && ($capture['status'] ?? '') === 'COMPLETED') {
                $capturedAmount = $capture['purchase_units'][0]['payments']['captures'][0]['amount']['value'] ?? 0;
                if (!$this->verifyAmountMatch((float) $capturedAmount, $donation)) {
                    return response('Amount mismatch', 400);
                }
                $donation->update(['status' => 'completed']);
                Log::info('Donation completed via PayPal webhook', ['donation_id' => $donation->id]);
            } else {
                Log::warning('PayPal capture failed', ['order_id' => $orderId, 'capture' => $capture]);
            }
        }

        if ($eventType === 'PAYMENT.SALE.COMPLETED') {
            $billingToken = $event['resource']['billing_agreement_id'] ?? '';
            if ($billingToken) {
                $parent = Donation::where('paypal_billing_agreement_id', $billingToken)->first();
                if ($parent) {
                    $new = $this->createRecurringDonation($parent, [
                        'amount' => ($event['resource']['amount']['total'] ?? 0),
                        'currency' => $event['resource']['amount']['currency'] ?? 'USD',
                        'subscription_id' => $billingToken,
                    ], $event['resource']['id'] ?? ('pp_' . uniqid()), 'paypal_billing_agreement_id');
                    Log::info('Recurring donation created via PayPal', ['donation_id' => $new->id]);
                }
            }
        }

        return response('OK', 200);
    }
}
