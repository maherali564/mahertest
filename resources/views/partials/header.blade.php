@php $s = $settings ?? \App\Models\SiteSetting::current(); @endphp
<header class="header" id="header">
    <div class="container header__inner">
        <a href="{{ route('home', ['locale' => $currentLocale]) }}" class="logo">
            @php
                $logoSrc = null;
                if ($s->logos && is_array($s->logos) && isset($s->logos[$currentLocale])) {
                    $logoSrc = $s->logos[$currentLocale];
                } elseif ($s->logo) {
                    $logoSrc = $s->logo;
                }
            @endphp
            @if($logoSrc)
                <img loading="lazy" src="{{ asset('storage/'.$logoSrc) }}" alt="" class="logo__img">
            @else
                <span class="logo__icon" aria-hidden="true">🤝</span>
            @endif
            <span class="logo__text">
                <strong>{{ trans_field($s, 'site_name') }}</strong>
                <small>{{ trans_field($s, 'tagline') }}</small>
            </span>
        </a>
        <button class="nav-toggle" type="button" aria-label="Menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
        <nav class="nav" id="nav">
            <ul class="nav__list">
                <li><a href="{{ route('home', ['locale' => $currentLocale]) }}#home" class="nav__link">{{ __('common.nav_home') }}</a></li>
                <li><a href="{{ route('about.index', ['locale' => $currentLocale]) }}" class="nav__link">{{ __('common.nav_about') }}</a></li>
                <li><a href="{{ route('projects.index', ['locale' => $currentLocale]) }}" class="nav__link">{{ __('common.nav_projects') }}</a></li>
                <li class="nav__item nav__item--dropdown">
                    <a href="#" class="nav__link">{{ __('common.nav_programs') }} <i aria-hidden="true" class="fas fa-chevron-down nav__arrow"></i></a>
                    @php $navPrograms = \App\Models\Program::with('projects')->active()->get(); @endphp
                    @if($navPrograms->isNotEmpty())
                    <ul class="nav__dropdown">
                        @foreach($navPrograms as $navProgram)
                        <li class="nav__dropdown-item">
                            <a href="{{ route('projects.index', ['locale' => $currentLocale, 'program' => $navProgram->id]) }}" class="nav__dropdown-link">
                                @if($navProgram->icon)<i class="{{ $navProgram->icon }}" style="margin-inline-end:6px;color:var(--color-primary)"></i>@endif
                                {{ trans_field($navProgram, 'title') }}
                            </a>
                            @php $programProjects = $navProgram->projects->where('is_active', true)->sortBy('sort_order'); @endphp
                            @if($programProjects->isNotEmpty())
                            <ul class="nav__subdropdown">
                                @foreach($programProjects as $pp)
                                <li><a href="{{ route('projects.show', ['locale' => $currentLocale, 'slug' => $pp->slug]) }}" class="nav__subdropdown-link">{{ trans_field($pp, 'title') }}</a></li>
                                @endforeach
                            </ul>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </li>
                <li><a href="{{ route('stories.index', ['locale' => $currentLocale]) }}" class="nav__link">{{ __('common.nav_stories') }}</a></li>
                <li><a href="{{ route('volunteer.register', ['locale' => $currentLocale]) }}" class="nav__link">{{ __('volunteer.nav') }}</a></li>
                <li><a href="{{ route('home', ['locale' => $currentLocale]) }}#contact" class="nav__link">{{ __('common.nav_contact') }}</a></li>
                <li><a href="{{ route('transparency.index', ['locale' => $currentLocale]) }}" class="nav__link">{{ __('common.transparency') }}</a></li>
            </ul>
            <a href="{{ route('donate.page', ['locale' => $currentLocale]) }}" class="btn btn--primary btn--sm nav__cta" style="white-space:nowrap">{{ __('common.donate_now') }} <i aria-hidden="true" class="fas fa-heart" style="margin-inline-start:6px"></i></a>
        </nav>
    </div>
</header>
