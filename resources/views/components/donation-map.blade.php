<div class="donation-map-section">
    <div class="map-header">
        <h2><i aria-hidden="true" class="fas fa-globe-americas"></i> تبرعات من حول العالم</h2>
        <p>كل نقطة تمثل متبرعاً. الخطوط تظهر رحلة التبرع من دولته إلى {{ $campaign->target_country }}</p>
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
            <span class="legend-dot target"></span> {{ $campaign->target_country }} (الهدف)
        </div>
        <div class="legend-item">
            <span class="legend-dot donor"></span> متبرع
        </div>
        <div class="legend-item">
            <span class="legend-line"></span> مسار التبرع
        </div>
    </div>

    <div class="map-stats">
        <div class="map-stat">
            <span class="map-stat-value" id="map-country-count">0</span>
            <span class="map-stat-label">دولة</span>
        </div>
        <div class="map-stat">
            <span class="map-stat-value" id="map-total-donors">0</span>
            <span class="map-stat-label">متبرع</span>
        </div>
    </div>
</div>
