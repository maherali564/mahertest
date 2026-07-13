@php $isAr = app()->getLocale() === 'ar'; @endphp
@push('leaflet')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" nonce="{{ $cspNonce ?? '' }}"></script>
@endpush
<div class="donation-map-section">
    <div class="map-header">
        <h2><i aria-hidden="true" class="fas fa-globe-americas"></i> {{ $isAr ? 'تبرعات من حول العالم' : 'Donations from Around the World' }}</h2>
        <p>{{ $isAr ? 'كل نقطة تمثل متبرعاً. الخطوط تظهر رحلة التبرع من دولته إلى' : 'Each dot represents a donor. Lines show the donation journey from their country to' }} {{ $campaign->target_country }}</p>
    </div>

    <div id="map-data"
         data-target-lat="{{ $campaign->target_latitude }}"
         data-target-lng="{{ $campaign->target_longitude }}"
         data-target-country="{{ $campaign->target_country }}"
         data-target-flag="{{ $campaign->target_flag }}"
         data-campaign-slug="{{ $campaign->slug }}"
         data-campaign-id="{{ $campaign->id }}">
    </div>

    <div class="map-container" id="donation-world-map"></div>

    <div class="map-legend">
        <div class="legend-item">
            <span class="legend-dot target"></span> {{ $campaign->target_country }} {{ $isAr ? '(الهدف)' : '(Target)' }}
        </div>
        <div class="legend-item">
            <span class="legend-dot donor"></span> {{ $isAr ? 'متبرع' : 'Donor' }}
        </div>
        <div class="legend-item">
            <span class="legend-line"></span> {{ $isAr ? 'مسار التبرع' : 'Donation Route' }}
        </div>
    </div>

    <div class="map-stats">
        <div class="map-stat">
            <span class="map-stat-value" id="map-country-count">0</span>
            <span class="map-stat-label">{{ $isAr ? 'دولة' : 'Countries' }}</span>
        </div>
        <div class="map-stat">
            <span class="map-stat-value" id="map-total-donors">0</span>
            <span class="map-stat-label">{{ $isAr ? 'متبرع' : 'Donors' }}</span>
        </div>
    </div>
</div>
