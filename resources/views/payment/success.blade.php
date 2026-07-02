@extends('layouts.app')

@section('content')
<section class="section">
    <div class="container" style="text-align:center;padding:4rem 1rem">
        <div style="font-size:5rem;color:var(--color-primary);margin-bottom:1rem">✅</div>
        <h1 class="section-title" style="margin-bottom:0.5rem">{{ __('donate.donation_success_title') }}</h1>
        <p style="font-size:1.2rem;color:var(--text-muted);margin-bottom:2rem">{{ __('donate.donation_success_message') }}</p>
        <div style="background:var(--card-bg);border-radius:12px;padding:2rem;max-width:400px;margin:0 auto 2rem;box-shadow:0 2px 8px rgba(0,0,0,0.05)">
            <p><strong>{{ __('donate.donor_name') }}:</strong> {{ $donation->donor_name }}</p>
            <p><strong>{{ __('donate.donation_amount') }}:</strong> ${{ number_format($donation->amount, 2) }}</p>
            <p><strong>{{ __('donate.transaction_id') }}:</strong> {{ $donation->transaction_id }}</p>
            <p><strong>{{ __('donate.donation_date') }}:</strong> {{ $donation->created_at->format('Y-m-d H:i') }}</p>
        </div>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap">
            <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="btn btn--primary btn--lg">{{ __('common.back_to_home') }}</a>
        </div>
    </div>
</section>
@endsection
