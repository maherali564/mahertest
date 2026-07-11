<?php

namespace App\Console\Commands;

use App\Models\Donation;
use App\Services\Payment\PaymentService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessRecurringDonations extends Command
{
    protected $signature = 'donations:process-recurring';
    protected $description = 'Process recurring donations that are due';

    public function handle(): int
    {
        $now = now();
        $dueDonations = Donation::where('is_recurring', true)
            ->where('status', 'completed')
            ->whereNotNull('recurring_interval')
            ->get()
            ->filter(function ($donation) use ($now) {
                $lastDonated = $donation->donated_at ?? $donation->created_at;
                return match ($donation->recurring_interval) {
                    'monthly' => $lastDonated->copy()->addMonth()->lte($now),
                    'quarterly' => $lastDonated->copy()->addMonths(3)->lte($now),
                    'yearly' => $lastDonated->copy()->addYear()->lte($now),
                    default => false,
                };
            });

        $processed = 0;

        foreach ($dueDonations as $original) {
            DB::beginTransaction();

            try {
                if (($original->failed_attempts ?? 0) >= 3) {
                    $original->update(['is_recurring' => false]);
                    Log::warning('Recurring donation cancelled after 3 failed attempts', ['donation_id' => $original->id]);
                    DB::commit();
                    continue;
                }

                if ($original->stripe_subscription_id || $original->paypal_billing_agreement_id) {
                    $this->info("Skipping donation {$original->id}: managed by external subscription");
                    DB::commit();
                    continue;
                }

                $donation = Donation::create([
                    'donor_id' => $original->donor_id,
                    'donor_name' => $original->donor_name,
                    'email' => $original->email,
                    'phone' => $original->phone,
                    'amount' => $original->amount,
                    'currency' => $original->currency,
                    'payment_method_id' => $original->payment_method_id,
                    'is_anonymous' => $original->is_anonymous,
                    'is_recurring' => true,
                    'recurring_interval' => $original->recurring_interval,
                    'project_id' => $original->project_id,
                    'post_id' => $original->post_id,
                    'story_id' => $original->story_id,
                    'locale' => $original->locale,
                    'status' => 'pending',
                    'donated_at' => $now,
                ]);

                if ($donation->payment_method_id) {
                    $payment = PaymentService::fromDonation($donation);
                    $result = $payment->initPayment($donation);

                    if ($result['type'] === 'redirect' && !empty($result['url'])) {
                        $donation->update([
                            'status' => 'pending',
                            'notes' => 'Recurring donation pending redirect to ' . $result['url'],
                        ]);
                        Log::info('Recurring donation redirect created', [
                            'donation_id' => $donation->id,
                            'url' => $result['url'],
                        ]);
                    } elseif ($result['type'] === 'instructions') {
                        $donation->update(['status' => 'pending']);
                    }
                } else {
                    $donation->update(['status' => 'completed']);
                }

                $original->update(['failed_attempts' => 0]);

                DB::commit();
                $processed++;
            } catch (\Exception $e) {
                DB::rollBack();

                Log::error('Failed to process recurring donation', [
                    'original_donation_id' => $original->id,
                    'error' => $e->getMessage(),
                ]);

                $original->increment('failed_attempts');
                $original->update(['last_attempt_at' => now()]);
            }
        }

        $this->info("Processed {$processed} recurring donations.");
        return Command::SUCCESS;
    }
}
