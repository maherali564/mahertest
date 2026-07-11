@php $isAr = app()->getLocale() === 'ar'; @endphp
<div class="ec-countdown-container" data-ends-at="{{ $endsAt }}">
    <div class="ec-countdown-grid">
        <div class="ec-countdown-item">
            <div class="ec-countdown-card" id="countdown-days">
                <span class="ec-countdown-number">00</span>
            </div>
            <span class="ec-countdown-label">{{ $isAr ? 'يوم' : 'Days' }}</span>
        </div>
        <div class="ec-countdown-separator">:</div>
        <div class="ec-countdown-item">
            <div class="ec-countdown-card" id="countdown-hours">
                <span class="ec-countdown-number">00</span>
            </div>
            <span class="ec-countdown-label">{{ $isAr ? 'ساعة' : 'Hours' }}</span>
        </div>
        <div class="ec-countdown-separator">:</div>
        <div class="ec-countdown-item">
            <div class="ec-countdown-card" id="countdown-minutes">
                <span class="ec-countdown-number">00</span>
            </div>
            <span class="ec-countdown-label">{{ $isAr ? 'دقيقة' : 'Minutes' }}</span>
        </div>
        <div class="ec-countdown-separator">:</div>
        <div class="ec-countdown-item">
            <div class="ec-countdown-card" id="countdown-seconds">
                <span class="ec-countdown-number">00</span>
            </div>
            <span class="ec-countdown-label">{{ $isAr ? 'ثانية' : 'Seconds' }}</span>
        </div>
    </div>
</div>
