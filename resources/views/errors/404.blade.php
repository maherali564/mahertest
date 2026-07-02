@extends('layouts.app')

@section('content')
<section class="section" style="min-height:50vh;display:flex;align-items:center">
    <div class="container" style="text-align:center">
        <h1 style="font-size:6rem;font-weight:900;color:var(--color-primary);margin-bottom:0.5rem">404</h1>
        <h2 style="font-size:1.5rem;color:var(--color-text);margin-bottom:1rem">{{ __('errors.not_found_title') }}</h2>
        <p style="color:var(--color-text-muted);max-width:400px;margin:0 auto 2rem">{{ __('errors.not_found_desc') }}</p>
        <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="btn btn--primary btn--lg">{{ __('errors.back_home') }}</a>
    </div>
</section>
@endsection
