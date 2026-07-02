@extends('layouts.app')
@section('content')
<section class="section page-header">
    <div class="container">
        <h1 class="section-title">{{ __('common.nav_projects') }}</h1>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="projects__grid {{ $projects->count() === 1 ? 'projects__grid--single' : '' }}">
            @foreach($projects as $project)
            <article class="project-card">
                @if($project->first_image)
                <div class="project-card__image" style="background-image:url('{{ asset('storage/'.$project->first_image) }}')">
                    @if($project->hasVideo())
                    <div class="project-card__video-badge">
                        <svg viewBox="0 0 24 24" width="32" height="32" fill="white"><polygon points="8,5 19,12 8,19"/></svg>
                    </div>
                    @endif
                </div>
                @endif
                <div class="project-card__body">
                    <h3>{{ trans_field($project, 'title') }}</h3>
                    <p>{{ trans_field($project, 'description') }}</p>
                    @if($project->goal_amount > 0 || ($project->raised_amount ?? 0) > 0)
                    <div class="project-progress">
                        <div class="project-progress__bar">
                            <div class="project-progress__fill" style="width:{{ $project->progressPercent() }}%"></div>
                        </div>
                        <div class="project-progress__stats">
                            <span>${{ number_format($project->raised_amount ?? 0) }} {{ __('common.raised') }}</span>
                            <span>${{ number_format($project->goal_amount) }} {{ __('common.goal') }}</span>
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
    </div>
</section>
@endsection
