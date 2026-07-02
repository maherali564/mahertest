@extends('layouts.app')
@section('content')
<section class="section page-header">
    <div class="container">
        <span class="section-tag">{{ __('common.about_us') }}</span>
        <h1 class="section-title">{{ __('site.about_title') }}</h1>
        <p>{{ __('site.about_desc') }}</p>
    </div>
</section>

{{-- Our Story --}}
<section class="section">
    <div class="container">
        <div class="about-grid">
            <div>
                <span class="section-tag" style="margin-bottom:8px;display:inline-block">{{ __('site.our_story') }}</span>
                <h2 style="font-size:1.8rem;font-weight:800;margin-bottom:16px;line-height:1.3">{{ __('site.story_title') }}</h2>
                <div style="color:var(--color-text-muted);line-height:1.8;font-size:1rem">
                    <p style="margin-bottom:12px">مبادرة «ساهم» للإغاثة والتنمية؛ هي مبادرة إنسانية غير حكومية، انطلقت برؤية شبابية تطوعية استجابةً للأزمات الإنسانية المتلاحقة.</p>
                    <p style="margin-bottom:12px">واليوم، غدت "ساهم" منظمة دولية رسمية تسعى إلى تقديم الإغاثة والدعم العاجل والمستدام للمجتمعات المتضررة، مؤمنين بأن التغيير يبدأ بـالمساهمة، متسلحين بخبرة ميدانية عميقة وفهم دقيق لاحتياجات الفئات الأكثر تضرراً والمنكوبين.</p>
                </div>
            </div>
            <div style="height:clamp(400px,50vw,600px);background:linear-gradient(135deg,var(--color-primary),var(--color-primary-dark));border-radius:var(--radius-lg);display:flex;align-items:center;justify-content:center;color:#fff;font-size:4rem;overflow:hidden">
                @if($settings->about_image)
                    <img loading="lazy" src="{{ asset('storage/'.$settings->about_image) }}" alt="" style="width:100%;height:100%;object-fit:cover">
                @else
                    <i aria-hidden="true" class="fas fa-hand-holding-heart"></i>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Mission & Vision --}}
<section style="background:var(--color-bg-alt);padding:80px 0">
    <div class="container">
        <div class="about-grid-2">
            <div style="background:var(--color-bg);border-radius:var(--radius-md);padding:32px;box-shadow:var(--shadow-sm);border:1px solid var(--color-border);text-align:center">
                <div style="width:64px;height:64px;border-radius:50%;background:var(--color-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.5rem;margin:0 auto 16px"><i aria-hidden="true" class="fas fa-bullseye"></i></div>
                <h3 style="font-size:1.3rem;font-weight:700;margin-bottom:8px">رسالتنا</h3>
                <p style="color:var(--color-text-muted);line-height:1.7">تخفيف المعاناة الإنسانية عبر تقديم مساعدات إغاثية فورية، وتطوير برامج تنموية مستدامة تمكّن الأفراد والمجتمعات من النهوض مجدداً.</p>
            </div>
            <div style="background:var(--color-bg);border-radius:var(--radius-md);padding:32px;box-shadow:var(--shadow-sm);border:1px solid var(--color-border);text-align:center">
                <div style="width:64px;height:64px;border-radius:50%;background:var(--color-accent);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.5rem;margin:0 auto 16px"><i aria-hidden="true" class="fas fa-eye"></i></div>
                <h3 style="font-size:1.3rem;font-weight:700;margin-bottom:8px">رؤيتنا</h3>
                <p style="color:var(--color-text-muted);line-height:1.7">ريادة العمل الإنساني والتنموي لبناء مجتمعات صامدة ومكتفية ذاتياً.</p>
            </div>
        </div>
    </div>
</section>

{{-- Core Values --}}
<section class="section">
    <div class="container">
        <div class="section-header section-header--center">
            <span class="section-tag">{{ __('site.core_values') }}</span>
            <h2 class="section-title">{{ __('site.values_title') }}</h2>
        </div>
        <div class="values-grid">
            <div style="text-align:center;padding:28px;background:var(--color-bg);border-radius:var(--radius-md);box-shadow:var(--shadow-sm);border:1px solid var(--color-border)">
                <div style="width:56px;height:56px;border-radius:50%;background:var(--color-bg-alt);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:1.3rem;color:var(--color-primary)">
                    <i aria-hidden="true" class="fas fa-eye"></i>
                </div>
                <h4 style="font-weight:700;margin-bottom:6px">الشفافية</h4>
                <p style="font-size:0.85rem;color:var(--color-text-muted)">نلتزم بالوضوح الكامل في إدارة الموارد والإبلاغ عن الأثر.</p>
            </div>
            <div style="text-align:center;padding:28px;background:var(--color-bg);border-radius:var(--radius-md);box-shadow:var(--shadow-sm);border:1px solid var(--color-border)">
                <div style="width:56px;height:56px;border-radius:50%;background:var(--color-bg-alt);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:1.3rem;color:var(--color-primary)">
                    <i aria-hidden="true" class="fas fa-bolt"></i>
                </div>
                <h4 style="font-weight:700;margin-bottom:6px">الاستجابة السريعة</h4>
                <p style="font-size:0.85rem;color:var(--color-text-muted)">نصل إلى المتضررين في أسرع وقت ممكن لإنقاذ الأرواح وتخفيف المعاناة.</p>
            </div>
            <div style="text-align:center;padding:28px;background:var(--color-bg);border-radius:var(--radius-md);box-shadow:var(--shadow-sm);border:1px solid var(--color-border)">
                <div style="width:56px;height:56px;border-radius:50%;background:var(--color-bg-alt);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:1.3rem;color:var(--color-primary)">
                    <i aria-hidden="true" class="fas fa-shield-alt"></i>
                </div>
                <h4 style="font-weight:700;margin-bottom:6px">الأمانة</h4>
                <p style="font-size:0.85rem;color:var(--color-text-muted)">نحافظ على أمانة التبرع ونضمن وصولها لمستحقيها بكل دقة ومسؤولية.</p>
            </div>
            <div style="text-align:center;padding:28px;background:var(--color-bg);border-radius:var(--radius-md);box-shadow:var(--shadow-sm);border:1px solid var(--color-border)">
                <div style="width:56px;height:56px;border-radius:50%;background:var(--color-bg-alt);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:1.3rem;color:var(--color-primary)">
                    <i aria-hidden="true" class="fas fa-hands-helping"></i>
                </div>
                <h4 style="font-weight:700;margin-bottom:6px">التمكين</h4>
                <p style="font-size:0.85rem;color:var(--color-text-muted)">نعمل على تمكين الأفراد والمجتمعات لتحقيق اكتفاء ذاتي مستدام.</p>
            </div>
        </div>
    </div>
</section>

{{-- Impact Stats --}}
<section style="background:var(--color-bg-dark);padding:60px 0">
    <div class="container">
        <div class="stats__grid">
            <div class="stat-item stat-item--dark">
                <span class="stat-item__number" data-amount="{{ $totalRaised }}">${{ number_format($totalRaised, 0) }}</span>
                <span class="stat-item__label">{{ __('common.total_raised') }}</span>
            </div>
            <div class="stat-item stat-item--dark">
                <span class="stat-item__number">{{ $totalDonations }}</span>
                <span class="stat-item__label">{{ __('common.total_donations') }}</span>
            </div>
            <div class="stat-item stat-item--dark">
                <span class="stat-item__number">{{ $totalDonors }}</span>
                <span class="stat-item__label">{{ __('common.total_donors') }}</span>
            </div>
            @foreach($achievementStats as $stat)
            <div class="stat-item stat-item--dark">
                <span class="stat-item__number">{{ number_format($stat->value) }}</span>
                <span class="stat-item__label">{{ trans_field($stat, 'label') }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Partners --}}
@if($partners->isNotEmpty())
<section class="section">
    <div class="container">
        <div class="section-header section-header--center">
            <span class="section-tag">{{ __('site.our_partners') }}</span>
            <h2 class="section-title">{{ __('site.partners_title') }}</h2>
            <p>{{ __('site.partners_desc') }}</p>
        </div>
        <div class="partners-flex">
            @foreach($partners as $partner)
            <div style="width:160px;height:100px;background:var(--color-bg);border:1px solid var(--color-border);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;padding:16px;box-shadow:var(--shadow-sm)">
                @if($partner->logo)
                    <img loading="lazy" src="{{ asset('storage/'.$partner->logo) }}" alt="{{ trans_field($partner, 'name') }}" style="max-width:100%;max-height:100%;object-fit:contain">
                @else
                    <span style="font-size:0.85rem;color:var(--color-text-muted);text-align:center">{{ trans_field($partner, 'name') }}</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA --}}
<section style="background:var(--color-primary);padding:60px 0;text-align:center;color:#fff">
    <div class="container">
        <h2 style="font-size:1.8rem;font-weight:800;margin-bottom:12px">{{ __('site.cta_title') }}</h2>
        <p style="opacity:0.9;font-size:1.05rem;margin-bottom:24px;max-width:600px;margin-inline:auto">{{ __('site.cta_desc') }}</p>
        <a href="{{ route('donate.page', ['locale' => $currentLocale]) }}" class="btn" style="background:#fff;color:var(--color-primary);font-weight:700;padding:14px 36px">{{ __('common.donate_now') }}</a>
    </div>
</section>
@endsection
