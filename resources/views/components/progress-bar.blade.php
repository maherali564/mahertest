@php
    $percent = $target > 0 ? round(($current / $target) * 100, 1) : 0;
    $glass = $glass ?? false;
    $currency = $currency ?? 'USD';
@endphp
<div class="ec-glass-progress-container" data-currency="{{ $currency }}">
    <div class="ec-glass-progress-card">
        <div class="ec-progress-bar-glass">
            <div class="ec-progress-fill-glass" id="progress-fill-glass" style="width: {{ $percent }}%">
                <div class="ec-progress-shimmer"></div>
            </div>
        </div>
        <div class="ec-progress-percent" id="progress-percent">{{ $percent }}%</div>
        <div class="ec-progress-stats">
            <div class="ec-stat-item">
                <span class="ec-stat-value" id="collected-amount">{{ number_format($current) }} {{ $currency }}</span>
                <span class="ec-stat-label">تم جمعه</span>
            </div>
            <div class="ec-stat-divider"></div>
            <div class="ec-stat-item">
                <span class="ec-stat-value">{{ number_format($target) }} {{ $currency }}</span>
                <span class="ec-stat-label">المستهدف</span>
            </div>
            <div class="ec-stat-divider"></div>
            <div class="ec-stat-item">
                <span class="ec-stat-value" id="donor-count-badge-stat">{{ number_format($donors ?? 0) }}</span>
                <span class="ec-stat-label">متبرع</span>
            </div>
        </div>
    </div>
</div>
