@php
    $s = $settings ?? \App\Models\SiteSetting::current();
    $logoSrc = null;
    if ($s->logos && is_array($s->logos) && isset($s->logos[$currentLocale])) {
        $logoSrc = $s->logos[$currentLocale];
    } elseif ($s->logo) {
        $logoSrc = $s->logo;
    }
    $siteName = trans_field($s, 'site_name') ?? config('app.name');
@endphp

<style>
#preloader {
    position: fixed;
    inset: 0;
    z-index: 99999;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: opacity 0.4s ease, visibility 0.4s ease;
}
#preloader.preloader--hidden {
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
}
.preloader__inner {
    text-align: center;
}
.preloader__logo-wrapper {
    margin-bottom: 24px;
}
.preloader__logo {
    max-width: 180px;
    max-height: 80px;
    animation: preloaderPulse 1.5s ease-in-out infinite;
}
.preloader__logo-text {
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--color-primary, #0d6b4f);
    animation: preloaderPulse 1.5s ease-in-out infinite;
}
.preloader__dots {
    display: flex;
    justify-content: center;
    gap: 8px;
}
.preloader__dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: var(--color-primary, #0d6b4f);
    animation: preloaderBounce 1.4s ease-in-out infinite;
}
.preloader__dot:nth-child(2) {
    animation-delay: 0.2s;
}
.preloader__dot:nth-child(3) {
    animation-delay: 0.4s;
}
@keyframes preloaderPulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.05); opacity: 0.8; }
}
@keyframes preloaderBounce {
    0%, 80%, 100% { transform: scale(0.6); opacity: 0.4; }
    40% { transform: scale(1); opacity: 1; }
}
</style>

<div id="preloader" role="status" aria-label="{{ __('common.loading') }}">
    <div class="preloader__inner">
        <div class="preloader__logo-wrapper">
            @if($logoSrc)
                <img src="{{ asset('storage/'.$logoSrc) }}" alt="{{ $siteName }}" class="preloader__logo">
            @else
                <div class="preloader__logo-text">{{ $siteName }}</div>
            @endif
        </div>
        <div class="preloader__dots">
            <span class="preloader__dot"></span>
            <span class="preloader__dot"></span>
            <span class="preloader__dot"></span>
        </div>
    </div>
</div>

<script nonce="{{ $cspNonce }}">
(function() {
    var p = document.getElementById('preloader');
    if (!p) return;
    function hide() {
        p.classList.add('preloader--hidden');
        setTimeout(function() { p.remove(); }, 500);
    }
    if (document.readyState !== 'loading') {
        hide();
    } else {
        document.addEventListener('DOMContentLoaded', hide);
    }
    setTimeout(hide, 600);
})();
</script>