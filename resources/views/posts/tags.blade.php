@extends('layouts.app')

@section('meta_title', __('blog.tags'))
@section('meta_description', '')

@section('content')
<section class="section">
    <div class="container">
        <div class="section-header section-header--center">
            <span class="section-tag">{{ __('blog.all_tags') }}</span>
            <h1 class="section-title">{{ __('blog.tags') }}</h1>
        </div>

        @if($tags->isNotEmpty())
        <div style="display:flex;flex-wrap:wrap;gap:12px;justify-content:center;margin-top:2rem">
            @foreach($tags as $tag)
            <a href="{{ route('posts.tag', ['locale' => app()->getLocale(), 'slug' => $tag->slug]) }}" style="display:inline-block;background:var(--color-bg);border-radius:20px;padding:10px 20px;box-shadow:var(--shadow-sm);border:1px solid var(--color-border);text-decoration:none;transition:all 0.2s;font-size:0.95rem" onmouseover="this.style.boxShadow='var(--shadow-md)';this.style.borderColor='var(--color-primary)'" onmouseout="this.style.boxShadow='var(--shadow-sm)';this.style.borderColor='var(--color-border)'">
                <span style="color:var(--color-text);font-weight:600">#{{ trans_field($tag, 'name') }}</span>
                <span style="color:var(--color-text-muted);font-size:0.8rem;margin-inline-start:6px">({{ $tag->posts_count }})</span>
            </a>
            @endforeach
        </div>
        @else
        <div style="text-align:center;padding:4rem 2rem">
            <i aria-hidden="true" class="fas fa-tags" style="font-size:3rem;color:var(--color-text-muted);margin-bottom:1rem;display:block"></i>
            <p style="color:var(--color-text-muted);font-size:1.1rem">{{ __('blog.no_posts') }}</p>
        </div>
        @endif
    </div>
</section>
@endsection
