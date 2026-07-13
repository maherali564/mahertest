<div class="top-bar">
    @php $s = $settings; @endphp
    <div class="container top-bar__inner">
        <div class="top-bar__contact">
            @if($s->phone)<a href="tel:{{ preg_replace('/\s+/', '', $s->phone) }}"><i aria-hidden="true" class="fas fa-phone"></i> {{ $s->phone }}</a>@endif
            @if($s->email)<a href="mailto:{{ $s->email }}"><i aria-hidden="true" class="fas fa-envelope"></i> {{ $s->email }}</a>@endif
        </div>
       
        <div class="top-bar__langs">
            <div class="lang-dropdown">
                <button class="lang-dropdown__btn" type="button" aria-haspopup="true" aria-expanded="false">
                    <i aria-hidden="true" class="fas fa-globe lang-dropdown__globe"></i>
                    <span class="lang-name">{{ $localeLabels[$currentLocale] ?? $currentLocale }}</span>
                    <i aria-hidden="true" class="fas fa-chevron-down lang-dropdown__arrow"></i>
                </button>
                <ul class="lang-dropdown__menu">
                    @foreach($supportedLocales as $loc)
                        <li>
                            <a href="{{ locale_url($loc) }}" class="lang-dropdown__link {{ $loc === $currentLocale ? 'active' : '' }}" title="{{ $localeLabels[$loc] ?? $loc }}">
                                <span class="lang-name">{{ $localeLabels[$loc] ?? $loc }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
