@php $s = $settings ?? \App\Models\SiteSetting::current(); @endphp
<footer class="footer">
    <div class="container">
        <div class="footer__grid">
            <div class="footer__col footer__col--brand">
                @php
                    $logoSrc = null;
                    if ($s->logos && is_array($s->logos) && isset($s->logos[$currentLocale])) {
                        $logoSrc = $s->logos[$currentLocale];
                    } elseif ($s->logo) {
                        $logoSrc = $s->logo;
                    }
                @endphp
                @if($logoSrc)
                    <img loading="lazy" src="{{ asset('storage/'.$logoSrc) }}" alt="ساهم" class="footer__logo">
                @else
                    <div class="footer__logo-text">ساهم</div>
                @endif
                <p class="footer__desc">منظمة ساهم غير ربحية مستقلة وشفافة تهدف لمساعدة المجتمعات المتضررة</p>
                <div class="footer__social">
                    @if($s->twitter)<a href="{{ $s->twitter }}" target="_blank" rel="noopener" aria-label="X"><svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>@endif
                    @if($s->facebook)<a href="{{ $s->facebook }}" target="_blank" rel="noopener" aria-label="Facebook"><i aria-hidden="true" class="fab fa-facebook-f"></i></a>@endif
                    @if($s->instagram)<a href="{{ $s->instagram }}" target="_blank" rel="noopener" aria-label="Instagram"><i aria-hidden="true" class="fab fa-instagram"></i></a>@endif
                    @if($s->linkedin)<a href="{{ $s->linkedin }}" target="_blank" rel="noopener" aria-label="LinkedIn"><i aria-hidden="true" class="fab fa-linkedin-in"></i></a>@endif
                    @if($s->youtube)<a href="{{ $s->youtube }}" target="_blank" rel="noopener" aria-label="YouTube"><i aria-hidden="true" class="fab fa-youtube"></i></a>@endif
                </div>
            </div>
            <div class="footer__col footer__col--trust">
                <h4 class="footer__heading">الشفافية والتوثيق</h4>
                <div class="footer__badges">
                    <div style="display:flex;align-items:center;gap:8px;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);border-radius:10px;padding:8px 12px;white-space:nowrap">
                        <div style="width:26px;height:26px;border-radius:50%;background:var(--color-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.7rem;flex-shrink:0"><i aria-hidden="true" class="fas fa-credit-card"></i></div>
                        <div style="display:flex;align-items:baseline;gap:6px">
                            <strong style="font-size:0.78rem;color:#F8F9FA">شهادة PCI</strong>
                            <span style="font-size:0.65rem;color:#A3AED0">أمان المدفوعات</span>
                        </div>
                        <span style="color:var(--color-primary);font-size:0.7rem"><i aria-hidden="true" class="fas fa-check-circle"></i></span>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);border-radius:10px;padding:8px 12px;white-space:nowrap">
                        <div style="width:26px;height:26px;border-radius:50%;background:var(--color-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.7rem;flex-shrink:0"><i aria-hidden="true" class="fas fa-hand-holding-heart"></i></div>
                        <div style="display:flex;align-items:baseline;gap:6px">
                            <strong style="font-size:0.78rem;color:#F8F9FA">مرخصة رسمياً</strong>
                            <span style="font-size:0.65rem;color:#A3AED0">منظمة مسجلة</span>
                        </div>
                        <span style="color:var(--color-primary);font-size:0.7rem"><i aria-hidden="true" class="fas fa-check-circle"></i></span>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);border-radius:10px;padding:8px 12px;white-space:nowrap">
                        <div style="width:26px;height:26px;border-radius:50%;background:#d4a017;color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.7rem;flex-shrink:0"><i aria-hidden="true" class="fas fa-certificate"></i></div>
                        <div style="display:flex;align-items:baseline;gap:6px">
                            <strong style="font-size:0.78rem;color:#F8F9FA">حاصل على شهادة أمنية</strong>
                            <span style="font-size:0.65rem;color:#A3AED0">معتمد وموثق</span>
                        </div>
                        <span style="color:var(--color-primary);font-size:0.7rem"><i aria-hidden="true" class="fas fa-check-circle"></i></span>
                    </div>
                </div>
            </div>
            <div class="footer__col footer__col--links">
                <h4 class="footer__heading">روابط سريعة</h4>
                <nav class="footer__nav">
                    <a href="{{ route('home', ['locale' => $currentLocale]) }}">الرئيسية</a>
                    <a href="{{ route('about.index', ['locale' => $currentLocale]) }}">من نحن</a>
                    <a href="{{ route('projects.index', ['locale' => $currentLocale]) }}">المشاريع</a>
                    <a href="{{ route('transparency.index', ['locale' => $currentLocale]) }}">الشفافية</a>
                    <a href="{{ route('home', ['locale' => $currentLocale]) }}#contact">تواصل معنا</a>
                </nav>
            </div>
        </div>
        <div class="footer__bottom">
            <p>{{ __('common.all_rights') }} &copy; {{ trans_field($s, 'site_name') }} {{ date('Y') }} — <a href="{{ route('pages.privacy', ['locale' => $currentLocale]) }}" style="color:rgba(163,174,208,0.6);text-decoration:underline">{{ __('common.privacy_policy') }}</a> — <a href="#" onclick="event.preventDefault();reopenCookieSettings()" style="color:rgba(163,174,208,0.6);text-decoration:underline;cursor:pointer">{{ __('common.cookie_settings') }}</a></p>
        </div>
    </div>
</footer>
