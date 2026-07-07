@extends('layouts.app')
@php $s = $settings; @endphp

@section('content')
{{-- Hero Slider --}}
<section class="hero-slider" id="home">
    <div class="swiper heroSwiper">
        <div class="swiper-wrapper">
            @forelse($sliders as $slider)
            @php
                $textColor = $slider->text_color ?? '#fff';
                $position = $slider->text_position ?? 'center';
                $parts = explode('-', $position);
                $vertical = $parts[0]; // top, center, bottom
                $horizontal = $parts[1] ?? 'center'; // left, center, right
                $justifyMap = ['top' => 'flex-start', 'center' => 'center', 'bottom' => 'flex-end'];
                $alignMap = ['left' => 'flex-start', 'center' => 'center', 'right' => 'flex-end'];
                $textAlignMap = ['left' => 'start', 'center' => 'center', 'right' => 'end'];
                $justifyContent = $justifyMap[$vertical] ?? 'center';
                $alignItems = $alignMap[$horizontal] ?? 'center';
                $textAlign = $textAlignMap[$horizontal] ?? 'center';
                $btnColor = $slider->button_color ?? '#d4a853';
                $btnTextColor = $slider->button_text_color ?? '#fff';
                $overlayOpacity = ($slider->overlay_opacity ?? 45) / 100;
            @endphp
            <div class="swiper-slide hero-slide" style="@if($slider->image) --slide-bg: url('{{ asset('storage/'.$slider->image) }}'); @endif justify-content: {{ $justifyContent }}; align-items: {{ $alignItems }};">
                <div class="hero-slide__overlay" style="opacity: {{ $overlayOpacity }}"></div>
                <div class="container hero-slide__content" style="text-align: {{ $textAlign }}; color: {{ $textColor }};">
                    <h1 class="hero-slide__title" style="color: {{ $textColor }};" data-swiper-animate="fade-up">{{ trans_field($slider, 'title') }}</h1>
                    <p class="hero-slide__subtitle" style="color: {{ $textColor }};" data-swiper-animate="fade-up" data-delay="200">{{ trans_field($slider, 'subtitle') }}</p>
                    @if(trans_field($slider, 'button_text'))
                    <div class="hero-slide__action" data-swiper-animate="fade-up" data-delay="400">
                    <a href="{{ $slider->button_link ?: route('donate.page', ['locale' => $currentLocale]) }}" class="btn btn--primary btn--lg" style="background: {{ $btnColor }}; color: {{ $btnTextColor }};">{{ trans_field($slider, 'button_text') }}</a>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="swiper-slide hero-slide" style="--slide-bg: linear-gradient(135deg, #0d6b4f 0%, #0a4a35 50%, #062218 100%)">
                <div class="hero-slide__overlay"></div>
                <div class="container hero-slide__content">
                    <h1 class="hero-slide__title" data-swiper-animate="fade-up">{{ trans_field($s, 'hero_title') }}</h1>
                    <p class="hero-slide__subtitle" data-swiper-animate="fade-up" data-delay="200">{{ trans_field($s, 'hero_subtitle') }}</p>
                    <div class="hero-slide__action" data-swiper-animate="fade-up" data-delay="400">
                    <a href="{{ route('donate.page', ['locale' => $currentLocale]) }}" class="btn btn--primary btn--lg">{{ __('common.donate_now') }}</a>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
        <div class="swiper-pagination"></div>
      
    </div>
</section>

<section class="hm-section hm-section--light section section--sm">
    <div class="container">
        <div class="hm-trust-card">
            <div class="hm-trust-icon-wrap">
<i aria-hidden="true" class="fas fa-hand-holding-heart"></i>
            </div>
            <h2 class="hm-trust-text">{{ app()->getLocale() === 'ar' ? 'تبرعك لـ ساهم يضمن وصول الدعم الإنساني العاجل لمن يستحقه في أي مكان حول العالم، نحن منظمة دولية تعمل بحيادية تامة، وتقدم الإغاثة الفورية دون تمييز على أساس العرق، أو الجنس، أو الدين. بعيداً عن القيود أو التوجهات السياسية ، ومن قلب المعاناة، يتحول عطاؤك معنا مباشرة إلى حياة وأمل للمتضررين والمنكوبين
' : 'Your donation to Sahem ensures that urgent humanitarian aid reaches those who deserve it, anywhere in the world. We are an international organization that operates with complete neutrality, providing immediate relief without discrimination based on race, gender, or religion. Free from political constraints or biases, and from the heart of suffering, your giving with us transforms directly into life and hope for the affected and the afflicted' }}</h2>
        </div>
    </div>
</section>


{{-- Emergency Campaigns --}}
@php $emergencyCampaigns = \App\Models\EmergencyCampaign::where('is_active', true)->where(function($q){$q->whereNull('ends_at')->orWhere('ends_at','>',now());})->orderBy('is_featured','desc')->orderBy('created_at','desc')->get(); @endphp
@if($emergencyCampaigns->isNotEmpty())
<section class="section section--sm" style="background:#fff">
    <div class="container">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
            <h2 style="font-size:1.5rem;font-weight:800;display:flex;align-items:center;gap:.5rem">
                <span style="background:var(--color-danger);color:#fff;font-size:.75rem;padding:2px 10px;border-radius:20px">{{ __('campaigns.urgent') }}</span>
                {{ app()->getLocale() === 'ar' ? 'حملات تبرعية عاجلة' : 'Emergency Campaigns' }}
            </h2>
            <a href="{{ route('emergency-campaigns.index', ['locale' => $currentLocale]) }}" style="font-size:.85rem;color:var(--color-primary);font-weight:600;text-decoration:none">
                {{ app()->getLocale() === 'ar' ? 'عرض الكل' : 'View All' }} <i aria-hidden="true" class="fas fa-arrow-{{ $isRtl ? 'left' : 'right' }}"></i>
            </a>
        </div>
        <div class="scroll-x">
            <div class="scroll-x__track" id="ecTrack">
                @foreach($emergencyCampaigns as $ec)
                <a href="{{ route('emergency-campaigns.show', ['locale' => $currentLocale, 'campaign' => $ec->slug]) }}" style="text-decoration:none;color:inherit;flex:0 0 280px;scroll-snap-align:start">
                    <article class="ec-card" style="border-radius:12px;overflow:hidden;background:#fff;box-shadow:0 2px 12px rgba(0,0,0,.08);border-top:3px solid var(--color-danger);transition:transform .2s" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div style="height:150px;overflow:hidden;position:relative">
                            @if($ec->image)
                            <img loading="lazy" src="{{ asset('storage/'.$ec->image) }}" alt="" style="width:100%;height:100%;object-fit:cover">
                            @else
                            <div style="height:100%;background:linear-gradient(135deg,var(--color-danger),#c0392b);display:flex;align-items:center;justify-content:center;color:#fff;font-size:2rem"><i aria-hidden="true" class="fas fa-exclamation-triangle"></i></div>
                            @endif
                            <span style="position:absolute;top:8px;right:8px;background:var(--color-danger);color:#fff;padding:2px 10px;border-radius:20px;font-size:.7rem;font-weight:700">{{ app()->getLocale() === 'ar' ? 'عاجل' : 'URGENT' }}</span>
                        </div>
                        <div style="padding:1rem">
                            <h3 style="font-size:.95rem;font-weight:700;margin-bottom:.35rem">{{ trans_field($ec, 'title') }}</h3>
                            @if(trans_field($ec, 'excerpt'))
                            <p style="font-size:.78rem;color:#64748b;margin-bottom:.5rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;line-height:1.4">{{ trans_field($ec, 'excerpt') }}</p>
                            @endif
                            <div class="progress-bar" style="height:6px;margin-bottom:.5rem"><div class="progress-bar__fill" style="width:{{$ec->progressPercent}}%;height:100%;background:linear-gradient(90deg,var(--color-danger),#f39c12)"></div></div>
                            <div style="display:flex;justify-content:space-between;font-size:.8rem">
                                <span style="font-weight:700;color:var(--color-primary)">${{ number_format($ec->collected_amount,0) }}</span>
                                <span style="color:#999">{{ __('campaigns.goal_short') }} ${{ number_format($ec->target_amount,0) }}</span>
                            </div>
                        </div>
                    </article>
                </a>
                @endforeach
            </div>
            <button class="scroll-x__btn scroll-x__btn--prev" onclick="scrollTrack('ecTrack',-1)" aria-label="{{ __('common.prev') }}">
                <i aria-hidden="true" class="fas fa-chevron-{{ $isRtl ? 'right' : 'left' }}"></i>
            </button>
            <button class="scroll-x__btn scroll-x__btn--next" onclick="scrollTrack('ecTrack',1)" aria-label="{{ __('common.next') }}">
                <i aria-hidden="true" class="fas fa-chevron-{{ $isRtl ? 'left' : 'right' }}"></i>
            </button>
        </div>
    </div>
</section>
@endif

{{-- Trust Mission --}}
<section class="hm-section section section--sm">
    <div class="container">
        <div class="hm-header">
            <div class="home-trust-icon"><i aria-hidden="true" class="fas fa-globe-asia"></i></div>
            <h2 class="hm-title">{{ app()->getLocale() === 'ar' ? 'عطاؤكم يصل لكل مكان بالعالم' : 'your giving reaches every corner of the world' }}</h2>
           
        </div>
        <div class="hm-map-wrap">
            @include('partials.hero-map')
        </div>
    </div>
</section>

{{-- Projects --}}
<section class="projects section" id="projects">
    <div class="container">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
            <h2 class="section-title section-title--accent" style="margin-bottom:0">{{ __('common.main_projects') }}</h2>
            <a href="{{ route('projects.index', ['locale' => $currentLocale]) }}" style="font-size:.85rem;color:var(--color-primary);font-weight:600;text-decoration:none">
                {{ __('common.view_all') }} <i aria-hidden="true" class="fas fa-arrow-{{ $isRtl ? 'left' : 'right' }}"></i>
            </a>
        </div>
        <div class="scroll-x">
            <div class="scroll-x__track" id="projectsTrack">
                @foreach($projects as $project)
                <article class="project-card scroll-x__item">
                    @if($project->first_image)
                    <div class="project-card__image" style="background-image: url('{{ asset('storage/'.$project->first_image) }}')"></div>
                    @else
                    <div class="project-card__image project-card__image--placeholder"></div>
                    @endif
                    <div class="project-card__body">
                        <h3>{{ trans_field($project, 'title') }}</h3>
                        @if(trans_field($project, 'excerpt'))
                        <p style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;line-height:1.5;font-size:.85rem;color:#64748b">{{ trans_field($project, 'excerpt') }}</p>
                        @endif
                        @if($project->goal_amount > 0 || ($project->raised_amount ?? 0) > 0)
                        <div class="project-progress">
                            <div class="progress-bar">
                                <div class="progress-bar__fill" style="width: {{ $project->progressPercent() }}%"></div>
                            </div>
                            <div class="progress-stats">
                                <span>{{ __('common.raised') }}: <strong><span data-amount="{{ $project->raised_amount }}">${{ number_format($project->raised_amount, 0) }}</span></strong></span>
                                <span>{{ __('common.goal') }}: <strong><span data-amount="{{ $project->goal_amount }}">${{ number_format($project->goal_amount, 0) }}</span></strong></span>
                                <span><strong>{{ $project->progressPercent() }}%</strong></span>
                            </div>
                        </div>
                        @endif
                        <div class="project-card__actions">
                            <a href="{{ route('donate.project', ['locale' => $currentLocale, 'slug' => $project->slug]) }}" class="btn btn--primary">{{ __('common.contribute') }}</a>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
            <button class="scroll-x__btn scroll-x__btn--prev" onclick="scrollTrack('projectsTrack',-1)" aria-label="{{ __('common.prev') }}">
                <i aria-hidden="true" class="fas fa-chevron-{{ $isRtl ? 'right' : 'left' }}"></i>
            </button>
            <button class="scroll-x__btn scroll-x__btn--next" onclick="scrollTrack('projectsTrack',1)" aria-label="{{ __('common.next') }}">
                <i aria-hidden="true" class="fas fa-chevron-{{ $isRtl ? 'left' : 'right' }}"></i>
            </button>
        </div>
    </div>
</section>



{{-- Stories: Voices Awaiting Life --}}
@if($stories->isNotEmpty())
<section class="stories section">
    <div class="container">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
            <div>
                <h2 class="section-title section-title--accent" style="margin-bottom:0">{{ __('common.nav_stories') }}</h2>
                <p style="margin:2px 0 0;font-size:.85rem;color:#64748b">{{ __('home.voices_waiting_desc') }}</p>
            </div>
            <a href="{{ route('stories.index', ['locale' => $currentLocale]) }}" style="font-size:.85rem;color:var(--color-primary);font-weight:600;text-decoration:none">
                {{ __('common.view_all') }} <i aria-hidden="true" class="fas fa-arrow-{{ $isRtl ? 'left' : 'right' }}"></i>
            </a>
        </div>
        <div class="scroll-x">
            <div class="scroll-x__track" id="storiesTrack">
                @foreach($stories as $story)
                <article class="story-card scroll-x__item">
                    @if($story->first_image)
                    <div class="story-card__image" style="background-image: url('{{ asset('storage/'.$story->first_image) }}')"></div>
                    @endif
                    <div class="story-card__body">
                        <h3>{{ trans_field($story, 'title') }}</h3>
                        @if(trans_field($story, 'excerpt'))
                        <p style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;line-height:1.5;font-size:.85rem;color:#64748b;margin-bottom:.35rem">{{ trans_field($story, 'excerpt') }}</p>
                        @endif
                        <p>{{ trans_field($story, 'person_name') }}{{ $story->age ? ', '.$story->age.' '.__('common.age') : '' }}</p>
                        @if($story->goal_amount > 0 || ($story->raised_amount ?? 0) > 0)
                        <div class="project-progress" style="margin:8px 0">
                            <div class="progress-bar" style="height:6px;background:#e2e8f0;border-radius:3px;overflow:hidden">
                                <div class="progress-bar__fill" style="width:{{ $story->progressPercent() }}%;height:100%;background:linear-gradient(135deg,var(--color-primary),var(--color-primary-light));border-radius:3px;transition:width 0.5s"></div>
                            </div>
                            <div style="display:flex;justify-content:space-between;font-size:11px;margin-top:3px;color:#64748b">
                                <span><span data-amount="{{ $story->raised_amount ?? 0 }}">${{ number_format($story->raised_amount ?? 0,0) }}</span> {{ __('common.raised') }}</span>
                                <span><span data-amount="{{ $story->goal_amount }}">${{ number_format($story->goal_amount,0) }}</span> {{ __('common.goal') }}</span>
                            </div>
                        </div>
                        @endif
                        <div style="display:flex;gap:8px;margin-top:10px;flex-wrap:wrap">
                            <a href="{{ route('donate.story', ['locale' => $currentLocale, 'id' => $story->id]) }}" class="btn btn--primary btn--sm">{{ __('common.contribute') }}</a>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
            <button class="scroll-x__btn scroll-x__btn--prev" onclick="scrollTrack('storiesTrack',-1)" aria-label="{{ __('common.prev') }}">
                <i aria-hidden="true" class="fas fa-chevron-{{ $isRtl ? 'right' : 'left' }}"></i>
            </button>
            <button class="scroll-x__btn scroll-x__btn--next" onclick="scrollTrack('storiesTrack',1)" aria-label="{{ __('common.next') }}">
                <i aria-hidden="true" class="fas fa-chevron-{{ $isRtl ? 'left' : 'right' }}"></i>
            </button>
        </div>
    </div>
</section>
@endif

{{-- Latest Blog Posts --}}
@if($latestPosts->isNotEmpty())
<section class="section">
    <div class="container">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
            <div>
                <h2 class="section-title" style="margin-bottom:0">{{ __('blog.latest_posts') }}</h2>
                <p style="margin:2px 0 0;font-size:.85rem;color:#64748b">{{ __('home.blog_posts_desc') }}</p>
            </div>
            <a href="{{ route('posts.index', ['locale' => $currentLocale]) }}" style="font-size:.85rem;color:var(--color-primary);font-weight:600;text-decoration:none">
                {{ __('common.view_all') }} <i aria-hidden="true" class="fas fa-arrow-{{ $isRtl ? 'left' : 'right' }}"></i>
            </a>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:20px">
            @foreach($latestPosts as $post)
            <article style="background:var(--color-bg);border-radius:var(--radius-md);overflow:hidden;box-shadow:var(--shadow-sm);border:1px solid var(--color-border);transition:box-shadow 0.2s" onmouseover="this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.boxShadow='var(--shadow-sm)'">
                <a href="{{ route('posts.show', ['locale' => $currentLocale, 'slug' => $post->slug]) }}">
                    @if($post->featured_image)
                    <img src="{{ asset('storage/'.$post->featured_image) }}" alt="{{ trans_field($post, 'title') }}" style="width:100%;height:180px;object-fit:cover;display:block" loading="lazy">
                    @else
                    <div style="width:100%;height:180px;background:linear-gradient(135deg,var(--color-primary),var(--color-primary-light));display:flex;align-items:center;justify-content:center;color:#fff;font-size:2rem"><i aria-hidden="true" class="fas fa-newspaper"></i></div>
                    @endif
                </a>
                <div style="padding:16px">
                    <h3 style="font-size:1rem;font-weight:700;margin-bottom:6px;line-height:1.4">
                        <a href="{{ route('posts.show', ['locale' => $currentLocale, 'slug' => $post->slug]) }}" style="color:var(--color-text);text-decoration:none">{{ trans_field($post, 'title') }}</a>
                    </h3>
                    <div style="display:flex;gap:12px;font-size:0.75rem;color:var(--color-text-muted)">
                        <span>{{ $post->published_at->diffForHumans() }}</span>
                        <span><i aria-hidden="true" class="far fa-clock" style="margin-inline-end:2px"></i>{{ $post->reading_time }} {{ __('blog.minutes_read') }}</span>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Achievement Stats --}}
<section class="stats stats--achievements" id="impact">
    <div class="container">
        <div class="trust-badge--hero"><i aria-hidden="true" class="fas fa-shield-halved"></i> {{ app()->getLocale() === 'ar' ? 'إنجازاتنا بفضل تبرعاتكم' : 'Our Achievements' }} <i aria-hidden="true" class="fas fa-circle-check"></i></div>
        <p style="font-size:1.05rem;color:var(--color-primary);font-weight:600;margin-bottom:0.5rem">{{ app()->getLocale() === 'ar' ? 'أثر تبرعاتكم على الأرض' : 'The Impact of Your Donations on the Ground' }}</p>
        <p style="max-width:600px;margin:0 auto 2rem;color:var(--color-text-muted);font-size:0.95rem">{{ app()->getLocale() === 'ar' ? 'إنجازات ملموسة تحققت بفضل دعمكم وتبرعاتكم السخية' : 'Tangible achievements made possible by your generous support and donations' }}</p>
        <div class="impact-grid">
            @foreach($achievementStats as $stat)
            <div class="impact-card">
                @if($stat->icon)<i aria-hidden="true" class="fas {{ $stat->icon }}" style="font-size:1.8rem;color:var(--color-primary);margin-bottom:0.75rem;display:block"></i>@endif
                <span class="stat-item__number" data-count="{{ $stat->value }}" data-prefix="{{ $stat->prefix ?? '' }}">0</span>
                <span class="stat-item__label">{{ trans_field($stat, 'label') }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Donation Section --}}
{{-- Contact Section --}}
<section class="contact" id="contact">
    <div class="container">
        <div class="volunteer-cta__box">
            <h2>{{ __('volunteer.cta_title') }}</h2>
            <p>{{ __('volunteer.cta_desc') }}</p>
            <div class="volunteer-cta__actions">
                <a href="{{ route('volunteer.register', ['locale' => $currentLocale]) }}" class="btn btn--primary btn--lg">{{ __('volunteer.cta_btn') }}</a>
                <a href="{{ route('volunteer.dashboard', ['locale' => $currentLocale]) }}" class="btn btn--outline btn--lg">{{ __('volunteer.my_dashboard') }}</a>
            </div>
        </div>
        <div class="section-header section-header--center">
            <h2 class="section-title section-title--accent">{{ __('common.contact_us') }}</h2>
        </div>
        <div class="contact__grid {{ $isRtl ? 'contact__grid--rtl' : 'contact__grid--ltr' }}" style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;align-items:start">
            <div style="background:var(--color-bg);border-radius:var(--radius-md);padding:2rem;box-shadow:var(--shadow-sm);border:1px solid var(--color-border)">
                <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem"><i aria-hidden="true" class="fas fa-envelope" style="color:var(--color-primary)"></i> {{ __('common.contact_us') }}</h3>
                <form class="contact-form {{ $isRtl ? 'contact-form--rtl' : 'contact-form--ltr' }}" action="{{ route('contact.store', ['locale' => $currentLocale]) }}" method="POST">
                    @csrf
                    <input type="text" name="hp_website" tabindex="-1" autocomplete="off" style="position:fixed;top:-100px;left:0" aria-hidden="true">
                    <label><span>{{ __('common.full_name') }}</span><input type="text" name="name" autocomplete="name" required></label>
                    <label><span>{{ __('common.email') }}</span><input type="email" name="email" autocomplete="email" required></label>
                    <label><span>{{ __('common.subject') }}</span><input type="text" name="subject" autocomplete="subject" required></label>
                    <label><span>{{ __('common.message') }}</span><textarea name="message" rows="4" required></textarea></label>
                    <button type="submit" class="btn btn--primary">{{ __('common.send_message') }}</button>
                </form>
            </div>
            <div style="display:flex;flex-direction:column;gap:1.5rem">
                <div style="background:var(--color-bg);border-radius:var(--radius-md);padding:2rem;box-shadow:var(--shadow-sm);border:1px solid var(--color-border)">
                    <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem"><i aria-hidden="true" class="fas fa-exclamation-triangle" style="color:var(--color-accent)"></i> {{ app()->getLocale() === 'ar' ? 'تقديم شكوى' : 'Submit a Complaint' }}</h3>
                    <p style="color:var(--color-text-muted);font-size:0.9rem;line-height:1.7;margin-bottom:1rem">{{ app()->getLocale() === 'ar' ? 'نحرص على الاستماع لملاحظاتكم وشكاويكم لتحسين خدماتنا. يمكنكم تقديم شكوى وسيتم الرد عليها في أقرب وقت.' : 'We value your feedback. Submit a complaint and we will respond as soon as possible.' }}</p>
                    <a href="{{ route('complaints.create', ['locale' => $currentLocale]) }}" class="btn btn--primary" style="display:inline-flex;align-items:center;gap:0.5rem">
                        <i aria-hidden="true" class="fas fa-pen"></i> {{ app()->getLocale() === 'ar' ? 'تقديم شكوى' : 'Submit Complaint' }}
                    </a>
                    @if($s->whatsapp || $s->email || $s->phone)
                    <div style="margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid var(--color-border)">
                        <h4 style="font-size:0.9rem;font-weight:600;margin-bottom:0.75rem;color:var(--color-text-muted)">{{ app()->getLocale() === 'ar' ? 'معلومات التواصل' : 'Contact Info' }}</h4>
                        @if($s->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $s->whatsapp) }}" style="display:flex;align-items:center;gap:0.5rem;padding:0.5rem 0;color:var(--color-text);text-decoration:none;font-size:0.85rem" target="_blank">
                            <i aria-hidden="true" class="fab fa-whatsapp" style="color:#25D366;width:20px;text-align:center"></i> {{ $s->whatsapp }}
                        </a>
                        @endif
                        @if($s->email)
                        <a href="mailto:{{ $s->email }}" style="display:flex;align-items:center;gap:0.5rem;padding:0.5rem 0;color:var(--color-text);text-decoration:none;font-size:0.85rem">
                            <i aria-hidden="true" class="fas fa-envelope" style="color:var(--color-primary);width:20px;text-align:center"></i> {{ $s->email }}
                        </a>
                        @endif
                        @if($s->phone)
                        <a href="tel:{{ preg_replace('/\s+/', '', $s->phone) }}" style="display:flex;align-items:center;gap:0.5rem;padding:0.5rem 0;color:var(--color-text);text-decoration:none;font-size:0.85rem">
                            <i aria-hidden="true" class="fas fa-phone" style="color:var(--color-primary);width:20px;text-align:center"></i> {{ $s->phone }}
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
                <div style="background:var(--color-bg);border-radius:var(--radius-md);padding:1.5rem;box-shadow:0 0 20px rgba(34,139,34,0.25),var(--shadow-sm);border:1px solid rgba(34,139,34,0.3)">
                    <h4 style="font-size:0.9rem;font-weight:600;margin-bottom:0.75rem;color:var(--color-text-muted);display:flex;align-items:center;gap:0.5rem"><i aria-hidden="true" class="fas fa-shield-alt" style="color:var(--color-primary)"></i> {{ app()->getLocale() === 'ar' ? 'الشفافية والتوثيق' : 'Transparency & Trust' }}</h4>
                    <p style="font-size:0.78rem;line-height:1.7;color:var(--color-text-muted);margin-bottom:1rem">تخضع منظمة ساهم الدولية للاغاثة والتنمية لقوانين الاتحاد الاوروبي وتمتثل بعملها وفق اعلى معايير الحوكمة والشفافية المالية والإدارية. ونلتزم بأمن المعلومات وبقواعد أمان صارمة ونزاهة مؤسسية تضمن إيصال المساعدات لمستحقيها بكل مسؤولية وموثوقية قانونية</p>
                    <div class="contact-trust-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                        <div style="display:flex;flex-direction:column;align-items:center;gap:4px;background:rgba(255,255,255,0.5);border:1px solid var(--color-border);border-radius:12px;padding:14px 10px;text-align:center">
                            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--color-primary),#1a6b3c);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.85rem;flex-shrink:0;margin-bottom:2px"><i aria-hidden="true" class="fas fa-lock"></i></div>
                            <strong style="font-size:0.8rem;line-height:1.3">SSL Secured</strong>
                            <span style="font-size:0.65rem;color:var(--color-text-muted);line-height:1.2">مشفّر 256-bit</span>
                        </div>
                        <div style="display:flex;flex-direction:column;align-items:center;gap:4px;background:rgba(255,255,255,0.5);border:1px solid var(--color-border);border-radius:12px;padding:14px 10px;text-align:center">
                            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--color-primary),#1a6b3c);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.85rem;flex-shrink:0;margin-bottom:2px"><i aria-hidden="true" class="fas fa-credit-card"></i></div>
                            <strong style="font-size:0.8rem;line-height:1.3">متوافق مع PCI</strong>
                            <span style="font-size:0.65rem;color:var(--color-text-muted);line-height:1.2">أمان المدفوعات</span>
                        </div>
                        <div style="display:flex;flex-direction:column;align-items:center;gap:4px;background:rgba(255,255,255,0.5);border:1px solid var(--color-border);border-radius:12px;padding:14px 10px;text-align:center">
                            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--color-primary),#1a6b3c);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.85rem;flex-shrink:0;margin-bottom:2px"><i aria-hidden="true" class="fas fa-certificate"></i></div>
                            <strong style="font-size:0.8rem;line-height:1.3">مرخصة رسمياً</strong>
                            <span style="font-size:0.65rem;color:var(--color-text-muted);line-height:1.2">منظمة مسجلة</span>
                        </div>
                        <div style="display:flex;flex-direction:column;align-items:center;gap:4px;background:rgba(255,255,255,0.5);border:1px solid var(--color-border);border-radius:12px;padding:14px 10px;text-align:center">
                            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--color-primary),#1a6b3c);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.85rem;flex-shrink:0;margin-bottom:2px"><i aria-hidden="true" class="fas fa-hand-holding-heart"></i></div>
                            <strong style="font-size:0.8rem;line-height:1.3">جهة خيرية رسمية</strong>
                            <span style="font-size:0.65rem;color:var(--color-text-muted);line-height:1.2">معتمد وموثق</span>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
   
    </div>
</section>

@endsection

@push('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
.contact__grid--rtl { flex-direction: row-reverse; }
.contact__grid--ltr { flex-direction: row; }
@media (max-width:640px) { .contact-trust-grid { grid-template-columns:1fr !important; } }
.project-card__actions { display: flex; gap: 8px; margin-top: 12px; }
.project-card__actions .btn--primary { order: 0; }
.project-card__actions .btn--outline { order: 1; }
.project-progress { margin: 10px 0; }
.project-progress .progress-bar { height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden; }
.project-progress .progress-bar__fill { height: 100%; background: linear-gradient(135deg, var(--color-primary), var(--color-primary-light)); border-radius: 4px; transition: width 0.5s; }
.project-progress .progress-stats { display: flex; justify-content: space-between; font-size: 12px; margin-top: 4px; color: #64748b; }
.project-progress .progress-stats strong { color: #1e293b; }
.donation-item__campaign { font-size: 11px; color: #64748b; }
/* === Hero Slider (WFP-inspired) === */
.hero-slider { position: relative; overflow: hidden; }

.hero-slide { position: relative; display: flex; align-items: center; justify-content: center; min-height: 80vh; background: linear-gradient(135deg, #0d6b4f 0%, #083b2b 100%); background-image: var(--slide-bg); background-size: cover; background-position: center; }

.hero-slide__overlay { position: absolute; inset: 0; background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.65) 100%); z-index: 1; }

.hero-slide__content { position: relative; z-index: 2; text-align: center; color: #fff; max-width: 820px; padding: 30px 40px; border-radius: 16px; }

.hero-slide__title { font-size: clamp(1.8rem, 4.5vw, 3.5rem); font-weight: 900; margin-bottom: 12px; line-height: 1.2; text-shadow: 0 4px 30px rgba(0,0,0,0.5), 0 1px 3px rgba(0,0,0,0.3); letter-spacing: -0.02em; }

.hero-slide__title::after { content: ''; display: block; width: 60px; height: 4px; border-radius: 2px; margin: 16px auto 0; background: currentColor; opacity: .5; }

.hero-slide__subtitle { font-size: clamp(1rem, 1.5vw, 1.2rem); opacity: 0.92; margin-bottom: 24px; line-height: 1.7; text-shadow: 0 2px 20px rgba(0,0,0,0.4); max-width: 580px; margin-inline: auto; }

.hero-slide__action { margin-top: .5rem; }

/* Button */
.hero-slide__action .btn--primary { border-radius: 50px; padding: 14px 40px; font-size: 1.05rem; font-weight: 700; box-shadow: 0 4px 20px rgba(0,0,0,0.2); transition: transform .2s, box-shadow .2s; }
.hero-slide__action .btn--primary:hover { transform: translateY(-2px); box-shadow: 0 6px 28px rgba(0,0,0,0.3); }

/* Navigation Arrows */
.hero-slider .swiper-button-next,
.hero-slider .swiper-button-prev {
    width: 48px; height: 48px; margin-top: -24px;
    background: rgba(255,255,255,0.15); border-radius: 50%;
    color: #fff; font-size: 18px; border: 2px solid rgba(255,255,255,0.25);
    transition: background 0.2s, border-color 0.2s, transform 0.2s;
    backdrop-filter: blur(4px);
}
.hero-slider .swiper-button-next:hover,
.hero-slider .swiper-button-prev:hover { background: rgba(255,255,255,0.3); border-color: rgba(255,255,255,0.5); transform: scale(1.1); }
.hero-slider .swiper-button-next { inset-inline-end: 24px; }
.hero-slider .swiper-button-prev { inset-inline-start: 24px; }
.hero-slider .swiper-button-next::after { content: '\276F'; font-family: inherit; font-size: 20px; }
.hero-slider .swiper-button-prev::after { content: '\276E'; font-family: inherit; font-size: 20px; }

/* Pagination Dots */
.hero-slider .swiper-pagination { bottom: 28px !important; z-index: 3; }
.hero-slider .swiper-pagination-bullet {
    width: 12px; height: 12px; background: rgba(255,255,255,0.4);
    opacity: 1; border-radius: 50%; margin: 0 6px !important;
    transition: all 0.3s; cursor: pointer; border: 2px solid rgba(255,255,255,0.5);
}
.hero-slider .swiper-pagination-bullet-active { background: #fff; transform: scale(1.2); border-color: #fff; }

/* Slide animations */
.hero-slide__title, .hero-slide__subtitle, .hero-slide__action {
    opacity: 0; transform: translateY(24px); transition: all 0.6s ease;
}
.swiper-slide-active .hero-slide__title,
.swiper-slide-active .hero-slide__subtitle,
.swiper-slide-active .hero-slide__action {
    opacity: 1; transform: translateY(0);
}
.swiper-slide-active .hero-slide__subtitle { transition-delay: 0.15s; }
.swiper-slide-active .hero-slide__action { transition-delay: 0.3s; }

/* Responsive */
@media (max-width: 768px) {
    .hero-slide { min-height: 60vh; }
    .hero-slide__content { padding: 20px; }
    .hero-slide__title { font-size: clamp(1.5rem, 5vw, 2.2rem); }
    .hero-slide__title::after { width: 40px; height: 3px; margin-top: 12px; }
    .hero-slide__subtitle { margin-bottom: 20px; font-size: clamp(0.9rem, 2vw, 1rem); }
    .hero-slider .swiper-button-next,
    .hero-slider .swiper-button-prev { width: 36px; height: 36px; margin-top: -18px; font-size: 14px; border-width: 1.5px; }
    .hero-slider .swiper-button-next { inset-inline-end: 10px; }
    .hero-slider .swiper-button-prev { inset-inline-start: 10px; }
    .hero-slider .swiper-pagination { bottom: 14px !important; }
    .hero-slider .swiper-pagination-bullet { width: 8px; height: 8px; border-width: 1.5px; }
}
@media (max-width: 480px) {
    .hero-slide { min-height: 50vh; }
    .hero-slide__content { padding: 16px; }
    .hero-slide__title { margin-bottom: 8px; font-size: clamp(1.3rem, 6vw, 1.6rem); }
    .hero-slide__title::after { width: 30px; height: 2px; margin-top: 10px; }
    .hero-slide__subtitle { margin-bottom: 16px; }
    .hero-slide__action .btn--primary { padding: 10px 24px; font-size: .9rem; }
}

.volunteer-cta__box { text-align: center; background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark)); border-radius: 16px; padding: 3rem 2rem; color: #fff; display: flex; flex-direction: column; align-items: center; gap: 1rem; margin-bottom: 1.5rem; }
.volunteer-cta__box h2 { color: #fff; font-size: 1.75rem; }
.volunteer-cta__box p { color: rgba(255,255,255,0.85); font-size: 1rem; }
.volunteer-cta__actions { display: flex; gap: 1rem; flex-wrap: wrap; justify-content: center; }
.volunteer-cta__actions .btn--outline { background: transparent; border: 2px solid rgba(255,255,255,0.5); color: #fff; }
.volunteer-cta__actions .btn--outline:hover { background: rgba(255,255,255,0.1); border-color: #fff; }


/* Scroll-x */
.scroll-x { position:relative; }
.scroll-x__track { display:flex; gap:1.5rem; overflow-x:auto; scroll-snap-type:x mandatory; -webkit-overflow-scrolling:touch; scrollbar-width:none; padding:0.5rem 0; }
.scroll-x__track::-webkit-scrollbar { display:none; }
.scroll-x__item { flex:0 0 280px; scroll-snap-align:start; box-sizing:border-box; min-width:0; }
.scroll-x__btn { position:absolute; top:50%; transform:translateY(-50%); width:40px; height:40px; border:1px solid var(--color-border); background:#fff; border-radius:50%; cursor:pointer; z-index:2; display:flex; align-items:center; justify-content:center; transition:all 0.2s; color:var(--color-text); font-size:0.9rem; box-shadow:0 2px 8px rgba(0,0,0,0.08); }
.scroll-x__btn:hover { background:var(--color-primary); color:#fff; border-color:var(--color-primary); }
.scroll-x__btn--prev { inset-inline-start:-20px; }
.scroll-x__btn--next { inset-inline-end:-20px; }
@media (max-width:768px) {
    .scroll-x__btn { display:none; }
    .scroll-x__item { flex:0 0 240px; }
}

/* Section Title Accent */
.section-title--accent { position:relative; display:inline-block; padding-bottom:10px; }
.section-title--accent::after { content:''; position:absolute; bottom:0; left:50%; transform:translateX(-50%); width:60px; height:3px; background:var(--color-primary); border-radius:2px; }
.stats--achievements { text-align:center; }
.trust-badge--hero { display:inline-flex; align-items:center; gap:12px; background:var(--color-primary); color:#fff; padding:12px 28px; border-radius:50px; font-size:1.2rem; font-weight:700; box-shadow:0 4px 16px rgba(22,163,74,0.3); margin-bottom:1rem; }
.trust-badge--hero i { font-size:1.1rem; }
.stats__title { font-size:1.3rem; font-weight:800; color:var(--color-heading); margin-bottom:1.5rem; position:relative; padding-bottom:10px; display:inline-block; }
.stats__title::after { content:''; position:absolute; bottom:0; left:50%; transform:translateX(-50%); width:60px; height:3px; background:var(--color-primary); border-radius:2px; }
.impact-grid { display:grid; grid-template-columns:repeat(6,1fr); gap:20px; max-width:1100px; margin:0 auto; }
.impact-card { background:#fff; padding:28px 16px; border-radius:12px; border:1px solid var(--color-border); box-shadow:0 2px 8px rgba(0,0,0,0.04); transition:all 0.3s ease; }
.impact-card:hover { transform:translateY(-6px); box-shadow:0 12px 32px rgba(0,0,0,0.1); }
.impact-card .stat-item__number { display:block; font-size:2rem; font-weight:800; color:var(--color-heading); line-height:1.2; }
.impact-card .stat-item__label { display:block; font-size:0.85rem; color:var(--color-text-muted); margin-top:4px; }
@media (max-width:768px) { .impact-grid { grid-template-columns:repeat(2,1fr); gap:12px; } .impact-card { padding:20px 12px; } }

/* Trust Section */
.trust-section { padding:1.5rem 0; background:var(--color-bg-alt); text-align:center; }
.trust-section__title { font-size:1rem; font-weight:700; color:var(--color-heading); margin-bottom:1rem; display:inline-block; position:relative; padding-bottom:8px; }
.trust-section__title::after { content:''; position:absolute; bottom:0; left:50%; transform:translateX(-50%); width:50px; height:3px; background:var(--color-primary); border-radius:2px; }
.trust-section__badges { display:flex; gap:8px; justify-content:center; }
.trust-badge { display:flex; align-items:center; gap:6px; background:#fff; border:1px solid var(--color-border); border-radius:8px; padding:6px 10px; position:relative; white-space:nowrap; }
.trust-badge__icon { width:24px; height:24px; border-radius:50%; background:var(--color-primary); color:#fff; display:flex; align-items:center; justify-content:center; font-size:0.65rem; flex-shrink:0; }
.trust-badge__icon--gold { background:#d4a017; }
.trust-badge__body { display:flex; align-items:baseline; gap:4px; }
.trust-badge__body strong { font-size:0.72rem; color:var(--color-heading); }
.trust-badge__body span { font-size:0.6rem; color:var(--color-text-muted); }
.trust-badge__check { color:var(--color-primary); font-size:0.7rem; }
@media (max-width:600px) {
    .trust-section__badges { display:grid; grid-template-columns:1fr 1fr; gap:6px; }
    .trust-badge { padding:8px; gap:6px; white-space:normal; }
    .trust-badge__icon { width:20px; height:20px; font-size:0.55rem; }
    .trust-badge__body strong { font-size:0.65rem; }
    .trust-badge__body span { font-size:0.55rem; }
    .stats__grid { grid-template-columns:1fr 1fr !important; gap:12px; }
    .stat-item { padding:16px; }
    .stat-item__number { font-size:1.5rem; }
}

/* Home Trust Section */
.home-trust-icon { width:50px; height:50px; margin:0 auto 0.75rem; border-radius:50%; background:linear-gradient(135deg,#f59e0b,#d97706); display:flex; align-items:center; justify-content:center; font-size:1.4rem; color:#fff; box-shadow:0 0 30px rgba(245,158,11,0.25),0 6px 18px rgba(245,158,11,0.2); position:relative; animation:homeTrustFloat 4s ease-in-out infinite; }
.home-trust-icon::after { content:''; position:absolute; inset:-6px; border-radius:50%; border:2px solid rgba(245,158,11,0.3); animation:homeTrustPulse 3s ease-in-out infinite; }
.home-trust-text { font-size:1rem; line-height:1.8; color:#94a3b8; margin:1rem auto 0; max-width:700px; font-weight:400; }
@keyframes homeTrustFloat { 0%,100% { transform:translateY(0); } 50% { transform:translateY(-8px); } }
@keyframes homeTrustPulse { 0%,100% { transform:scale(1); opacity:0.4; } 50% { transform:scale(1.15); opacity:0.1; } }

/* Map Animations */
@keyframes radarPulse { 0% { transform:scale(0.9); opacity:0.8; } 100% { transform:scale(2.4); opacity:0; } }
.radar-pulse-ring { transform-origin:center; transform-box:fill-box; will-change:transform,opacity; animation:radarPulse 3s cubic-bezier(0.215,0.610,0.355,1) infinite; }
@keyframes routeDash { to { stroke-dashoffset: -40; } }
.route-line-animate { stroke-dasharray: 6 8; animation: routeDash 1.5s linear infinite; }

/* Hero Map */
.hero__map { display:flex; justify-content:center; align-items:center; width:100%; }
.hero__map svg { width:100%; max-width:550px; aspect-ratio:1.1; }
@media (max-width:768px) {
    .hero__map svg { max-width:100% !important; height:auto !important; }
    .hero__map + div { margin-top:1rem; }
    .map-text-grid { grid-template-columns:1fr !important; }
}

/* Fix logo size */
.logo__img { height: 80px; }

@media (max-width: 768px) {
    .logo__img { height: 60px; }
}

@media (max-width: 640px) {
    .contact__grid { grid-template-columns:1fr !important; }
}

/* Ensure slider text is vertically centered */
.heroSwiper .swiper-slide { display: flex; align-items: center; justify-content: center; }

/* Fix icons visibility for scroll-to-top and dark mode */
.scroll-top, .dark-mode-toggle { display: flex; }
.scroll-top { display: none; }
.scroll-top.visible { display: flex; }
.scroll-top i, .dark-mode-toggle i { display: inline-block; }

/* Force swiper wrapper to 0 in fade (prevents RTL translate drift on mobile) */
.heroSwiper .swiper-wrapper { transform: translate3d(0,0,0) !important; }

/* Hero Map Section */
.hm-section { background:linear-gradient(135deg,#0b1a2e,#13294b,#0b1a2e); color:#e2e8f0; }
.hm-header { text-align:center; margin-bottom:1.5rem; }
.hm-title { font-size:1.5rem; font-weight:800; margin:0 0 0.25rem; color:#f1f5f9; }
.hm-subtitle { opacity:0.7; font-size:0.95rem; margin:0; }
.hm-map-wrap { max-width:520px; margin:0 auto; border-radius:16px; overflow:hidden; box-shadow:0 8px 32px rgba(0,0,0,0.3); }
.hm-map-wrap svg { display:block; width:100%; height:auto; }
.hm-map-wrap svg path { transition:all 0.25s ease; cursor:pointer; }
.hm-map-wrap svg path:hover { fill:rgba(245,158,11,0.35) !important; stroke:#f59e0b !important; stroke-width:1.5 !important; }

/* Trust Card */
.hm-section--light { background:#f8fafc !important; color:#1e293b; }
.hm-trust-card { background:#fff; border:1px solid #e2e8f0; border-radius:20px; padding:2.5rem 2rem; text-align:center; max-width:860px; margin:0 auto; box-shadow: 0 16px 64px rgba(13, 107, 79, 0.55), 0 0 80px rgba(13, 107, 79, 0.15);; }
.hm-trust-icon-wrap { width:72px; height:72px; margin:0 auto 1.25rem; display:flex; align-items:center; justify-content:center; border-radius:50%; background:linear-gradient(135deg,#0d6b4f,#0a9c6e); font-size:2rem; color:#fff; box-shadow:0 4px 20px rgba(13,107,79,0.4); }
.hm-trust-text { font-size:1.1rem; font-weight:500; line-height:1.8; margin:0; color:#475569; max-width:720px; margin-inline:auto; }

</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script nonce="{{ $cspNonce }}">
function scrollTrack(id, dir) {
    var t = document.getElementById(id);
    if (!t) return;
    var step = t.clientWidth;
    var limit = t.scrollWidth - t.clientWidth;
    if (limit <= 0) return;
    var isRtl = window.getComputedStyle(t).direction === 'rtl';
    var pos = Math.abs(t.scrollLeft);
    if (dir > 0 && pos >= limit - 2) t.scrollTo({ left: 0, behavior: 'smooth' });
    else if (dir < 0 && pos <= 2) t.scrollTo({ left: limit, behavior: 'smooth' });
    else t.scrollBy({ left: (isRtl ? -dir : dir) * step, behavior: 'smooth' });
}
</script>
<script nonce="{{ $cspNonce }}">
document.addEventListener('DOMContentLoaded', function () {
    const swiperEl = document.querySelector('.heroSwiper');
    if (!swiperEl) return;

    const slideCount = document.querySelectorAll('.heroSwiper .swiper-slide').length;
    const heroSwiper = new Swiper('.heroSwiper', {
        loop: slideCount > 1,
        speed: 800,
        rtl: {{ $isRtl ? 'true' : 'false' }},
        autoplay: { delay: 5000, disableOnInteraction: false, pauseOnMouseEnter: true },
        pagination: { el: '.swiper-pagination', clickable: true },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        effect: 'fade',
        fadeEffect: { crossFade: true },
        keyboard: { enabled: true },
    });

    // Pause on hover
    swiperEl.addEventListener('mouseenter', () => heroSwiper.autoplay.stop());
    swiperEl.addEventListener('mouseleave', () => heroSwiper.autoplay.start());
});
</script>
@endpush
