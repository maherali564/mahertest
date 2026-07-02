@extends('layouts.app')
@section('title', __('complaints.title'))
@section('content')
<section style="padding:40px 0 48px;background:var(--color-bg-alt)">
    <div class="container" style="max-width:640px">
        <div class="section-header section-header--center" style="margin-bottom:28px">
            <span class="section-tag">{{ __('complaints.subtitle') }}</span>
            <h1 class="section-title">{{ __('complaints.title') }}</h1>
            <p style="max-width:560px;margin:0 auto;color:var(--color-text-muted)">{{ __('complaints.page_lead') }}</p>
        </div>
    <div class="container" style="max-width:640px">
        @if(session('success'))
        <div class="alert alert--success" role="alert">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert--error" role="alert">
            <ul style="margin:0;padding-inline-start:1.2rem">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="complaint-steps">
            <div class="complaint-step">
                <div class="complaint-step__num">1</div>
                <span class="complaint-step__label">{{ __('complaints.step1_title') }}</span>
                <span class="complaint-step__desc">{{ __('complaints.step1_desc') }}</span>
            </div>
            <div class="complaint-step">
                <div class="complaint-step__num">2</div>
                <span class="complaint-step__label">{{ __('complaints.step2_title') }}</span>
                <span class="complaint-step__desc">{{ __('complaints.step2_desc') }}</span>
            </div>
            <div class="complaint-step">
                <div class="complaint-step__num">3</div>
                <span class="complaint-step__label">{{ __('complaints.step3_title') }}</span>
                <span class="complaint-step__desc">{{ __('complaints.step3_desc') }}</span>
            </div>
        </div>

        <form action="{{ route('complaints.store', ['locale' => app()->getLocale()]) }}" method="POST" enctype="multipart/form-data" class="contact-form">
            @csrf
            <label>
                <span>{{ __('complaints.name') }}</span>
                <input type="text" name="name" value="{{ old('name') }}" required maxlength="255" placeholder="{{ __('complaints.name_placeholder') }}">
            </label>
            <label>
                <span>{{ __('complaints.email') }}</span>
                <input type="email" name="email" value="{{ old('email') }}" required maxlength="255" placeholder="{{ __('complaints.email_placeholder') }}">
            </label>
            <label>
                <span>{{ __('complaints.subject') }}</span>
                <select name="subject" required>
                    <option value="">{{ app()->getLocale() === 'ar' ? '— اختر الموضوع —' : '— Select subject —' }}</option>
                    <option value="donation" @selected(old('subject')==='donation')>{{ __('complaints.subject_donation') }}</option>
                    <option value="response" @selected(old('subject')==='response')>{{ __('complaints.subject_response') }}</option>
                    <option value="site" @selected(old('subject')==='site')>{{ __('complaints.subject_site') }}</option>
                    <option value="volunteer" @selected(old('subject')==='volunteer')>{{ __('complaints.subject_volunteer') }}</option>
                    <option value="other" @selected(old('subject')==='other')>{{ __('complaints.subject_other') }}</option>
                </select>
            </label>
            <label>
                <span>{{ __('complaints.description') }}</span>
                <textarea name="description" rows="6" required minlength="20" placeholder="{{ __('complaints.description_placeholder') }}">{{ old('description') }}</textarea>
            </label>
            <label>
                <span>{{ __('complaints.attachment') }}</span>
                <small style="display:block;color:var(--color-text-muted);font-weight:400;margin-top:2px">{{ __('complaints.attachment_hint') }}</small>
                <input type="file" name="attachment" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
            </label>
            <label class="checkbox-label">
                <input type="checkbox" name="consent" value="1" @checked(old('consent')) required>
                <span>{{ __('complaints.consent') }}</span>
            </label>
            <button type="submit" class="btn btn--primary btn--block">{{ __('complaints.submit') }}</button>
        </form>

        <div class="complaint-info">
            <div class="complaint-info__item">
                <span class="complaint-info__icon"><i aria-hidden="true" class="fas fa-clock" style="color:var(--color-primary)"></i></span>
                <span><strong>{{ __('complaints.response_time') }}:</strong> {{ __('complaints.response_time_value') }}</span>
            </div>
            <div class="complaint-info__item">
                <span class="complaint-info__icon"><i aria-hidden="true" class="fas fa-envelope" style="color:var(--color-primary)"></i></span>
                <span><strong>{{ __('complaints.contact_followup') }}:</strong> {{ __('complaints.contact_followup_value', ['email' => 'info@sahem.org', 'phone' => '+972 59 918 4228']) }}</span>
            </div>
        </div>
    </div>
</section>
@endsection
