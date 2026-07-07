<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /** Validate that the access_token query param matches the donation. */
    private function validateAccessToken(Request $request, Donation $donation): bool
    {
        $token = $request->query('token', '');
        return !empty($token) && hash_equals($donation->access_token, $token);
    }

    /** Show a success page after payment completion. */
    public function success(Request $request, string $locale, Donation $donation)
    {
        abort_unless($this->validateAccessToken($request, $donation), 403);
        return view('payment.success', compact('donation'));
    }

    /** Show a cancellation confirmation form (GET fallback for the POST-only cancel). */
    public function cancelForm(Request $request, string $locale, Donation $donation)
    {
        abort_unless($this->validateAccessToken($request, $donation), 403);
        $token = $request->query('token', '');
        return view('payment.cancel-form', compact('donation', 'token'));
    }

    /** Cancel a donation (POST-only to prevent CSRF-style cancellation via <img>). */
    public function cancel(Request $request, string $locale, Donation $donation)
    {
        abort_unless($this->validateAccessToken($request, $donation), 403);
        $donation->update(['status' => 'cancelled']);
        return view('payment.cancel', compact('donation'));
    }

    /** Show payment instructions (bank transfer details) for a donation. */
    public function instructions(Request $request, string $locale, Donation $donation)
    {
        abort_unless($this->validateAccessToken($request, $donation), 403);
        $paymentMethod = $donation->paymentMethod;
        $gateway = $paymentMethod?->gateway;
        $config = $gateway?->config ?? [];
        $instructions = $paymentMethod?->instructions ?? '';
        $driver = $gateway?->driver ?? '';
        return view('payment.instructions', compact('donation', 'config', 'instructions', 'paymentMethod', 'driver'));
    }
}
