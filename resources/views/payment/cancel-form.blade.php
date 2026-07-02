@extends('layouts.app')

@section('content')
<section class="section">
    <div class="container" style="text-align:center;padding:4rem 1rem">
        <div style="font-size:5rem;color:#f59e0b;margin-bottom:1rem">⚠️</div>
        <h1 class="section-title" style="margin-bottom:0.5rem">{{ __('donate.cancel_confirm_title') }}</h1>
        <p style="font-size:1.2rem;color:var(--text-muted);margin-bottom:2rem">
            {{ __('donate.cancel_confirm_description', ['amount' => $donation->amount, 'currency' => $donation->currency]) }}
        </p>
        <form method="POST" action="{{ route('payment.cancel.post', ['locale' => app()->getLocale(), 'donation' => $donation->id, 'token' => $token]) }}" style="display:inline">
            @csrf
            <button type="submit" class="btn btn--primary btn--lg" style="background:#dc2625">{{ __('common.confirm_cancel') }}</button>
            <a href="{{ route('donate.page', ['locale' => app()->getLocale()]) }}" class="btn btn--outline btn--lg">{{ __('common.go_back') }}</a>
        </form>
    </div>
</section>
@endsection
