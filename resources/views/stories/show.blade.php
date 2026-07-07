@extends('layouts.app')
@section('content')
<section class="section">
    <div class="container prose">
        <h1 class="section-title">{{ trans_field($story, 'title') }}</h1>

        @php
            $allImages = $story->images ?? [];
            if ($story->image && !in_array($story->image, $allImages)) {
                array_unshift($allImages, $story->image);
            }
            $galleryCount = count($allImages);
        @endphp

        @if($galleryCount > 0)
        <div class="story-gallery {{ $galleryCount > 1 ? 'story-gallery--multiple' : 'story-gallery--single' }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
            <div class="story-gallery__main">
                <img loading="lazy" src="{{ asset('storage/'.$allImages[0]) }}"
                     alt=""
                     class="story-gallery__main-img"
                     onclick="openLightbox(0)"
                     style="cursor:pointer">
            </div>
            @if($galleryCount > 1)
            <div class="story-gallery__thumbs">
                @foreach($allImages as $i => $img)
                <img loading="lazy" src="{{ asset('storage/'.$img) }}"
                     alt=""
                     class="story-gallery__thumb {{ $i === 0 ? 'story-gallery__thumb--active' : '' }}"
                     onclick="openLightbox({{ $i }})">
                @endforeach
            </div>
            @endif
        </div>
        @elseif($story->image)
        <img loading="lazy" src="{{ asset('storage/'.$story->image) }}" alt="" class="story__image" style="max-width:100%;border-radius:12px;margin:1.5rem 0">
        @endif

        @if($story->video_url)
        @php
            $vType = $story->video_type;
            $vUrl = $story->video_url;
            $videoSrc = null;
            if ($vType === 'youtube') {
                preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $vUrl, $m);
                $id = $m[1] ?? $vUrl;
                $videoSrc = 'https://www.youtube.com/embed/'.$id;
                $vType = 'youtube';
            } elseif ($vType === 'vimeo') {
                preg_match('/vimeo\.com\/(\d+)/', $vUrl, $m);
                $id = $m[1] ?? $vUrl;
                $videoSrc = 'https://player.vimeo.com/video/'.$id;
                $vType = 'vimeo';
            } else {
                $videoSrc = asset('storage/'.ltrim($vUrl, '/'));
                $vType = 'upload';
            }
        @endphp
        @php
            $vThumbKey = $story->video_url ? ltrim($story->video_url, '/') : null;
            $thumbnails = $story->video_thumbnails ?? [];
            $vThumbPath = $vThumbKey && isset($thumbnails[$vThumbKey]) && Storage::disk('public')->exists($thumbnails[$vThumbKey])
                ? $thumbnails[$vThumbKey]
                : null;
            $vThumbUrl = $vThumbPath
                ? asset('storage/'.$vThumbPath)
                : ($story->image && Storage::disk('public')->exists($story->image) ? asset('storage/'.$story->image) : null);
        @endphp
        <div class="media-grid__video" style="max-width:100%;margin:1.5rem 0;border-radius:12px;overflow:hidden;position:relative;aspect-ratio:16/9;cursor:pointer" onclick="openVideoLightbox('{{ $videoSrc }}','{{ $vType }}')">
            <div class="media-grid__video-thumb" style="width:100%;height:100%;background:linear-gradient(135deg,#1e293b,#334155);display:flex;align-items:center;justify-content:center;">
                @if($vThumbUrl)<img loading="lazy" src="{{ $vThumbUrl }}" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover">@endif
                <div style="position:relative;z-index:1;background:rgba(0,0,0,0.4);border-radius:50%;padding:16px;display:flex;align-items:center;justify-content:center;">
                    <svg viewBox="0 0 24 24" width="64" height="64" fill="white"><polygon points="8,5 19,12 8,19"/></svg>
                </div>
            </div>
        </div>
        @endif
        @foreach($story->videos ?? [] as $uploadedVideo)
        @if($uploadedVideo !== $story->video_url && Storage::disk('public')->exists($uploadedVideo))
        @php
            $videoSrc = asset('storage/'.ltrim($uploadedVideo, '/'));
            $thumbnails = $story->video_thumbnails ?? [];
            $vThumb = isset($thumbnails[$uploadedVideo]) && Storage::disk('public')->exists($thumbnails[$uploadedVideo])
                ? asset('storage/'.$thumbnails[$uploadedVideo])
                : null;
        @endphp
        <div class="media-grid__video" style="max-width:100%;margin:1.5rem 0;border-radius:12px;overflow:hidden;position:relative;aspect-ratio:16/9;cursor:pointer" onclick="openVideoLightbox('{{ $videoSrc }}','upload')">
            <div class="media-grid__video-thumb" style="width:100%;height:100%;background:linear-gradient(135deg,#1e293b,#334155);display:flex;align-items:center;justify-content:center;">
                @if($vThumb)<img loading="lazy" src="{{ $vThumb }}" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover">@endif
                <div style="position:relative;z-index:1;background:rgba(0,0,0,0.4);border-radius:50%;padding:16px;display:flex;align-items:center;justify-content:center;">
                    <svg viewBox="0 0 24 24" width="64" height="64" fill="white"><polygon points="8,5 19,12 8,19"/></svg>
                </div>
            </div>
        </div>
        @endif
        @endforeach

        <div class="story__meta" style="color:var(--color-text-muted);margin-bottom:1rem">
            @if($story->person_name)<span><strong>{{ __('common.full_name') }}:</strong> {{ trans_field($story, 'person_name') }}</span>@endif
            @if($story->age)<span style="margin-{{ $isRtl ? 'right' : 'left' }}:1rem"><strong>العمر:</strong> {{ $story->age }}</span>@endif
            @if($story->location)<span style="margin-{{ $isRtl ? 'right' : 'left' }}:1rem"><strong>الموقع:</strong> {{ trans_field($story, 'location') }}</span>@endif
        </div>
        <div>{!! safe_html(trans_field($story, 'content')) !!}</div>

        @if($story->goal_amount > 0)
        <div class="donate-project__progress" style="margin:2rem 0">
            <div class="progress-bar" style="width:100%;height:12px;background:#e2e8f0;border-radius:6px;overflow:hidden;margin-bottom:1rem">
                <div class="progress-bar__fill" style="width:{{ $story->progressPercent() }}%;height:100%;background:var(--color-primary);border-radius:6px;transition:width 0.5s"></div>
            </div>
            <div class="progress-stats" style="display:flex;gap:1.5rem;flex-wrap:wrap">
                <div><span style="display:block;font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8">{{ __('common.raised') }}</span><span style="display:block;font-size:18px;font-weight:700">${{ number_format($story->raised_amount ?? 0) }}</span></div>
                <div><span style="display:block;font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8">{{ __('common.goal') }}</span><span style="display:block;font-size:18px;font-weight:700">${{ number_format($story->goal_amount) }}</span></div>
                <div><span style="display:block;font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8">%</span><span style="display:block;font-size:18px;font-weight:700">{{ $story->progressPercent() }}%</span></div>
            </div>
        </div>
        @endif

        <a href="{{ route('donate.story', ['locale' => $currentLocale, 'id' => $story->id]) }}" class="btn btn--primary" style="margin-top:2rem">{{ __('common.contribute') }}</a>
    </div>
</section>

@php $storyDonations = $story->donations()->completed()->latest()->limit(20)->get(); @endphp
@if($storyDonations->isNotEmpty())
<section class="section" style="background:#f8fafc;padding-top:2rem;padding-bottom:3rem">
    <div class="container">
        <div class="section-header" style="text-align:center;margin-bottom:1.5rem">
            <h2 class="section-title" style="font-size:1.5rem">{{ __('donor_wall.recent_donations') }}</h2>
        </div>
        <div class="donor-wall__compact">
            @foreach($storyDonations as $d)
            <div class="donor-entry">
                <div class="donor-entry__avatar" style="width:36px;height:36px;font-size:0.85rem;background:linear-gradient(135deg,var(--color-primary),var(--color-primary-light))">
                    {{ strtoupper(substr($d->is_anonymous ? __('common.anonymous') : $d->donor_name, 0, 1)) }}
                </div>
                <div class="donor-entry__info">
                    <strong class="donor-entry__name" style="font-size:0.9rem;color:#1e293b">{{ $d->is_anonymous ? __('common.anonymous') : $d->donor_name }}</strong>
                    <span class="donor-entry__meta" style="font-size:0.8rem;color:#64748b">{{ $d->donated_at?->diffForHumans() ?: $d->created_at->diffForHumans() }}</span>
                </div>
                <span class="donor-entry__amount" style="font-size:1rem;color:var(--color-primary)">${{ number_format($d->amount, 0) }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Image Lightbox --}}
<div id="lightbox" class="lightbox" onclick="closeLightbox(event)" style="display:none">
    <button class="lightbox__close" onclick="closeLightbox()" aria-label="{{ __('common.close') }}">&times;</button>
    <button class="lightbox__nav lightbox__nav--prev" onclick="navigateLightbox(-1)" aria-label="{{ __('common.prev') }}">&#8249;</button>
    <img loading="lazy" id="lightbox-img" class="lightbox__img" alt="">
    <button class="lightbox__nav lightbox__nav--next" onclick="navigateLightbox(1)" aria-label="{{ __('common.next') }}">&#8250;</button>
    <div class="lightbox__counter" id="lightbox-counter"></div>
</div>

<div id="videoLightbox" class="lightbox" onclick="closeVideoLightbox(event)" style="display:none">
    <button class="lightbox__close" onclick="closeVideoLightbox()" aria-label="{{ __('common.close') }}">&times;</button>
    <div id="videoLightboxContainer" class="lightbox__video-container"></div>
</div>

<style>
.story-gallery { max-width: min(420px, 100%); }
.story-gallery--single { margin: 1.5rem auto; }
.story-gallery--multiple {
    float: {{ $isRtl ? 'right' : 'left' }};
    margin-{{ $isRtl ? 'left' : 'right' }}: 1.5rem;
    margin-bottom: 1.5rem;
}
.story-gallery__main {
    width: 100%;
    aspect-ratio: 16/9;
    overflow: hidden;
    border-radius: 12px;
    margin-bottom: 12px;
}
.story-gallery__main-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}
.story-gallery__main-img:hover { transform: scale(1.03); }
.story-gallery__thumbs {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
.story-gallery__thumb {
    width: 100px;
    height: 70px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    opacity: 0.6;
    transition: all 0.2s;
    border: 2px solid transparent;
}
.story-gallery__thumb:hover,
.story-gallery__thumb--active { opacity: 1; border-color: var(--color-primary); }
.container.prose { overflow: hidden; }

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
.lightbox__video-container { width:90%; max-width:900px; aspect-ratio:16/9; border-radius:8px; overflow:hidden; }
.lightbox__video-container iframe,
.lightbox__video-container video { width:100%; height:100%; border:0; }
.media-grid__play-btn { display:flex; align-items:center; justify-content:center; background:rgba(0,0,0,0.3); border-radius:50%; padding:12px; transition:background 0.2s; }
.media-grid__play-btn:hover { background:rgba(0,0,0,0.5); }
.media-grid__play-btn svg { filter:drop-shadow(0 2px 8px rgba(0,0,0,0.5)); }
.donor-wall__compact { max-width:600px; margin:0 auto; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; background:#fff; }
.donor-wall__compact .donor-entry { display:flex; align-items:center; gap:0.75rem; padding:0.75rem 1rem; border-bottom:1px solid #f1f5f9; }
.donor-wall__compact .donor-entry:last-child { border-bottom:none; }
.donor-wall__compact .donor-entry:hover { background:#f8fafc; }
</style>

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
    if (document.getElementById('lightbox').style.display !== 'flex') return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft' && {{ $isRtl ? 'false' : 'true' }}) navigateLightbox(-1);
    if (e.key === 'ArrowRight' && {{ $isRtl ? 'false' : 'true' }}) navigateLightbox(1);
    if (e.key === 'ArrowLeft' && {{ $isRtl ? 'true' : 'false' }}) navigateLightbox(1);
    if (e.key === 'ArrowRight' && {{ $isRtl ? 'true' : 'false' }}) navigateLightbox(-1);
});
</script>
@endsection
