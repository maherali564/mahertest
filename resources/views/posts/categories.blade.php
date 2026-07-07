@extends('layouts.app')

@section('meta_title', __('blog.categories'))
@section('meta_description', '')

@section('content')
<section class="section">
    <div class="container">
        <div class="section-header section-header--center">
            <span class="section-tag">{{ __('blog.all_categories') }}</span>
            <h1 class="section-title">{{ __('blog.categories') }}</h1>
        </div>

        @if($categories->isNotEmpty())
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px;margin-top:2rem">
            @foreach($categories as $category)
            <a href="{{ route('posts.category', ['locale' => app()->getLocale(), 'slug' => $category->slug]) }}" style="display:block;background:var(--color-bg);border-radius:var(--radius-md);padding:24px;box-shadow:var(--shadow-sm);border:1px solid var(--color-border);text-decoration:none;transition:box-shadow 0.2s" onmouseover="this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.boxShadow='var(--shadow-sm)'">
                <h3 style="font-size:1.1rem;font-weight:700;color:var(--color-text);margin-bottom:6px">{{ trans_field($category, 'name') }}</h3>
                @if(trans_field($category, 'description'))
                <p style="color:var(--color-text-muted);font-size:0.85rem;line-height:1.6;margin-bottom:8px">{{ trans_field($category, 'description') }}</p>
                @endif
                <span style="font-size:0.8rem;color:var(--color-primary);font-weight:600">{{ $category->posts_count }} {{ __('blog.x_posts', ['count' => $category->posts_count]) }}</span>
            </a>
            @endforeach
        </div>
        @else
        <div style="text-align:center;padding:4rem 2rem">
            <i aria-hidden="true" class="fas fa-folder-open" style="font-size:3rem;color:var(--color-text-muted);margin-bottom:1rem;display:block"></i>
            <p style="color:var(--color-text-muted);font-size:1.1rem">{{ __('blog.no_posts') }}</p>
        </div>
        @endif
    </div>
</section>
@endsection
