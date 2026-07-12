@extends('layouts.app')

@push('head')
<link rel="stylesheet" href="{{ asset('css/emergency-campaign.css') }}?v={{ filemtime(public_path('css/emergency-campaign.css')) }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
{{-- Hero Section --}}
<section id="hero" class="ec-hero-section">
    @if($campaign->image)
    <div class="ec-hero-bg" style="background-image: url('{{ asset('storage/' . $campaign->image) }}')"></div>
    @else
    <div class="ec-hero-bg" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);"></div>
    @endif
    <div class="ec-hero-overlay"></div>

    <div class="ec-hero-content">
        <div class="ec-hero-top">
            <div class="ec-hero-logo">
                <span>{{ app()->getLocale() === 'ar' ? 'نداء' : 'Appeal' }}</span>
            </div>
            <div class="ec-urgent-badge">
                <span class="ec-pulse-dot"></span>
                {{ app()->getLocale() === 'ar' ? 'حملة عاجلة' : 'Urgent Campaign' }}
            </div>
        </div>

        <h1 class="ec-hero-title">{{ $campaign->getTranslation('title', app()->getLocale()) }}</h1>

        <x-countdown-timer :endsAt="$campaign->ends_at" />

        <a href="#donate-form" class="ec-hero-donate-btn">
            <i aria-hidden="true" class="fas fa-heart"></i> {{ app()->getLocale() === 'ar' ? 'تبرع الآن' : 'Donate Now' }}
        </a>

        <x-progress-bar
            :current="$campaign->collected_amount"
            :target="$campaign->target_amount"
            :donors="$campaign->donorCount"
            :glass="true"
            :currency="$campaign->currency"
        />
    </div>
</section>

{{-- Main Grid --}}
<div class="ec-main-grid">
    {{-- Content Column --}}
    <div class="ec-content-col">
        {{-- Campaign Story --}}
        <div class="ec-story-section">
            <h2 class="ec-section-title">{{ app()->getLocale() === 'ar' ? 'قصة الحملة' : 'Campaign Story' }}</h2>
            @if($campaign->video)
            @php $ecThumb = $campaign->video_thumbnail ?: $campaign->image; @endphp
            <div style="margin-bottom:1.5rem;border-radius:12px;overflow:hidden;position:relative;aspect-ratio:16/9;cursor:pointer" onclick="openVideoLightbox('{{ asset('storage/'.$campaign->video) }}')">
                <div style="width:100%;height:100%;background:linear-gradient(135deg,#1e293b,#334155);display:flex;align-items:center;justify-content:center;">
                    @if($ecThumb)<img loading="lazy" src="{{ asset('storage/'.$ecThumb) }}" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover">@endif
                    <div style="position:relative;z-index:1;background:rgba(0,0,0,0.4);border-radius:50%;padding:16px;display:flex;align-items:center;justify-content:center;">
                        <svg viewBox="0 0 24 24" width="64" height="64" fill="white"><polygon points="8,5 19,12 8,19"/></svg>
                    </div>
                </div>
            </div>
            @endif
            <div class="ec-story-text">
                {{ $campaign->getTranslation('description', app()->getLocale()) }}
            </div>
        </div>

        {{-- Donation World Map --}}
        <x-donation-map :campaign="$campaign" />
    </div>

    {{-- Sidebar: Donation Form --}}
    <div class="ec-sidebar-col">
        <x-donation-form :action="route('emergency-campaigns.donate', ['locale' => app()->getLocale(), 'campaign' => $campaign->slug])" />
    </div>
</div>



{{-- Donor Wall --}}
<x-donor-wall
    :donations="$recentDonations"
    :donorCount="$campaign->donorCount"
/>

{{-- Lightbox Modal --}}
<x-lightbox-modal />

<div id="videoLightbox" class="lightbox" onclick="closeVideoLightbox(event)" style="display:none">
    <button class="lightbox__close" onclick="closeVideoLightbox()" aria-label="{{ __('common.close') }}">&times;</button>
    <div id="videoLightboxContainer" class="lightbox__video-container"></div>
</div>
<style>
.lightbox{position:fixed;inset:0;background:rgba(0,0,0,0.92);z-index:9999;display:flex;align-items:center;justify-content:center}
.lightbox__close{position:absolute;top:16px;right:16px;z-index:10000;background:rgba(255,255,255,.15);border:none;color:#fff;font-size:2rem;width:44px;height:44px;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .2s}
.lightbox__close:hover{background:rgba(255,255,255,.3)}
.lightbox__video-container{width:90%;max-width:900px;aspect-ratio:16/9;border-radius:8px;overflow:hidden}
.lightbox__video-container iframe,.lightbox__video-container video{width:100%;height:100%;border:0}
</style>

<script nonce="{{ $cspNonce }}">
document.addEventListener('DOMContentLoaded', function () {
    var mapData = document.getElementById('map-data');
    if (!mapData) return;
    var countryCount = 0, totalDonors = 0;
    var shownCountries = {};
    var targetLat = parseFloat(mapData.dataset.targetLat);
    var targetLng = parseFloat(mapData.dataset.targetLng);
    var targetCountry = mapData.dataset.targetCountry;
    var targetFlag = mapData.dataset.targetFlag;
    var campaignSlug = mapData.dataset.campaignSlug;
    var map = L.map('donation-world-map', { center: [25, 0], zoom: 2, minZoom: 2, maxZoom: 8, zoomControl: true, attributionControl: false, scrollWheelZoom: true });
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { subdomains: 'abcd', maxZoom: 19 }).addTo(map);
    var targetIcon = L.divIcon({
        className: 'target-map-icon',
        html: '<div class="target-marker-container"><div class="target-pulse-ring"></div><div class="target-dot"></div></div><span class="target-label">' + targetCountry + ' ' + targetFlag + '</span>',
        iconSize: [80, 80], iconAnchor: [40, 40]
    });
    L.marker([targetLat, targetLng], { icon: targetIcon }).addTo(map);
    var activeLines = [];
    window.addDonor = function (d) {
        if (!d.latitude || !d.longitude || d.latitude === 0 || d.longitude === 0) return;
        if (Math.abs(d.latitude) > 90 || Math.abs(d.longitude) > 180) return;
        totalDonors++;
        if (d.country && !shownCountries[d.country]) { shownCountries[d.country] = true; countryCount++; }
        var cc = document.getElementById('map-country-count'); if (cc) cc.innerText = countryCount;
        var td = document.getElementById('map-total-donors'); if (td) td.innerText = totalDonors;
        var icon = L.divIcon({ className: 'donor-map-icon', html: '<div class="donor-marker-container"><div class="donor-ripple"></div><div class="donor-dot"></div></div>', iconSize: [30, 30], iconAnchor: [15, 15] });
        var marker = L.marker([d.latitude, d.longitude], { icon: icon }).addTo(map);
        marker.bindPopup('<div class="map-popup"><strong>' + d.donor_name + '</strong><br><span style="color:#F59E0B;font-weight:bold">' + d.amount + ' ' + (d.currency || 'USD') + '</span><br><small>' + (d.country || '') + (d.city ? ' - ' + d.city : '') + '</small><br><small>' + (d.created_at || '{{ app()->getLocale() === 'ar' ? 'الآن' : 'Now' }}') + '</small></div>');
        var line = L.polyline([[d.latitude, d.longitude], [targetLat, targetLng]], { color: '#F59E0B', weight: 1.5, opacity: 0.8, dashArray: '10, 15' }).addTo(map);
        (function (l) { var o = 0; !function a() { o = (o + 0.5) % 25; l.setStyle({ dashOffset: -o }); requestAnimationFrame(a); }(); })(line);
        activeLines.push({ line: line, marker: marker, time: Date.now() });
        setTimeout(function () {
            while (activeLines.length && Date.now() - activeLines[0].time > 10000) { var old = activeLines.shift(); map.removeLayer(old.marker); map.removeLayer(old.line); }
            while (activeLines.length > 50) { var old = activeLines.shift(); map.removeLayer(old.marker); map.removeLayer(old.line); }
        }, 10000);
    }
    if (typeof Echo !== 'undefined') {
        window.Echo.channel('emergency-campaign.' + mapData.dataset.campaignId).listen('EmergencyDonationReceived', function (data) { if (data.donation) window.addDonor(data.donation); });
    }
    fetch('/' + document.documentElement.lang + '/api/emergency-campaigns/' + campaignSlug + '/donations').then(function (r) { return r.json(); }).then(function (donations) { donations.forEach(function (d) { addDonor({ latitude: d.donor_latitude, longitude: d.donor_longitude, donor_name: d.donor_name, amount: d.amount, currency: d.currency, country: d.donor_country, city: d.donor_city, created_at: d.created_at }); }); }).catch(function () {});
});</script>
<script src="{{ asset('js/emergency-campaign.js') }}" nonce="{{ $cspNonce }}"></script>
<script nonce="{{ $cspNonce }}">
var ec = new EmergencyCampaign({{ $campaign->id }}, '{{ app()->getLocale() }}');
ec.init();
</script>
@endsection
