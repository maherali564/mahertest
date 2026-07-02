@extends('layouts.app')
@php $s = $settings; @endphp

@section('content')
<section style="background:linear-gradient(135deg,var(--color-primary),var(--color-primary-dark));padding:3rem 0;text-align:center;color:#fff">
    <div class="container">
        <span style="display:inline-block;padding:4px 14px;background:rgba(255,255,255,0.15);border-radius:20px;font-size:0.78rem;margin-bottom:12px">{{ __('common.nav_donate') }}</span>
        <h1 style="font-size:2rem;font-weight:800;margin-bottom:8px">{{ trans_field($s, 'donate_title') }}</h1>
        <p style="color:rgba(255,255,255,0.85);font-size:1rem;max-width:600px;margin:0 auto">{{ trans_field($s, 'donate_description') }}</p>
    </div>
</section>

<section class="donate-section">
    <div class="container donate-section__inner">
        <div class="donate-grid">
            <aside class="donate-info">
                <h3 class="donate-info__heading"><i aria-hidden="true" class="fas fa-credit-card"></i>{{ __('common.payment_methods') }}</h3>
                <div class="donate-info__methods">
                    @foreach($paymentMethods as $pm)
                    <div class="donate-info__method" data-method-id="{{ $pm->id }}">
                        <div class="donate-info__method-icon">
                            @if($pm->gateway && $pm->gateway->logo)
                            <img loading="lazy" src="{{ asset('storage/'.$pm->gateway->logo) }}" alt="{{ $pm->name }}">
                            @elseif($pm->icon)
                            <i class="{{ $pm->icon }}"></i>
                            @else
                            <i aria-hidden="true" class="fas fa-wallet"></i>
                            @endif
                        </div>
                        <div>
                            <strong>{{ $pm->name }}</strong>
                            @if($pm->description)
                            <span>{{ $pm->description }}</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </aside>

            <div class="donate-form-wrap">
                <form class="donate-form" action="{{ route('donate.store', ['locale' => $currentLocale]) }}" method="POST">
                    @csrf
                    <input type="text" name="hp_website" tabindex="-1" autocomplete="off" class="hp-field" aria-hidden="true">
                    <h3 class="donate-form__title">{{ __('common.donation_form') }}</h3>

                    <div class="donate-form__amounts">
                        <span class="donate-form__label">{{ __('donate.quick_amounts') }}</span>
                        <div class="donate-form__presets">
                            @foreach([10, 25, 50, 100, 250, 500] as $preset)
                            <button type="button" data-amount="{{ $preset }}" class="donate-form__preset">${{ $preset }}</button>
                            @endforeach
                        </div>
                    </div>

                    <div class="donate-form__fields">
                        <label>
                            <span class="donate-form__label">{{ __('donate.custom_amount') }}</span>
                            <input type="number" name="amount" id="donationAmount" min="1" step="0.01" required placeholder="{{ __('donate.min_amount') }}">
                        </label>
                        <label>
                            <span class="donate-form__label">{{ __('common.full_name') }}</span>
                            <input type="text" name="donor_name" required>
                        </label>
                        <label>
                            <span class="donate-form__label">{{ __('common.email') }}</span>
                            <input type="email" name="email" required>
                        </label>
                        <label>
                            <span class="donate-form__label">{{ __('common.phone') }}</span>
                            <input type="tel" name="phone">
                        </label>
                        <label>
                            <span class="donate-form__label">{{ __('donate.payment_method') }}</span>
                            <select name="payment_method_id" id="paymentMethodSelect">
                                <option value="">{{ __('donate.general_donation') }}</option>
                                @foreach($paymentMethods as $pm)
                                <option value="{{ $pm->id }}" data-driver="{{ $pm->gateway?->driver ?? '' }}">{{ $pm->name }} - {{ $pm->description }}</option>
                                @endforeach
                            </select>
                            <div id="paymentMethodInfo" class="payment-method-info" style="display:none"></div>
                        </label>
                    </div>

                    <div class="donate-form__checks">
                        <label>
                            <input type="checkbox" name="is_anonymous" value="1">
                            <span>{{ __('donate.anonymous_donation') }}</span>
                        </label>
                        <label>
                            <input type="checkbox" name="is_recurring" value="1" id="recurringToggle">
                            <span>{{ __('donate.recurring_donation') }}</span>
                        </label>
                    </div>

                    <div id="recurringOptions" style="display:none">
                        <label>
                            <span class="donate-form__label">{{ __('donate.recurring_interval') }}</span>
                            <select name="recurring_interval">
                                <option value="monthly">{{ __('donate.every_month') }}</option>
                                <option value="quarterly">{{ __('donate.every_3_months') }}</option>
                                <option value="yearly">{{ __('donate.every_year') }}</option>
                            </select>
                        </label>
                    </div>

                    <label style="margin-top:12px">
                        <span class="donate-form__label">{{ __('donate.donation_note') }}</span>
                        <textarea name="notes" rows="2"></textarea>
                    </label>

                    <button type="submit" class="btn btn--primary btn--block btn--lg donate-form__submit">{{ __('common.complete_donation') }}</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('head')
<style>
.donate-section { padding: 3rem 0; }
.donate-section__inner { max-width: 1100px; }
.donate-grid { display:grid; grid-template-columns:1fr 1fr; gap:2rem; align-items:start; }
.donate-info__heading { font-size:1.1rem; font-weight:700; margin-bottom:1rem; color:var(--color-heading); }
.donate-info__heading i { color:var(--color-primary); margin-inline-end:8px; }
.donate-info__methods { display:grid; grid-template-columns:1fr 1fr; gap:8px; }
.donate-info__method { display:flex; align-items:center; gap:10px; background:#fff; border:1px solid var(--color-border); border-radius:10px; padding:10px 14px; transition:box-shadow 0.2s; cursor:default; }
.donate-info__method:hover { box-shadow:0 2px 8px rgba(0,0,0,0.06); }
.donate-info__method-icon { width:36px; height:36px; border-radius:8px; background:var(--color-bg); display:flex; align-items:center; justify-content:center; font-size:1.1rem; flex-shrink:0; }
.donate-info__method-icon i { color:var(--color-primary); }
.donate-info__method-icon img { max-width:24px; max-height:24px; }
.donate-info__method strong { font-size:0.85rem; display:block; }
.donate-info__method span { font-size:0.72rem; color:var(--color-text-muted); display:block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
.donate-form-wrap { }
.donate-form { background:#fff; border:1px solid var(--color-border); border-radius:16px; padding:1.5rem; box-shadow:0 4px 24px rgba(0,0,0,0.06); }
.donate-form__title { font-size:1.15rem; font-weight:700; margin-bottom:1.25rem; color:var(--color-heading); text-align:center; }
.donate-form__label { font-size:0.78rem; font-weight:600; color:var(--color-text-muted); display:block; margin-bottom:6px; }
.donate-form__amounts { margin-bottom:1rem; }
.donate-form__presets { display:flex; gap:6px; flex-wrap:wrap; }
.donate-form__preset { padding:8px 16px; border:2px solid var(--color-border); border-radius:8px; background:#fff; font-weight:700; font-size:0.85rem; cursor:pointer; transition:all 0.2s; color:var(--color-text); }
.donate-form__preset:hover { border-color:var(--color-primary); color:var(--color-primary); }
.donate-form__preset:focus { border-color:var(--color-primary); outline:none; }
.donate-form__fields { display:flex; flex-direction:column; gap:12px; }
.donate-form__fields label { display:flex; flex-direction:column; gap:4px; }
.donate-form__fields input,
.donate-form__fields select,
.donate-form__fields textarea { padding:10px 14px; border:2px solid var(--color-border); border-radius:8px; font-size:0.9rem; outline:none; transition:border-color 0.2s; background:#fff; }
.donate-form__fields input:focus,
.donate-form__fields select:focus,
.donate-form__fields textarea:focus { border-color:var(--color-primary); }
.donate-form__fields textarea { resize:vertical; }
.donate-form__checks { display:flex; gap:1rem; margin-top:1rem; flex-wrap:wrap; }
.donate-form__checks label { display:flex; align-items:center; gap:6px; cursor:pointer; }
.donate-form__checks input { width:18px; height:18px; accent-color:var(--color-primary); }
.donate-form__checks span { font-size:0.85rem; color:var(--color-text); }
.donate-form__submit { margin-top:1.25rem; border-radius:10px; padding:14px; font-size:1rem; font-weight:700; }
.hp-field { position:fixed; top:-100px; left:0; }
@media (max-width:768px) {
    .donate-grid { grid-template-columns:1fr; }
    .donate-info__methods { grid-template-columns:1fr; }
}
</style>
@endpush

@push('scripts')
<script nonce="{{ $cspNonce }}">
document.addEventListener('DOMContentLoaded', function () {
    var pmSelect = document.getElementById('paymentMethodSelect');
});
</script>
@endpush
