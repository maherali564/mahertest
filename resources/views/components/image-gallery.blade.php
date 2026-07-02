<div class="ec-gallery-section">
    <h2 class="ec-section-title">معرض الصور</h2>
    <div class="ec-gallery-grid">
        @php
            $images = $images ?? [];
        @endphp
        @forelse($images as $image)
            <div class="ec-gallery-item">
                <img src="{{ asset('storage/' . $image) }}" alt="" loading="lazy">
                <div class="ec-gallery-overlay">
                    <i aria-hidden="true" class="fas fa-search-plus"></i>
                </div>
            </div>
        @empty
            <p style="color: var(--text-muted); grid-column: 1/-1; text-align: center; padding: 2rem;">
                لا توجد صور متاحة حالياً
            </p>
        @endforelse
    </div>
</div>
