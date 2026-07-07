<!DOCTYPE html>
<html lang="{{ $currentLocale }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', trans_field($settings ?? null, 'site_name', $currentLocale) ?? config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', trans_field($settings ?? null, 'tagline', $currentLocale) ?? '')">
    <meta property="og:title" content="@yield('title', trans_field($settings ?? null, 'site_name', $currentLocale) ?? config('app.name'))">
    <meta property="og:description" content="@yield('meta_description', trans_field($settings ?? null, 'tagline', $currentLocale) ?? '')">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('storage/' . (($settings->logos[$currentLocale] ?? $settings->logo ?? ''))))">
    <meta property="og:locale" content="{{ $currentLocale === 'ar' ? 'ar_AR' : ($currentLocale === 'sv' ? 'sv_SE' : $currentLocale . '_' . strtoupper($currentLocale)) }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', trans_field($settings ?? null, 'site_name', $currentLocale) ?? config('app.name'))">
    <meta name="twitter:description" content="@yield('meta_description', trans_field($settings ?? null, 'tagline', $currentLocale) ?? '')">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="icon" type="image/png" href="{{ ($settings->logo ?? false) ? asset('storage/'.$settings->logo) : asset('favicon.ico') }}">
    <script type="application/ld+json" nonce="{{ $cspNonce }}">
    {
        "@context": "https://schema.org",
        "@type": "NGO",
        "name": "{{ trans_field($settings ?? null, 'site_name', $currentLocale) ?? config('app.name') }}",
        "description": "{{ trans_field($settings ?? null, 'tagline', $currentLocale) ?? '' }}",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('storage/' . (($settings->logos[$currentLocale] ?? $settings->logo ?? ''))) }}",
        "foundingDate": "2024",
        "address": {
            "@type": "PostalAddress",
            "addressCountry": "{{ $currentLocale === 'sv' ? 'SE' : 'SA' }}"
        }
    }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/extra.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @livewireStyles
    @stack('head')
</head>
<body class="{{ $isRtl ? 'rtl' : 'ltr' }}">

    @include('partials.top-bar')
    @include('partials.header')

    @if(session('success'))
        <div class="alert alert--success container">{{ session('success') }}</div>
    @endif

    <main>
        @yield('content')
    </main>

    @include('partials.footer')


    @livewireScripts
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" nonce="{{ $cspNonce }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/video-lightbox.js') }}"></script>
    @stack('scripts')

    {{-- Cookie Consent Banner --}}
    <div id="cookieBanner" style="position:fixed;bottom:0;left:0;right:0;z-index:9999;transform:translateY(100%);transition:transform 0.5s cubic-bezier(0.4,0,0.2,1);direction:{{ $isRtl ? 'rtl' : 'ltr' }}">
        <div style="background:#fff;border-top:1px solid #e2e8f0;box-shadow:0 -8px 40px rgba(0,0,0,0.12);padding:20px 24px">
            <div style="max-width:1100px;margin:0 auto;display:flex;flex-wrap:wrap;gap:16px;align-items:center;justify-content:space-between">
                <div style="display:flex;align-items:center;gap:14px;flex:1;min-width:250px">
                    <div style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,var(--color-primary),var(--color-primary-light));display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i aria-hidden="true" class="fas fa-cookie-bite" style="color:#fff;font-size:1.2rem"></i>
                    </div>
                    <div>
                        <h4 style="font-weight:700;font-size:0.95rem;margin-bottom:2px;color:#1e293b">{{ __('common.cookie_title') }}</h4>
                        <p style="margin:0;font-size:0.82rem;color:#64748b;line-height:1.5">{{ __('common.cookie_desc') }}</p>
                    </div>
                </div>
                <div style="display:flex;gap:8px;flex-shrink:0;flex-wrap:wrap">
                    <button onclick="cookieAcceptAll()" style="padding:10px 24px;border:none;border-radius:8px;background:linear-gradient(135deg,var(--color-primary),var(--color-primary-dark));color:#fff;font-size:0.85rem;font-weight:600;cursor:pointer;transition:all 0.2s;box-shadow:0 2px 8px rgba(13,107,79,0.3)" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
                        <i aria-hidden="true" class="fas fa-check" style="margin-inline-end:6px"></i>{{ __('common.cookie_accept_all') }}
                    </button>
                    <button onclick="cookieRejectAll()" style="padding:10px 24px;border:2px solid #e2e8f0;border-radius:8px;background:#fff;color:#64748b;font-size:0.85rem;font-weight:600;cursor:pointer;transition:all 0.2s" onmouseover="this.style.borderColor='#dc2626';this.style.color='#dc2626'" onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#64748b'">
                        <i aria-hidden="true" class="fas fa-times" style="margin-inline-end:6px"></i>{{ __('common.cookie_reject_all') }}
                    </button>
                    <button onclick="openCookieSettings()" style="padding:10px 20px;border:2px solid #e2e8f0;border-radius:8px;background:#fff;color:#1e293b;font-size:0.85rem;font-weight:600;cursor:pointer;transition:all 0.2s" onmouseover="this.style.borderColor='#0d6b4f';this.style.color='#0d6b4f'" onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#1e293b'">
                        <i aria-hidden="true" class="fas fa-sliders-h" style="margin-inline-end:6px"></i>{{ __('common.cookie_customize') }}
                    </button>
                    <a href="{{ route('pages.privacy', ['locale' => $currentLocale]) }}#cookies" style="padding:10px 16px;font-size:0.82rem;color:var(--color-primary);text-decoration:none;display:flex;align-items:center;font-weight:500">
                        <i aria-hidden="true" class="fas fa-info-circle" style="margin-inline-end:4px"></i>{{ __('common.cookie_more') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Cookie Settings Modal --}}
    <div id="cookieModal" style="position:fixed;inset:0;z-index:10000;display:none;align-items:center;justify-content:center;padding:20px">
        <div onclick="closeCookieSettings()" style="position:absolute;inset:0;background:rgba(0,0,0,0.5);backdrop-filter:blur(4px)"></div>
        <div style="position:relative;background:#fff;border-radius:20px;max-width:560px;width:100%;max-height:85vh;overflow-y:auto;box-shadow:0 24px 64px rgba(0,0,0,0.2);direction:{{ $isRtl ? 'rtl' : 'ltr' }}">
            {{-- Modal Header --}}
            <div style="padding:24px 24px 0;display:flex;align-items:center;justify-content:space-between">
                <div>
                    <h2 style="font-size:1.25rem;font-weight:800;color:#1e293b;margin-bottom:4px"><i aria-hidden="true" class="fas fa-cookie-bite" style="color:var(--color-primary);margin-inline-end:8px"></i>{{ __('common.cookie_settings') }}</h2>
                    <p style="font-size:0.82rem;color:#64748b;margin:0">{{ __('common.cookie_desc') }}</p>
                </div>
                <button onclick="closeCookieSettings()" aria-label="{{ __('common.close') }}" style="width:36px;height:36px;border-radius:50%;border:none;background:#f1f5f9;color:#64748b;font-size:1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all 0.2s" onmouseover="this.style.background='#fee2e2';this.style.color='#dc2626'" onmouseout="this.style.background='#f1f5f9';this.style.color='#64748b'">
                    <i aria-hidden="true" class="fas fa-times"></i>
                </button>
            </div>

            {{-- Modal Body --}}
            <div style="padding:20px 24px;display:flex;flex-direction:column;gap:12px">
                {{-- Necessary --}}
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:16px">
                    <div style="display:flex;align-items:center;justify-content:space-between">
                        <div style="display:flex;align-items:center;gap:12px">
                            <div style="width:40px;height:40px;border-radius:10px;background:#dcfce7;display:flex;align-items:center;justify-content:center">
                                <i aria-hidden="true" class="fas fa-shield-alt" style="color:var(--color-primary);font-size:1rem"></i>
                            </div>
                            <div>
                                <h4 style="font-weight:700;font-size:0.9rem;color:#1e293b;margin-bottom:2px">{{ __('common.cookie_necessary') }}</h4>
                                <p style="font-size:0.78rem;color:#64748b;margin:0">{{ __('common.cookie_necessary_desc') }}</p>
                            </div>
                        </div>
                        <div style="position:relative;width:48px;height:26px;background:var(--color-primary);border-radius:13px;cursor:not-allowed;opacity:0.7">
                            <div style="position:absolute;top:3px;inset-inline-start:calc(100% - 23px);width:20px;height:20px;background:#fff;border-radius:50%;box-shadow:0 1px 4px rgba(0,0,0,0.15)"></div>
                        </div>
                    </div>
                </div>

                {{-- Analytics --}}
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px;transition:border-color 0.2s" id="cookieAnalyticsCard">
                    <div style="display:flex;align-items:center;justify-content:space-between">
                        <div style="display:flex;align-items:center;gap:12px">
                            <div style="width:40px;height:40px;border-radius:10px;background:#eff6ff;display:flex;align-items:center;justify-content:center">
                                <i aria-hidden="true" class="fas fa-chart-bar" style="color:var(--color-primary);font-size:1rem"></i>
                            </div>
                            <div>
                                <h4 style="font-weight:700;font-size:0.9rem;color:#1e293b;margin-bottom:2px">{{ __('common.cookie_analytics') }}</h4>
                                <p style="font-size:0.78rem;color:#64748b;margin:0">{{ __('common.cookie_analytics_desc') }}</p>
                            </div>
                        </div>
                        <button onclick="toggleCookie('analytics')" id="cookieAnalyticsToggle" aria-label="{{ __('common.cookie_analytics') }}" style="position:relative;width:48px;height:26px;background:#e2e8f0;border:none;border-radius:13px;cursor:pointer;transition:background 0.3s">
                            <div id="cookieAnalyticsDot" style="position:absolute;top:3px;inset-inline-start:3px;width:20px;height:20px;background:#fff;border-radius:50%;box-shadow:0 1px 4px rgba(0,0,0,0.15);transition:inset-inline-start 0.3s"></div>
                        </button>
                    </div>
                </div>

                {{-- Functional --}}
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px;transition:border-color 0.2s" id="cookieFunctionalCard">
                    <div style="display:flex;align-items:center;justify-content:space-between">
                        <div style="display:flex;align-items:center;gap:12px">
                            <div style="width:40px;height:40px;border-radius:10px;background:#fefce8;display:flex;align-items:center;justify-content:center">
                                <i aria-hidden="true" class="fas fa-cog" style="color:#ca8a04;font-size:1rem"></i>
                            </div>
                            <div>
                                <h4 style="font-weight:700;font-size:0.9rem;color:#1e293b;margin-bottom:2px">{{ __('common.cookie_functional') }}</h4>
                                <p style="font-size:0.78rem;color:#64748b;margin:0">{{ __('common.cookie_functional_desc') }}</p>
                            </div>
                        </div>
                        <button onclick="toggleCookie('functional')" id="cookieFunctionalToggle" aria-label="{{ __('common.cookie_functional') }}" style="position:relative;width:48px;height:26px;background:#e2e8f0;border:none;border-radius:13px;cursor:pointer;transition:background 0.3s">
                            <div id="cookieFunctionalDot" style="position:absolute;top:3px;inset-inline-start:3px;width:20px;height:20px;background:#fff;border-radius:50%;box-shadow:0 1px 4px rgba(0,0,0,0.15);transition:inset-inline-start 0.3s"></div>
                        </button>
                    </div>
                </div>

                {{-- Marketing --}}
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px;transition:border-color 0.2s" id="cookieMarketingCard">
                    <div style="display:flex;align-items:center;justify-content:space-between">
                        <div style="display:flex;align-items:center;gap:12px">
                            <div style="width:40px;height:40px;border-radius:10px;background:#fdf4ff;display:flex;align-items:center;justify-content:center">
                                <i aria-hidden="true" class="fas fa-bullhorn" style="color:#9333ea;font-size:1rem"></i>
                            </div>
                            <div>
                                <h4 style="font-weight:700;font-size:0.9rem;color:#1e293b;margin-bottom:2px">{{ __('common.cookie_marketing') }}</h4>
                                <p style="font-size:0.78rem;color:#64748b;margin:0">{{ __('common.cookie_marketing_desc') }}</p>
                            </div>
                        </div>
                        <button onclick="toggleCookie('marketing')" id="cookieMarketingToggle" aria-label="{{ __('common.cookie_marketing') }}" style="position:relative;width:48px;height:26px;background:#e2e8f0;border:none;border-radius:13px;cursor:pointer;transition:background 0.3s">
                            <div id="cookieMarketingDot" style="position:absolute;top:3px;inset-inline-start:3px;width:20px;height:20px;background:#fff;border-radius:50%;box-shadow:0 1px 4px rgba(0,0,0,0.15);transition:inset-inline-start 0.3s"></div>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div style="padding:0 24px 24px;display:flex;gap:8px;justify-content:flex-end">
                <button onclick="cookieRejectAll();closeCookieSettings()" style="padding:10px 20px;border:2px solid #e2e8f0;border-radius:8px;background:#fff;color:#64748b;font-size:0.85rem;font-weight:600;cursor:pointer;transition:all 0.2s" onmouseover="this.style.borderColor='#dc2626';this.style.color='#dc2626'" onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#64748b'">
                    {{ __('common.cookie_reject_all') }}
                </button>
                <button onclick="saveCookiePreferences();closeCookieSettings()" style="padding:10px 24px;border:none;border-radius:8px;background:linear-gradient(135deg,var(--color-primary),var(--color-primary-dark));color:#fff;font-size:0.85rem;font-weight:600;cursor:pointer;box-shadow:0 2px 8px rgba(13,107,79,0.3);transition:all 0.2s" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
                    <i aria-hidden="true" class="fas fa-save" style="margin-inline-end:6px"></i>{{ __('common.cookie_save') }}
                </button>
                <button onclick="cookieAcceptAll();closeCookieSettings()" style="padding:10px 24px;border:none;border-radius:8px;background:linear-gradient(135deg,var(--color-primary),var(--color-primary-dark));color:#fff;font-size:0.85rem;font-weight:600;cursor:pointer;box-shadow:0 2px 8px rgba(13,107,79,0.3);transition:all 0.2s" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
                    <i aria-hidden="true" class="fas fa-check-double" style="margin-inline-end:6px"></i>{{ __('common.cookie_accept_all') }}
                </button>
            </div>
        </div>
    </div>

    <script nonce="{{ $cspNonce }}">
    var cookiePrefs = {necessary:true,analytics:false,functional:false,marketing:false};

    function loadCookiePrefs() {
        try {
            var saved = localStorage.getItem('cookie_preferences');
            if (saved) {
                cookiePrefs = JSON.parse(saved);
            }
        } catch(e) {}
        updateToggles();
    }

    function updateToggles() {
        ['analytics','functional','marketing'].forEach(function(cat) {
            var toggle = document.getElementById('cookie'+capitalize(cat)+'Toggle');
            var dot = document.getElementById('cookie'+capitalize(cat)+'Dot');
            var card = document.getElementById('cookie'+capitalize(cat)+'Card');
            if (cookiePrefs[cat]) {
                toggle.style.background = '#0d6b4f';
                dot.style.insetInlineStart = 'calc(100% - 23px)';
                card.style.borderColor = '#bbf7d0';
            } else {
                toggle.style.background = '#e2e8f0';
                dot.style.insetInlineStart = '3px';
                card.style.borderColor = '#e2e8f0';
            }
        });
    }

    function capitalize(s) { return s.charAt(0).toUpperCase() + s.slice(1); }

    function toggleCookie(cat) {
        cookiePrefs[cat] = !cookiePrefs[cat];
        updateToggles();
    }

    function cookieAcceptAll() {
        cookiePrefs = {necessary:true,analytics:true,functional:true,marketing:true};
        localStorage.setItem('cookie_consent','accepted');
        localStorage.setItem('cookie_preferences',JSON.stringify(cookiePrefs));
        hideBanner();
    }

    function cookieRejectAll() {
        cookiePrefs = {necessary:true,analytics:false,functional:false,marketing:false};
        localStorage.setItem('cookie_consent','rejected');
        localStorage.setItem('cookie_preferences',JSON.stringify(cookiePrefs));
        hideBanner();
    }

    function saveCookiePreferences() {
        localStorage.setItem('cookie_consent','custom');
        localStorage.setItem('cookie_preferences',JSON.stringify(cookiePrefs));
        hideBanner();
    }

    function openCookieSettings() {
        loadCookiePrefs();
        document.getElementById('cookieModal').style.display = 'flex';
    }

    function closeCookieSettings() {
        document.getElementById('cookieModal').style.display = 'none';
    }

    function hideBanner() {
        document.getElementById('cookieBanner').style.transform = 'translateY(100%)';
    }

    function showBanner() {
        document.getElementById('cookieBanner').style.transform = 'translateY(0)';
    }

    function reopenCookieSettings() {
        openCookieSettings();
        showBanner();
    }

    loadCookiePrefs();
    if (!localStorage.getItem('cookie_consent')) {
        setTimeout(showBanner, 1000);
    }
    </script>
</body>
</html>
