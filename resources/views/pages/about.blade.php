@extends('layouts.app')
@php $isAr = app()->getLocale() === 'ar'; @endphp
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
        <div class="about-grid" style="align-items:start">
            <div>
                <span class="section-tag" style="margin-bottom:8px;display:inline-block">{{ __('site.our_story') }}</span>
                <h2 style="font-size:1.8rem;font-weight:800;margin-bottom:16px;line-height:1.3">{{ $isAr ? 'منذ البداية، كانت رؤيتنا واضحة' : 'Our vision was clear from the start' }}</h2>
                <div style="color:var(--color-text-muted);line-height:1.8;font-size:1rem">
                    <p style="margin-bottom:12px">{{ $isAr ? 'بدأت «ساهم» للإغاثة والتنمية؛ كمبادرة إنسانية غير حكومية، برؤية شبابية تطوعية استجابةً للأزمات الإنسانية المتلاحقة، قبل أن تتطور إلى منظمة غير حكومية بهيكلها التنظيمي الحالي' : 'Sahem for Relief and Development began as a non-governmental humanitarian initiative with a volunteer youth vision, responding to successive humanitarian crises, before evolving into a formally structured non-governmental organization.' }}</p>
                    <p style="margin-bottom:12px">{{ $isAr ? 'واليوم، غدت "ساهم" منظمة دولية رسمية تسعى إلى تقديم الإغاثة والدعم العاجل والمستدام للمجتمعات المتضررة، مؤمنين بأن التغيير يبدأ بالمساهمة، متسلحين بخبرة ميدانية عميقة وفهم دقيق لاحتياجات الفئات الأكثر تضرراً والمنكوبين.' : 'Today, Sahem has become an official international organization working to provide urgent and sustainable relief and support to affected communities, believing that change begins with contribution, armed with deep field experience and a precise understanding of the needs of the most vulnerable and afflicted.' }}</p>
                </div>
            </div>
            <div style="height:clamp(400px,50vw,600px);background:linear-gradient(135deg,var(--color-primary),var(--color-primary-dark));border-radius:var(--radius-lg);display:flex;align-items:center;justify-content:center;color:#fff;font-size:4rem;overflow:hidden">
                @if($settings->about_image)
                    <img loading="lazy" src="{{ asset('storage/'.$settings->about_image) }}" alt="" width="1200" height="600" style="width:100%;height:100%;object-fit:cover">
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
                <h3 style="font-size:1.3rem;font-weight:700;margin-bottom:8px">{{ $isAr ? 'رسالتنا' : 'Our Mission' }}</h3>
                <p style="color:var(--color-text-muted);line-height:1.7">{{ $isAr ? 'تخفيف المعاناة الإنسانية عبر تقديم مساعدات إغاثية فورية، وتطوير برامج تنموية مستدامة تمكّن الأفراد والمجتمعات من النهوض مجدداً.' : 'To alleviate human suffering by providing immediate relief aid and developing sustainable programs that empower individuals and communities to rebuild their lives.' }}</p>
            </div>
            <div style="background:var(--color-bg);border-radius:var(--radius-md);padding:32px;box-shadow:var(--shadow-sm);border:1px solid var(--color-border);text-align:center">
                <div style="width:64px;height:64px;border-radius:50%;background:var(--color-accent);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.5rem;margin:0 auto 16px"><i aria-hidden="true" class="fas fa-eye"></i></div>
                <h3 style="font-size:1.3rem;font-weight:700;margin-bottom:8px">{{ $isAr ? 'رؤيتنا' : 'Our Vision' }}</h3>
                <p style="color:var(--color-text-muted);line-height:1.7">{{ $isAr ? 'ريادة العمل الإنساني والتنموي لبناء مجتمعات صامدة ومكتفية ذاتياً.' : 'To lead humanitarian and development work in building resilient, self-sufficient communities.' }}</p>
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
                <h4 style="font-weight:700;margin-bottom:6px">{{ $isAr ? 'الشفافية' : 'Transparency' }}</h4>
                <p style="font-size:0.85rem;color:var(--color-text-muted)">{{ $isAr ? 'نلتزم بالوضوح الكامل في إدارة الموارد والإبلاغ عن الأثر.' : 'We are committed to full clarity in resource management and impact reporting.' }}</p>
            </div>
            <div style="text-align:center;padding:28px;background:var(--color-bg);border-radius:var(--radius-md);box-shadow:var(--shadow-sm);border:1px solid var(--color-border)">
                <div style="width:56px;height:56px;border-radius:50%;background:var(--color-bg-alt);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:1.3rem;color:var(--color-primary)">
                    <i aria-hidden="true" class="fas fa-bolt"></i>
                </div>
                <h4 style="font-weight:700;margin-bottom:6px">{{ $isAr ? 'الاستجابة السريعة' : 'Rapid Response' }}</h4>
                <p style="font-size:0.85rem;color:var(--color-text-muted)">{{ $isAr ? 'نصل إلى المتضررين في أسرع وقت ممكن لإنقاذ الأرواح وتخفيف المعاناة.' : 'We reach those affected as quickly as possible to save lives and alleviate suffering.' }}</p>
            </div>
            <div style="text-align:center;padding:28px;background:var(--color-bg);border-radius:var(--radius-md);box-shadow:var(--shadow-sm);border:1px solid var(--color-border)">
                <div style="width:56px;height:56px;border-radius:50%;background:var(--color-bg-alt);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:1.3rem;color:var(--color-primary)">
                    <i aria-hidden="true" class="fas fa-shield-alt"></i>
                </div>
                <h4 style="font-weight:700;margin-bottom:6px">{{ $isAr ? 'الأمانة' : 'Stewardship' }}</h4>
                <p style="font-size:0.85rem;color:var(--color-text-muted)">{{ $isAr ? 'نحافظ على أمانة التبرع ونضمن وصولها لمستحقيها بكل دقة ومسؤولية.' : 'We uphold the trust of every donation, ensuring it reaches those entitled to it with accuracy and responsibility.' }}</p>
            </div>
            <div style="text-align:center;padding:28px;background:var(--color-bg);border-radius:var(--radius-md);box-shadow:var(--shadow-sm);border:1px solid var(--color-border)">
                <div style="width:56px;height:56px;border-radius:50%;background:var(--color-bg-alt);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:1.3rem;color:var(--color-primary)">
                    <i aria-hidden="true" class="fas fa-hands-helping"></i>
                </div>
                <h4 style="font-weight:700;margin-bottom:6px">{{ $isAr ? 'التمكين' : 'Empowerment' }}</h4>
                <p style="font-size:0.85rem;color:var(--color-text-muted)">{{ $isAr ? 'نعمل على تمكين الأفراد والمجتمعات لتحقيق اكتفاء ذاتي مستدام.' : 'We work to empower individuals and communities to achieve sustainable self-sufficiency.' }}</p>
            </div>
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
                    <img loading="lazy" src="{{ asset('storage/'.$partner->logo) }}" alt="{{ trans_field($partner, 'name') }}" width="160" height="100" style="max-width:100%;max-height:100%;object-fit:contain">
                @else
                    <span style="font-size:0.85rem;color:var(--color-text-muted);text-align:center">{{ trans_field($partner, 'name') }}</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif


@endsection