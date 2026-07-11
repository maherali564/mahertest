@extends('layouts.app')
@section('content')
<section class="section">
    <div class="container prose">
        <h1 class="section-title">{{ trans_field($project, 'title') }}</h1>

        @php
            $mediaItems = $project->media;
            $imageItems = $mediaItems->where('type', 'image')->values();
        @endphp

        @if($mediaItems->isNotEmpty())
        <div x-data="{ filter: 'all', activeImg: 0 }" class="media-gallery">
            <div class="media-filter">
                <button :class="filter === 'all' ? 'active' : ''" @click="filter = 'all'">{{ __('common.all') }}</button>
                <button :class="filter === 'image' ? 'active' : ''" @click="filter = 'image'">{{ __('common.images') }}</button>
                <button :class="filter === 'video' ? 'active' : ''" @click="filter = 'video'">{{ __('common.videos') }}</button>
            </div>
            <div class="media-grid">
                @foreach($mediaItems as $item)
                <div x-show="filter === 'all' || filter === '{{ $item->type }}'" class="media-grid__item" data-type="{{ $item->type }}">
                    @if($item->type === 'image')
                    <img loading="lazy" src="{{ asset('storage/'.$item->path) }}" alt="" class="media-grid__thumb" @click="activeImg = {{ $imageItems->search(fn($i) => $i->id === $item->id) }}; openLightbox(activeImg)">
                    @else
                    @php
                        $vUrl = $item->path;
                        $vType = 'upload';
                        $vSrc = asset('storage/'.ltrim($vUrl, '/'));
                        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $vUrl, $m)) {
                            $vSrc = 'https://www.youtube.com/embed/'.$m[1];
                            $vType = 'youtube';
                        } elseif (preg_match('/vimeo\.com\/(\d+)/', $vUrl, $m)) {
                            $vSrc = 'https://player.vimeo.com/video/'.$m[1];
                            $vType = 'vimeo';
                        }
                    @endphp
                    <div class="media-grid__video" onclick="openVideoLightbox('{{ $vSrc }}','{{ $vType }}')">
                        <div class="media-grid__video-thumb" style="cursor:pointer;position:relative;width:100%;height:100%">
                            @if($item->thumbnail)
                            <img loading="lazy" src="{{ asset('storage/'.$item->thumbnail) }}" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:8px">
                            @elseif($project->image)
                            <img loading="lazy" src="{{ asset('storage/'.$project->image) }}" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:8px">
                            @endif
                            <div class="media-grid__play-btn"><svg viewBox="0 0 24 24" width="48" height="48" fill="white"><polygon points="8,5 19,12 8,19"/></svg></div>
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @elseif($project->image)
        <img loading="lazy" src="{{ asset('storage/'.$project->image) }}" alt="" style="max-width:100%;border-radius:12px;margin:1.5rem 0">
        @endif

        <div>{!! safe_html(trans_field($project, 'content') ?: nl2br(e(trans_field($project, 'description')))) !!}</div>

        @if($project->goal_amount > 0)
        <div class="project-progress" style="margin:2rem 0">
            <div class="project-progress__bar">
                <div class="project-progress__fill" style="width:{{ $project->progressPercent() }}%"></div>
            </div>
            <div class="project-progress__stats">
                <span>${{ number_format($project->raised_amount ?? 0) }} {{ __('common.raised') }}</span>
                <span>${{ number_format($project->goal_amount) }} {{ __('common.goal') }}</span>
            </div>
        </div>
        @endif

        <a href="{{ route('donate.project', ['locale' => $currentLocale, 'slug' => $project->slug]) }}" class="btn btn--primary" style="margin-top:1rem">{{ __('common.contribute') }}</a>
    </div>
</section>

@php $projectDonations = $project->donations()->completed()->latest()->limit(20)->get(); @endphp
@if($projectDonations->isNotEmpty())
<section class="section" style="background:#f8fafc;padding-top:2rem;padding-bottom:3rem">
    <div class="container">
        <div class="section-header" style="text-align:center;margin-bottom:1.5rem">
            <h2 class="section-title" style="font-size:1.5rem">{{ __('common.donor_wall.recent_donations') }}</h2>
        </div>
        <div class="donor-wall__compact">
            @foreach($projectDonations as $d)
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
.container.prose { overflow: hidden; }
.media-gallery { margin: 1.5rem 0; }
.media-filter { display:flex; gap:0.5rem; margin-bottom:1rem; flex-wrap:wrap; }
.media-filter button { padding:0.4rem 1rem; border-radius:999px; border:1px solid #d1d5db; background:#fff; cursor:pointer; font-size:0.85rem; transition:all 0.2s; }
.media-filter button.active, .media-filter button:hover { background:var(--color-primary); color:#fff; border-color:var(--color-primary); }
.media-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:0.75rem; }
.media-grid__item { aspect-ratio:16/9; overflow:hidden; border-radius:8px; position:relative; }
.media-grid__thumb { width:100%; height:100%; object-fit:cover; cursor:pointer; transition:transform 0.2s; }
.media-grid__thumb:hover { transform:scale(1.04); }
.media-grid__video { width:100%; height:100%; }
.media-grid__video-thumb { width:100%; height:100%; display:flex; align-items:center; justify-content:center; }
.media-grid__play-btn { position:absolute; inset:0; display:flex; align-items:center; justify-content:center; background:rgba(0,0,0,0.3); transition:background 0.2s; border-radius:8px; }
.media-grid__play-btn:hover { background:rgba(0,0,0,0.5); }
.media-grid__play-btn svg { filter:drop-shadow(0 2px 4px rgba(0,0,0,0.4)); }
.lightbox { position:fixed; inset:0; background:rgba(0,0,0,0.92); z-index:9999; display:flex; align-items:center; justify-content:center; }
.lightbox__img { max-width:90%; max-height:85%; object-fit:contain; border-radius:4px; box-shadow:0 8px 40px rgba(0,0,0,0.5); }
.lightbox__close { position:absolute; top:20px; {{ $isRtl ? 'left' : 'right' }}:20px; background:none; border:none; color:#fff; font-size:36px; cursor:pointer; opacity:0.8; z-index:10; line-height:1; }
.lightbox__close:hover { opacity:1; }
.lightbox__nav { position:absolute; top:50%; transform:translateY(-50%); background:rgba(255,255,255,0.15); border:none; color:#fff; font-size:48px; padding:10px 20px; cursor:pointer; border-radius:8px; opacity:0.7; transition:0.2s; line-height:1; }
.lightbox__nav:hover { opacity:1; background:rgba(255,255,255,0.25); }
.lightbox__nav--prev { {{ $isRtl ? 'right' : 'left' }}:20px; }
.lightbox__nav--next { {{ $isRtl ? 'left' : 'right' }}:20px; }
.lightbox__counter { position:absolute; bottom:20px; {{ $isRtl ? 'right' : 'left' }}:50%; transform:translateX({{ $isRtl ? '50%' : '-50%' }}); color:rgba(255,255,255,0.6); font-size:14px; }
.lightbox__video-container { width:90%; max-width:900px; aspect-ratio:16/9; border-radius:8px; overflow:hidden; }
.lightbox__video-container iframe,
.lightbox__video-container video { width:100%; height:100%; border:0; }
.donor-wall__compact { max-width:600px; margin:0 auto; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; background:#fff; }
.donor-wall__compact .donor-entry { display:flex; align-items:center; gap:0.75rem; padding:0.75rem 1rem; border-bottom:1px solid #f1f5f9; }
.donor-wall__compact .donor-entry:last-child { border-bottom:none; }
.donor-wall__compact .donor-entry:hover { background:#f8fafc; }
</style>

<script nonce="{{ $cspNonce }}">
const lightboxImages = @json($imageItems->map(fn($i) => asset('storage/'.$i->path))->values());
let currentIndex = 0;
function openLightbox(index) { currentIndex=index; document.getElementById('lightbox').style.display='flex'; document.getElementById('lightbox-img').src=lightboxImages[index]; updateCounter(); document.body.style.overflow='hidden'; }
function closeLightbox(e) { if(e&&e.target!==e.currentTarget) return; document.getElementById('lightbox').style.display='none'; document.body.style.overflow=''; }
function navigateLightbox(dir) { currentIndex=(currentIndex+dir+lightboxImages.length)%lightboxImages.length; document.getElementById('lightbox-img').src=lightboxImages[currentIndex]; updateCounter(); }
function updateCounter() { document.getElementById('lightbox-counter').textContent=(currentIndex+1)+' / '+lightboxImages.length; }
document.addEventListener('keydown',function(e){ if(document.getElementById('lightbox').style.display!=='flex') return; if(e.key==='Escape') closeLightbox(); if(e.key==='ArrowLeft') navigateLightbox(-1); if(e.key==='ArrowRight') navigateLightbox(1); });
</script>
@endsection
