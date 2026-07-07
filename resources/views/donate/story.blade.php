@extends('layouts.app')
@php
    use Illuminate\Support\Facades\Storage;
    $storyImagePaths = $story->images ?? [];
    if ($story->image && !in_array($story->image, $storyImagePaths)) {
        array_unshift($storyImagePaths, $story->image);
    }
    $allImages = $storyImagePaths;
    $mediaItems = collect();
    $getThumb = function ($videoPath) use ($story) {
        if (isset($story->video_thumbnails[$videoPath]) && Storage::disk('public')->exists($story->video_thumbnails[$videoPath])) {
            return $story->video_thumbnails[$videoPath];
        }
        $thumbName = pathinfo($videoPath, PATHINFO_DIRNAME) . '/' . pathinfo($videoPath, PATHINFO_FILENAME) . '_thumb.jpg';
        if (Storage::disk('public')->exists($thumbName)) {
            return $thumbName;
        }
        return $story->image;
    };
    foreach ($storyImagePaths as $img) {
        $mediaItems->push((object)['type' => 'image', 'path' => $img, 'thumbnail' => null, 'id' => 'img_'.str_replace(['/','\\','.'], '_', $img)]);
    }
    if ($story->video_url) {
        $vThumb = null;
        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $story->video_url, $m)) {
            $vThumb = 'https://img.youtube.com/vi/'.$m[1].'/hqdefault.jpg';
        } else {
            $vThumb = $getThumb($story->video_url);
        }
        $mediaItems->push((object)['type' => 'video', 'path' => $story->video_url, 'thumbnail' => $vThumb, 'id' => 'video_main']);
    }
    foreach ($story->videos ?? [] as $v) {
        if ($v !== $story->video_url && !in_array($v, $story->images ?? [])) {
            $vThumb = $getThumb($v);
            $mediaItems->push((object)['type' => 'video', 'path' => $v, 'thumbnail' => $vThumb, 'id' => 'video_'.md5($v)]);
        }
    }
    $hasVideo = $mediaItems->contains(fn($m) => $m->type === 'video');
@endphp
@section('content')
<section class="section">
    <div class="container donate-project">
        <div class="donate-project__grid">
            <div class="donate-project__info">
                @if($story->image)
                <div class="donate-project__image">
                    <img loading="lazy" src="{{ asset('storage/'.$story->image) }}" alt="{{ trans_field($story, 'title') }}">
                </div>
                @endif
                @if($mediaItems->isNotEmpty())
                <div class="media-grid" data-component="media-grid">
                    <div class="media-grid__tabs">
                        <button class="active" data-filter="all">{{ __('common.all') }}</button>
                        <button data-filter="image">{{ __('common.images') }}</button>
                        @if($hasVideo)<button data-filter="video">{{ __('common.videos') }}</button>@endif
                    </div>
                    <div class="media-grid__items" id="mediaGridItems">
                        @foreach($mediaItems as $item)
                        <div class="media-grid__item" data-type="{{ $item->type }}">
                            @if($item->type === 'image')
                            @php $imgIdx = array_search($item->path, $allImages); if($imgIdx === false) $imgIdx = $loop->index; @endphp
                            <img loading="lazy" src="{{ asset('storage/'.$item->path) }}"
                                 alt="" class="media-grid__thumb"
                                 @click="openLightbox({{ $imgIdx }})">
                            @else
                            @php
                                $vUrl = $item->path;
                                $vType = null;
                                $vEmbedUrl = null;
                                if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $vUrl, $m)) {
                                    $vType = 'youtube';
                                    $vEmbedUrl = 'https://www.youtube.com/embed/'.$m[1].'?autoplay=1';
                                } elseif (preg_match('/vimeo\.com\/(\d+)/', $vUrl, $m)) {
                                    $vType = 'vimeo';
                                    $vEmbedUrl = 'https://player.vimeo.com/video/'.$m[1].'?autoplay=1';
                                } else {
                                    $vType = 'upload';
                                    $vEmbedUrl = asset('storage/'.ltrim($vUrl, '/'));
                                }
                            @endphp
                            <div class="media-grid__video" onclick="openVideoLightbox('{{ addslashes($vEmbedUrl) }}', '{{ $vType }}')">
                                <div class="media-grid__video-thumb">
                                    @if($item->thumbnail ?? null)
                                    <img loading="lazy" src="{{ str_starts_with($item->thumbnail, 'http') ? $item->thumbnail : asset('storage/'.$item->thumbnail) }}" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:8px">
                                    @else
                                    <div class="media-grid__video-fallback">
                                        <svg viewBox="0 0 24 24" width="36" height="36" fill="currentColor" opacity="0.5"><polygon points="8,5 19,12 8,19"/></svg>
                                    </div>
                                    @endif
                                    <div class="media-grid__play-btn"><svg viewBox="0 0 24 24" width="48" height="48" fill="white"><polygon points="8,5 19,12 8,19"/></svg></div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                <h1 class="section-title">{{ trans_field($story, 'title') }}</h1>

                <div class="story__meta" style="color:var(--color-text-muted);margin-bottom:1.5rem">
                    @if($story->person_name)<span><strong>{{ __('common.full_name') }}:</strong> {{ trans_field($story, 'person_name') }}</span>@endif
                    @if($story->age)<span style="margin-{{ $isRtl ? 'right' : 'left' }}:1rem"><strong>العمر:</strong> {{ $story->age }} {{ __('common.age') }}</span>@endif
                    @if($story->location)<span style="margin-{{ $isRtl ? 'right' : 'left' }}:1rem"><strong>الموقع:</strong> {{ trans_field($story, 'location') }}</span>@endif
                </div>

                <div class="donate-project__description">{!! safe_html(trans_field($story, 'content')) !!}</div>

                @if($story->goal_amount > 0)
                <div class="donate-project__progress">
                    <div class="progress-bar">
                        <div class="progress-bar__fill" style="width:{{ $story->progressPercent() }}%"></div>
                    </div>
                    <div class="progress-stats">
                        <div class="progress-stats__item">
                            <span class="progress-stats__label">{{ __('common.raised') }}</span>
                            <span class="progress-stats__value">${{ number_format($story->raised_amount ?? 0) }}</span>
                        </div>
                        <div class="progress-stats__item">
                            <span class="progress-stats__label">{{ __('common.goal') }}</span>
                            <span class="progress-stats__value">${{ number_format($story->goal_amount) }}</span>
                        </div>
                        <div class="progress-stats__item">
                            <span class="progress-stats__label">%</span>
                            <span class="progress-stats__value">{{ $story->progressPercent() }}%</span>
                        </div>
                    </div>
                </div>
                @endif

                @if($donations->isNotEmpty())
                <div class="donate-project__donors">
                    <h3><i aria-hidden="true" class="fas fa-users" style="color:var(--color-primary)"></i> {{ __('donor_wall.recent_donations') }}</h3>
                    <div class="donors-list">
                        @foreach($donations as $donation)
                        <div class="donors-list__item">
                            <div class="donors-list__avatar" style="background:linear-gradient(135deg,var(--color-primary),var(--color-primary-light))">
                                {{ strtoupper(substr($donation->is_anonymous ? __('common.anonymous') : $donation->donor_name, 0, 1)) }}
                            </div>
                            <div class="donors-list__info">
                                <span class="donors-list__name">{{ $donation->is_anonymous ? __('common.anonymous') : $donation->donor_name }}</span>
                                <span class="donors-list__date">{{ $donation->donated_at?->diffForHumans() ?: $donation->created_at->diffForHumans() }}</span>
                            </div>
                            <span class="donors-list__amount" style="color:var(--color-primary)">${{ number_format($donation->amount, 0) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="donate-project__donors donate-project__donors--empty">
                    <div style="text-align:center;padding:2rem;color:#94a3b8">
                        <i aria-hidden="true" class="fas fa-heart" style="font-size:2rem;color:#d1d5db;margin-bottom:0.75rem;display:block"></i>
                        <p>{{ __('donor_wall.no_donations') }}</p>
                    </div>
                </div>
                @endif
            </div>

            <div class="donate-project__form">
                <div class="donate-form-card">
                    <h3>{{ __('donate.page_title') }}</h3>
                    <form action="{{ route('donate.store', ['locale' => $currentLocale]) }}" method="POST" class="donate-form {{ $isRtl ? 'donate-form--rtl' : 'donate-form--ltr' }}">
                        @csrf
<input type="text" name="hp_website" tabindex="-1" autocomplete="off" style="position:fixed;top:-100px;left:0" aria-hidden="true">


                        <div class="form-group">
                            <label>{{ __('donate.select_story') }}</label>
                            <select name="story_id" id="storySelect">
                                <option value="">{{ __('donate.select_story') }}</option>
                                @foreach($stories as $st)
                                <option value="{{ $st->id }}" data-id="{{ $st->id }}">{{ trans_field($st, 'title') }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>{{ __('donate.quick_amounts') }}</label>
                            <div class="amount-presets">
                                @foreach([10, 25, 50, 100, 250, 500] as $preset)
                                <button type="button" class="amount-preset" data-amount="{{ $preset }}">${{ $preset }}</button>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group">
                            <label>{{ __('donate.custom_amount') }}</label>
                            <input type="number" name="amount" id="donationAmount" min="1" step="0.01" required placeholder="{{ __('donate.min_amount') }}">
                        </div>

                        <div class="form-group">
                            <label>{{ __('common.full_name') }}</label>
                            <input type="text" name="donor_name" required>
                        </div>

                        <div class="form-group">
                            <label>{{ __('common.email') }}</label>
                            <input type="email" name="email" required>
                        </div>

                        <div class="form-group">
                            <label>{{ __('common.phone') }}</label>
                            <input type="tel" name="phone">
                        </div>

                        <div class="form-group">
                            <label>{{ __('donate.payment_method') }}</label>
                            <select name="payment_method_id" id="paymentMethodSelect" required>
                                <option value="">{{ __('donate.select_payment_method') }}</option>
                                @foreach($paymentMethods as $pm)
                                <option value="{{ $pm->id }}" data-driver="{{ $pm->gateway?->driver ?? '' }}">{{ $pm->name }} - {{ $pm->description }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-checkboxes">
                            <label class="checkbox-label">
                                <input type="checkbox" name="is_anonymous" value="1">
                                <span>{{ __('donate.anonymous_donation') }}
                                    <small>({{ __('donate.anonymous_hint') }})</small>
                                </span>
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="is_recurring" value="1" id="recurringToggle">
                                <span>{{ __('donate.recurring_donation') }}</span>
                            </label>
                        </div>

                        <div id="recurringOptions" class="form-group" style="display:none">
                            <label>{{ __('donate.recurring_interval') }}</label>
                            <select name="recurring_interval">
                                <option value="monthly">{{ __('donate.every_month') }}</option>
                                <option value="quarterly">{{ __('donate.every_3_months') }}</option>
                                <option value="yearly">{{ __('donate.every_year') }}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>{{ __('donate.donation_note') }}</label>
                            <textarea name="notes" rows="2"></textarea>
                        </div>

                        <button type="submit" class="btn btn--primary btn--block btn--lg">{{ __('common.complete_donation') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

{{-- Lightbox --}}
<div id="lightbox" class="lightbox" onclick="closeLightbox(event)" style="display:none">
    <button class="lightbox__close" onclick="closeLightbox()" aria-label="{{ __('common.close') }}">&times;</button>
    <button class="lightbox__nav lightbox__nav--prev" onclick="navigateLightbox(-1)" aria-label="{{ __('common.prev') }}">&#8249;</button>
    <img loading="lazy" id="lightbox-img" class="lightbox__img" alt="">
    <button class="lightbox__nav lightbox__nav--next" onclick="navigateLightbox(1)" aria-label="{{ __('common.next') }}">&#8250;</button>
    <div class="lightbox__counter" id="lightbox-counter"></div>
</div>

{{-- Video Lightbox --}}
<div id="videoLightbox" class="lightbox" onclick="closeVideoLightbox(event)" style="display:none">
    <button class="lightbox__close" onclick="closeVideoLightbox()" aria-label="{{ __('common.close') }}">&times;</button>
    <div id="videoLightboxContainer" style="width:80%;max-width:900px;aspect-ratio:16/9;border-radius:8px;overflow:hidden;background:#000"></div>
</div>

@push('head')
<style>
html, body { overflow-x: hidden; }
.donate-project__grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 2.5rem;
    align-items: start;
}
.donate-project__info { min-width: 0; }
.donate-project__form { min-width: 0; }
.media-grid { margin-bottom: 1.5rem; }
.media-grid__tabs {
    display: flex;
    gap: 8px;
    margin-bottom: 12px;
    flex-wrap: wrap;
}
.media-grid__tabs button {
    padding: 6px 16px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #fff;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.2s;
    color: var(--text-secondary, #64748b);
}
.media-grid__tabs button:hover { border-color: var(--color-primary); color: var(--color-primary); }
.media-grid__tabs button.active { background: var(--color-primary); border-color: var(--color-primary); color: #fff; }
.media-grid__items {
    display: flex;
    gap: 12px;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    padding-bottom: 8px;
    scrollbar-width: thin;
}
.media-grid__items::-webkit-scrollbar { height: 6px; }
.media-grid__items::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
.media-grid__item {
    flex: 0 0 calc((100% - 24px) / 3);
    aspect-ratio: 16/9;
    overflow: hidden;
    border-radius: 8px;
    position: relative;
    scroll-snap-align: start;
}
.donate-project__image {
    width: 100%;
    max-width: 720px;
    aspect-ratio: 16/9;
    overflow: hidden;
    border-radius: 16px;
    margin-bottom: 1.5rem;
}
.donate-project__image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.media-grid__item img,
.media-grid__item video { width: 100%; height: 100%; object-fit: cover; cursor: pointer; transition: transform 0.3s; }
.media-grid__item img:hover { transform: scale(1.03); }
.media-grid__video { width: 100%; height: 100%; }
.media-grid__video-thumb { width: 100%; height: 100%; cursor: pointer; position: relative; border-radius: 8px; overflow: hidden; }
.media-grid__play-btn { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; }
.media-grid__play-btn svg { filter: drop-shadow(0 4px 12px rgba(0,0,0,0.4)); opacity: 0.9; transition: transform 0.2s; }
.media-grid__video-thumb:hover .media-grid__play-btn svg { transform: scale(1.1); opacity: 1; }

.lightbox {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.92);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}
.lightbox__img {
    max-width: 90%;
    max-height: 85%;
    object-fit: contain;
    border-radius: 4px;
    box-shadow: 0 8px 40px rgba(0,0,0,0.5);
}
.lightbox__close {
    position: absolute;
    top: 20px;
    {{ $isRtl ? 'left' : 'right' }}: 20px;
    background: none;
    border: none;
    color: #fff;
    font-size: 36px;
    cursor: pointer;
    opacity: 0.8;
    z-index: 10;
    line-height: 1;
}
.lightbox__close:hover { opacity: 1; }
.lightbox__nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255,255,255,0.15);
    border: none;
    color: #fff;
    font-size: 48px;
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 8px;
    opacity: 0.7;
    transition: 0.2s;
    line-height: 1;
}
.lightbox__nav:hover { opacity: 1; background: rgba(255,255,255,0.25); }
.lightbox__nav--prev { {{ $isRtl ? 'right' : 'left' }}: 20px; }
.lightbox__nav--next { {{ $isRtl ? 'left' : 'right' }}: 20px; }
.lightbox__counter {
    position: absolute;
    bottom: 20px;
    {{ $isRtl ? 'right' : 'left' }}: 50%;
    transform: translateX({{ $isRtl ? '50%' : '-50%' }});
    color: rgba(255,255,255,0.6);
    font-size: 14px;
}

.media-grid__video-fallback { width:100%; height:100%; background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border-radius:8px; display:flex; align-items:center; justify-content:center; color:white; }

#videoLightboxContainer iframe, #videoLightboxContainer video { width:100%; height:100%; border-radius:8px; }
#videoLightboxContainer video { object-fit: contain; background:#000; }

.donate-project__description {
    line-height: 1.8;
    color: var(--text-secondary, #64748b);
    margin-bottom: 1.5rem;
}
.story__meta span {
    display: inline-block;
    font-size: 14px;
}
.donate-project__donors {
    margin-top: 2rem;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
}
.donate-project__donors h3 {
    font-size: 1.1rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.donate-project__donors--empty {
    border: 2px dashed #e2e8f0;
    background: #fafafa;
}
.donors-list {
    display: flex;
    flex-direction: column;
    gap: 6px;
    max-height: 360px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #e2e8f0 transparent;
}
.donors-list::-webkit-scrollbar { width: 4px; }
.donors-list::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 2px; }
.donors-list__item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 12px;
    background: var(--bg-secondary, #f8fafc);
    border-radius: 10px;
    transition: background 0.2s;
}
.donors-list__item:hover {
    background: #f1f5f9;
}
.donors-list__avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: var(--color-primary);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
    flex-shrink: 0;
}
.donors-list__info {
    flex: 1;
    min-width: 0;
}
.donors-list__name {
    display: block;
    font-weight: 600;
    font-size: 14px;
    color: var(--text-primary, #1e293b);
}
.donors-list__date {
    display: block;
    font-size: 12px;
    color: #94a3b8;
}
.donors-list__amount {
    font-weight: 700;
    font-size: 15px;
    color: var(--color-primary);
    white-space: nowrap;
}
.donate-form-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 24px rgba(0,0,0,0.06);
    position: sticky;
    top: 90px;
}
.donate-form-card h3 {
    margin-bottom: 1.25rem;
    font-size: 20px;
}
.form-group {
    margin-bottom: 1rem;
}
.form-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 4px;
    color: var(--text-primary, #1e293b);
}
.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.2s;
    background: #fff;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--color-primary);
}
.amount-presets {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}
.amount-preset {
    padding: 6px 14px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #fff;
    cursor: pointer;
    font-weight: 600;
    font-size: 13px;
    transition: all 0.2s;
    color: var(--text-primary, #1e293b);
}
.amount-preset:hover,
.amount-preset--active {
    border-color: var(--color-primary);
    background: #eff6ff;
    color: var(--color-primary);
}
.form-checkboxes {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 1rem;
}
.checkbox-label {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    cursor: pointer;
    font-size: 14px;
}
.checkbox-label input[type="checkbox"] {
    width: 16px;
    height: 16px;
    margin-top: 2px;
    accent-color: var(--color-primary);
}
.checkbox-label small {
    display: block;
    font-size: 12px;
    color: #94a3b8;
    margin-top: 1px;
}
.btn--block { width: 100%; }


@media (max-width: 900px) {
    .donate-project__grid {
        grid-template-columns: 1fr;
    }
    .donate-form-card {
        position: static;
    }
}
</style>
@endpush

@push('scripts')
<script nonce="{{ $cspNonce }}">
const lightboxImages = @json(array_map(fn($img) => asset('storage/'.$img), $allImages));
let currentIndex = 0;

function openLightbox(index) {
    currentIndex = index;
    document.getElementById('lightbox').style.display = 'flex';
    document.getElementById('lightbox-img').src = lightboxImages[index];
    updateCounter();
    document.body.style.overflow = 'hidden';
}

function closeLightbox(e) {
    if (e && e.target !== e.currentTarget) return;
    document.getElementById('lightbox').style.display = 'none';
    document.body.style.overflow = '';
}

function navigateLightbox(dir) {
    currentIndex = (currentIndex + dir + lightboxImages.length) % lightboxImages.length;
    document.getElementById('lightbox-img').src = lightboxImages[currentIndex];
    updateCounter();
}

function updateCounter() {
    document.getElementById('lightbox-counter').textContent =
        (currentIndex + 1) + ' / ' + lightboxImages.length;
}

document.addEventListener('keydown', function(e) {
    if (document.getElementById('lightbox').style.display === 'flex') {
        if (e.key === 'Escape') { closeLightbox(); return; }
        if (e.key === 'ArrowLeft' && {{ $isRtl ? 'false' : 'true' }}) { navigateLightbox(-1); return; }
        if (e.key === 'ArrowRight' && {{ $isRtl ? 'false' : 'true' }}) { navigateLightbox(1); return; }
        if (e.key === 'ArrowLeft' && {{ $isRtl ? 'true' : 'false' }}) { navigateLightbox(1); return; }
        if (e.key === 'ArrowRight' && {{ $isRtl ? 'true' : 'false' }}) { navigateLightbox(-1); return; }
    }
});

(function() {
    const presets = document.querySelectorAll('.amount-preset');
    const amountInput = document.getElementById('donationAmount');
    presets.forEach(btn => {
        btn.addEventListener('click', function() {
            presets.forEach(b => b.classList.remove('amount-preset--active'));
            this.classList.add('amount-preset--active');
            amountInput.value = this.dataset.amount;
        });
    });
    document.getElementById('recurringToggle')?.addEventListener('change', function() {
        document.getElementById('recurringOptions').style.display = this.checked ? 'block' : 'none';
    });

    document.getElementById('storySelect').addEventListener('change', function() {
        if (this.value) {
            var id = this.options[this.selectedIndex].dataset.id;
            window.location.href = '{{ route("donate.story", ["locale" => $currentLocale, "id" => "ID_PLACEHOLDER"]) }}'.replace('ID_PLACEHOLDER', id);
        }
    });

    var pmSelect = document.getElementById('paymentMethodSelect');

    document.querySelectorAll('[data-component="media-grid"]').forEach(function(grid) {
        var tabs = grid.querySelectorAll('.media-grid__tabs button');
        var items = grid.querySelectorAll('.media-grid__item');
        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                tabs.forEach(function(t) { t.classList.remove('active'); });
                this.classList.add('active');
                var filter = this.dataset.filter;
                items.forEach(function(item) {
                    item.style.display = (filter === 'all' || item.dataset.type === filter) ? '' : 'none';
                });
            });
        });
    });
})();
</script>
@endpush