@extends('layouts.app')
@push('head')
<style>
@media (max-width:640px) {
    .transparency-grid { grid-template-columns:1fr !important; }
    .trust-badges-grid { grid-template-columns:1fr !important; }
}
</style>
@endpush
@section('content')
<section class="section page-header">
    <div class="container">
        <span class="section-tag">{{ __('common.transparency') }}</span>
        <h1 class="section-title">{{ __('common.transparency_title') }}</h1>
        <p>{{ __('common.transparency_desc') }}</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="stats__grid" style="margin-bottom:2rem">
            <div class="stat-item" style="background:var(--color-bg);border-radius:var(--radius-md);padding:24px;box-shadow:var(--shadow-sm);border:1px solid var(--color-border)">
                <span class="stat-item__number">${{ number_format($totalRaised, 0) }}</span>
                <span class="stat-item__label">{{ __('common.total_raised') }}</span>
            </div>
            <div class="stat-item" style="background:var(--color-bg);border-radius:var(--radius-md);padding:24px;box-shadow:var(--shadow-sm);border:1px solid var(--color-border)">
                <span class="stat-item__number">{{ $totalDonations }}</span>
                <span class="stat-item__label">{{ __('common.total_donations') }}</span>
            </div>
            <div class="stat-item" style="background:var(--color-bg);border-radius:var(--radius-md);padding:24px;box-shadow:var(--shadow-sm);border:1px solid var(--color-border)">
                <span class="stat-item__number">{{ $totalDonors }}</span>
                <span class="stat-item__label">{{ __('common.total_donors') }}</span>
            </div>
        </div>

        <div class="transparency-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:2rem">
            <div style="background:var(--color-bg);border-radius:var(--radius-md);padding:28px;box-shadow:var(--shadow-sm);border:1px solid var(--color-border)">
                <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:16px">{{ __('common.where_donations_go') }}</h3>
                <div style="margin-bottom:12px">
                    <div style="display:flex;justify-content:space-between;margin-bottom:4px;font-size:0.9rem">
                        <span>{{ __('common.direct_aid') }}</span>
                        <span style="font-weight:700">{{ 100 - $adminCostRate }}%</span>
                    </div>
                    <div class="progress-bar" style="height:8px;background:var(--color-border);border-radius:4px;overflow:hidden">
                        <div class="progress-bar__fill" style="width:{{ 100 - $adminCostRate }}%;height:100%;background:var(--color-primary);border-radius:4px"></div>
                    </div>
                </div>
                <div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:4px;font-size:0.9rem">
                        <span>{{ __('common.admin_costs') }}</span>
                        <span style="font-weight:700">{{ $adminCostRate }}%</span>
                    </div>
                    <div class="progress-bar" style="height:8px;background:var(--color-border);border-radius:4px;overflow:hidden">
                        <div class="progress-bar__fill" style="width:{{ $adminCostRate }}%;height:100%;background:var(--color-accent);border-radius:4px"></div>
                    </div>
                </div>
                <p style="margin-top:16px;font-size:0.85rem;color:var(--color-text-muted)">{{ __('common.admin_cost_desc') }}</p>
            </div>

            <div style="background:var(--color-bg);border-radius:var(--radius-md);padding:28px;box-shadow:var(--shadow-sm);border:1px solid var(--color-border)">
                <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:16px">{{ __('common.program_breakdown') }}</h3>
                @forelse($projectBreakdown as $project)
                <div style="margin-bottom:10px">
                    <div style="display:flex;justify-content:space-between;font-size:0.85rem">
                        <span>{{ $project['title'] ?? '' }}</span>
                        <span style="font-weight:600">${{ number_format($project['raised'], 0) }} / ${{ number_format($project['goal'], 0) }}</span>
                    </div>
                    <div class="progress-bar" style="height:6px;background:var(--color-border);border-radius:3px;overflow:hidden">
                        <div class="progress-bar__fill" style="width:{{ $project['percent'] }}%;height:100%;background:var(--color-primary);border-radius:3px"></div>
                    </div>
                </div>
                @empty
                <p style="color:var(--color-text-muted)">{{ __('common.no_projects_yet') }}</p>
                @endforelse
            </div>
        </div>

        <div style="text-align:center;padding:32px;background:var(--color-bg-alt);border-radius:var(--radius-md)">
            <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:8px">{{ __('common.report_request') }}</h3>
            <p style="color:var(--color-text-muted);font-size:0.9rem;margin-bottom:16px">{{ __('common.report_request_desc') }}</p>
            <a href="{{ route('home', ['locale' => $currentLocale]) }}#contact" class="btn btn--primary">{{ __('common.contact_us') }}</a>
        </div>

        <div style="margin-top:2rem;background:var(--color-bg);border-radius:var(--radius-md);padding:2rem;box-shadow:0 0 20px rgba(34,139,34,0.15),var(--shadow-sm);border:1px solid rgba(34,139,34,0.25)">
            <div style="text-align:center;max-width:700px;margin:0 auto 1.5rem">
                <p style="font-size:0.9rem;line-height:1.8;color:var(--color-text-muted)">تخضع منظمة ساهم الدولية للاغاثة والتنمية لقوانين الاتحاد الاوروبي وتمتثل بعملها وفق اعلى معايير الحوكمة والشفافية المالية والإدارية. ونلتزم بأمن المعلومات وبقواعد أمان صارمة ونزاهة مؤسسية تضمن إيصال المساعدات لمستحقيها بكل مسؤولية وموثوقية قانونية</p>
            </div>
            <div class="trust-badges-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;max-width:500px;margin:0 auto">
                <div style="display:flex;flex-direction:column;align-items:center;gap:4px;background:rgba(255,255,255,0.5);border:1px solid var(--color-border);border-radius:12px;padding:18px 12px;text-align:center">
                    <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--color-primary),#1a6b3c);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.9rem;flex-shrink:0;margin-bottom:4px"><i aria-hidden="true" class="fas fa-lock"></i></div>
                    <strong style="font-size:0.85rem;line-height:1.3">SSL Secured</strong>
                    <span style="font-size:0.7rem;color:var(--color-text-muted);line-height:1.2">مشفّر 256-bit</span>
                </div>
                <div style="display:flex;flex-direction:column;align-items:center;gap:4px;background:rgba(255,255,255,0.5);border:1px solid var(--color-border);border-radius:12px;padding:18px 12px;text-align:center">
                    <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--color-primary),#1a6b3c);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.9rem;flex-shrink:0;margin-bottom:4px"><i aria-hidden="true" class="fas fa-credit-card"></i></div>
                    <strong style="font-size:0.85rem;line-height:1.3">متوافق مع PCI</strong>
                    <span style="font-size:0.7rem;color:var(--color-text-muted);line-height:1.2">أمان المدفوعات</span>
                </div>
                <div style="display:flex;flex-direction:column;align-items:center;gap:4px;background:rgba(255,255,255,0.5);border:1px solid var(--color-border);border-radius:12px;padding:18px 12px;text-align:center">
                    <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--color-primary),#1a6b3c);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.9rem;flex-shrink:0;margin-bottom:4px"><i aria-hidden="true" class="fas fa-certificate"></i></div>
                    <strong style="font-size:0.85rem;line-height:1.3">مرخصة رسمياً</strong>
                    <span style="font-size:0.7rem;color:var(--color-text-muted);line-height:1.2">منظمة مسجلة</span>
                </div>
                <div style="display:flex;flex-direction:column;align-items:center;gap:4px;background:rgba(255,255,255,0.5);border:1px solid var(--color-border);border-radius:12px;padding:18px 12px;text-align:center">
                    <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--color-primary),#1a6b3c);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.9rem;flex-shrink:0;margin-bottom:4px"><i aria-hidden="true" class="fas fa-hand-holding-heart"></i></div>
                    <strong style="font-size:0.85rem;line-height:1.3">جهة خيرية رسمية</strong>
                    <span style="font-size:0.7rem;color:var(--color-text-muted);line-height:1.2">معتمد وموثق</span>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
