@extends('layouts.app')
@section('content')
<section class="section" style="background:linear-gradient(135deg,#ff6b6b,#c0392b);color:#fff;padding:4rem 0 3rem">
    <div class="container" style="text-align:center">
        <h1 style="font-size:2.5rem;font-weight:900;margin-bottom:0.5rem">{{ __('campaigns.emergency_heading') }}</h1>
        <p style="opacity:.9;font-size:1.1rem;max-width:600px;margin:0 auto">{{ __('campaigns.emergency_subheading') }}</p>
    </div>
</section>
<section class="section">
    <div class="container">
        @if($campaigns->isEmpty())
            <p style="text-align:center;color:var(--color-text-light);padding:3rem 0">{{ __('campaigns.no_active') }}</p>
        @else
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:1.5rem">
            @foreach($campaigns as $campaign)
            <article class="emergency-card">
                <div class="emergency-card__image">
                    @if($campaign->image)
                    <img loading="lazy" src="{{ asset('storage/'.$campaign->image) }}" alt="{{ trans_field($campaign, 'title') }}">
                    @else
                    <div style="height:200px;background:linear-gradient(135deg,var(--color-danger),#c0392b);display:flex;align-items:center;justify-content:center;color:#fff;font-size:3rem"><i aria-hidden="true" class="fas fa-exclamation-triangle"></i></div>
                    @endif
                    <span class="emergency-badge">{{ __('campaigns.urgent') }}</span>
                </div>
                <div class="emergency-card__body">
                    <h3>{{ trans_field($campaign, 'title') }}</h3>
                    <p>{{ Str::limit(strip_tags(trans_field($campaign, 'description')), 100) }}</p>
                    <div class="emergency-progress">
                        <div class="progress-bar"><div class="progress-bar__fill" style="width:{{ $campaign->progressPercent }}%"></div></div>
                        <div class="progress-stats">
                            <span><strong>${{ number_format($campaign->collected_amount, 0) }}</strong> / ${{ number_format($campaign->target_amount, 0) }}</span>
                        </div>
                    </div>
                    @if($campaign->remainingDays() !== null)
                    <p style="color:var(--color-danger);font-size:.85rem;margin-top:.5rem"><i aria-hidden="true" class="fas fa-clock"></i> {{ __('campaigns.remaining_days', ['days' => $campaign->remainingDays()]) }}</p>
                    @endif
                    <a href="{{ route('emergency-campaigns.show', ['locale' => app()->getLocale(), 'campaign' => $campaign->slug]) }}" class="btn btn--primary" style="width:100%;margin-top:1rem">{{ __('campaigns.donate_now') }}</a>
                </div>
            </article>
            @endforeach
        </div>
        @endif
    </div>
</section>
<style>
.emergency-card{border-radius:12px;overflow:hidden;background:#fff;box-shadow:0 2px 12px rgba(0,0,0,.08);transition:transform .2s,box-shadow .2s}
.emergency-card:hover{transform:translateY(-3px);box-shadow:0 4px 20px rgba(0,0,0,.12)}
.emergency-card__image{position:relative;height:200px;overflow:hidden}
.emergency-card__image img{width:100%;height:100%;object-fit:cover}
.emergency-badge{position:absolute;top:12px;right:12px;background:var(--color-danger);color:#fff;padding:4px 12px;border-radius:20px;font-size:.75rem;font-weight:700;text-transform:uppercase}
.emergency-card__body{padding:1.25rem}
.emergency-card__body h3{font-size:1.1rem;margin-bottom:.5rem}
.emergency-card__body p{font-size:.9rem;color:#666;margin-bottom:.75rem}
</style>
@endsection
