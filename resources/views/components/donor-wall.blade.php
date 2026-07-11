@php $isAr = app()->getLocale() === 'ar'; @endphp
<div class="ec-donor-wall-section" id="donor-wall">
    <div class="ec-donor-wall-header">
        <h2>
            <i aria-hidden="true" class="fas fa-users"></i>
            {{ $isAr ? 'جدار المتبرعين' : 'Donor Wall' }}
        </h2>
        <div class="ec-donor-count-badge" id="donor-count-badge">
            <span class="ec-live-dot"></span>
            {{ number_format($donorCount) }} {{ $isAr ? 'متبرع' : 'Donors' }}
        </div>
    </div>

    <div class="ec-donor-wall-container" id="donor-wall-list">
        @forelse($donations as $donation)
            <div class="ec-donor-item">
                <div class="ec-donor-avatar" style="background: {{ $donation->avatarColor ?? '#C62828' }}">
                    {{ mb_substr($donation->donorDisplayName(), 0, 1) }}
                </div>
                <div class="ec-donor-info">
                    <strong>{{ $donation->donorDisplayName() }}</strong>
                    @if($donation->message)
                        <p class="ec-donor-message">"{{ $donation->message }}"</p>
                    @endif
                    <small>{{ $donation->created_at->diffForHumans() }}</small>
                </div>
                <div class="ec-donor-amount">
                    {{ number_format($donation->amount) }} {{ $donation->currency }}
                </div>
            </div>
        @empty
            <p style="color:#999;text-align:center;padding:2rem">{{ $isAr ? 'لا توجد تبرعات بعد، كن أول المتبرعين!' : 'No donations yet, be the first donor!' }}</p>
        @endforelse
    </div>
</div>
