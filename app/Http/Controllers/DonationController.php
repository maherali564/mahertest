<?php

namespace App\Http\Controllers;

use App\Mail\DonationConfirmation;
use App\Models\Donation;
use App\Models\PaymentMethod;
use App\Models\Project;
use App\Models\Story;
use App\Services\Payment\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;

class DonationController extends Controller
{
    /** Show the donation form for a specific project. */
    public function projectPage(string $locale, string $slug): View
    {
        $project = Project::with('media')->where('slug', $slug)->active()->firstOrFail();
        $donations = Donation::completed()->where('project_id', $project->id)->latest()->limit(20)->get();
        $paymentMethods = PaymentMethod::with('gateway')->active()->get();
        $projects = Project::active()->get();
        $stories = Story::active()->get();

        return view('donate.project', compact('project', 'donations', 'paymentMethods', 'projects', 'stories'));
    }

    /** Show the donation form for a specific story by slug (falls back to id before migration). */
    public function storyPage(string $locale, string $slug): View
    {
        $story = Story::active()->where('slug', $slug)->first();
        if (!$story && ctype_digit($slug)) {
            $story = Story::active()->findOrFail($slug);
        }
        if (!$story) abort(404);
        $donations = Donation::completed()->where('story_id', $story->id)->latest()->limit(20)->get();
        $paymentMethods = PaymentMethod::with('gateway')->active()->get();
        $projects = Project::active()->get();
        $stories = Story::active()->where('id', '!=', $story->id)->get();

        return view('donate.story', compact('story', 'donations', 'paymentMethods', 'projects', 'stories'));
    }

    /** Process a new donation: validate, persist, initiate payment. */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'donor_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'amount' => 'required|numeric|min:1|max:999999.99',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'is_anonymous' => 'nullable|boolean',
            'is_recurring' => 'nullable|boolean',
            'recurring_interval' => 'nullable|string|in:monthly,quarterly,yearly',
            'project_id' => 'nullable|exists:projects,id',
            'post_id' => 'nullable|exists:posts,id',
            'story_id' => 'nullable|exists:stories,id',

            'notes' => 'nullable|string|max:2000',
        ];

        if ($request->filled('hp_website')) {
            abort(422, 'Spam detected');
        }

        $validated = $request->validate($rules);

        $donation = Donation::create([
            ...$validated,
            'is_anonymous' => $request->boolean('is_anonymous'),
            'is_recurring' => $request->boolean('is_recurring'),
            'currency' => 'USD',
            'status' => 'pending',
            'locale' => app()->getLocale(),
            'donated_at' => now(),
        ]);

        if ($donation->payment_method_id) {
            try {
                $payment = PaymentService::fromDonation($donation);
                $result = $payment->initPayment($donation);

                if ($result['type'] === 'redirect' && !empty($result['url'])) {
                    $allowedDomains = [
                        'checkout.stripe.com',
                        'www.paypal.com',
                        'sandbox.paypal.com',
                    ];
                    $host = parse_url($result['url'], PHP_URL_HOST);
                    $isAllowed = false;
                    foreach ($allowedDomains as $domain) {
                        if (Str::endsWith($host, '.' . $domain) || $host === $domain) {
                            $isAllowed = true;
                            break;
                        }
                    }
                    if (!$isAllowed) {
                        Log::warning('Blocked redirect to untrusted domain', ['url' => $result['url']]);
                        return back()->with('error', __('common.payment_redirect_blocked'));
                    }
                    return redirect()->away($result['url']);
                }

                if ($result['type'] === 'instructions') {
                    $token = $donation->access_token;
                    return redirect()->route('payment.instructions', [
                        'locale' => $donation->locale,
                        'donation' => $donation->id,
                        'token' => $token,
                    ]);
                }
            } catch (RuntimeException $e) {
                Log::error('Payment initiation failed', [
                    'donation_id' => $donation->id,
                    'error' => $e->getMessage(),
                ]);
                return back()->with('error', $e->getMessage());
            }
        }

        try {
            Mail::to($donation->email)->queue(new DonationConfirmation($donation, 'under_review'));
        } catch (\Exception $e) {
            Log::error('Donation confirmation email failed', ['donation_id' => $donation->id, 'error' => $e->getMessage()]);
        }

        return back()->with('success', __('common.donation_success'));
    }
}
