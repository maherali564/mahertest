@extends('layouts.app')

@section('content')
<section class="section">
    <div class="container" style="text-align:center;padding:4rem 1rem">
        <div style="font-size:5rem;color:#f59e0b;margin-bottom:1rem">⚠️</div>
        <h1 class="section-title" style="margin-bottom:0.5rem">{{ __('donate.donation_failed') }}</h1>
        <p style="font-size:1.2rem;color:var(--text-muted);margin-bottom:2rem">{{ __('common.try_again_later') }}</p>
        <a href="{{ route('donate.page', ['locale' => app()->getLocale()]) }}" class="btn btn--primary btn--lg">{{ __('common.try_again') }}</a>
    </div>
</section>
@endsection
