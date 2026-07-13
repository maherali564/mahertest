@extends('layouts.app')

@section('meta_title', isset($category) ? trans_field($category, 'name') : (isset($tag) ? trans_field($tag, 'name') : __('blog.all_posts')))
@section('meta_description', isset($category) ? trans_field($category, 'description') : '')

@section('content')
<section class="section">
    <div class="container">
        <div class="section-header section-header--center">
            @if(isset($category))
                @php $title = trans_field($category, 'name'); $subtitle = trans_field($category, 'description'); @endphp
            @elseif(isset($tag))
                @php $title = '#'.trans_field($tag, 'name'); $subtitle = ''; @endphp
            @elseif($search)
                @php $title = __('blog.search_results_for', ['q' => $search]); $subtitle = ''; @endphp
            @else
                @php $title = __('blog.all_posts'); $subtitle = ''; @endphp
            @endif
            <span class="section-tag">{{ __('blog.latest_posts') }}</span>
            <h1 class="section-title">{{ $title }}</h1>
            @if($subtitle)<p style="color:var(--color-text-muted);max-width:600px;margin:0 auto">{{ $subtitle }}</p>@endif
        </div>

        <form method="GET" action="{{ route('posts.index', ['locale' => app()->getLocale()]) }}" style="max-width:500px;margin:1.5rem auto 0;display:flex;gap:8px">
            <input type="text" name="q" value="{{ old('q', $search ?? '') }}" placeholder="{{ __('blog.search_placeholder') }}" style="flex:1;padding:10px 16px;border:1px solid var(--color-border);border-radius:var(--radius-sm);font-size:0.9rem;background:var(--color-bg);color:var(--color-text)">
            <button type="submit" class="btn btn--primary" style="padding:10px 20px"><i aria-hidden="true" class="fas fa-search"></i></button>
        </form>

        @if($search && !$posts->count())
        <div style="text-align:center;padding:2rem">
            <i aria-hidden="true" class="fas fa-search" style="font-size:2rem;color:var(--color-text-muted);margin-bottom:0.5rem;display:block"></i>
            <p style="color:var(--color-text-muted);font-size:1.1rem">{{ __('blog.no_results') }}</p>
        </div>
        @elseif($posts->count())
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:24px;margin-top:2rem">
            @foreach($posts as $post)
            <article style="background:var(--color-bg);border-radius:var(--radius-md);overflow:hidden;box-shadow:var(--shadow-sm);border:1px solid var(--color-border);transition:box-shadow 0.2s" onmouseover="this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.boxShadow='var(--shadow-sm)'">
                <a href="{{ route('posts.show', ['locale' => app()->getLocale(), 'slug' => $post->slug]) }}">
                    @if($post->featured_image)
                    <img src="{{ asset('storage/'.$post->featured_image) }}" alt="{{ trans_field($post, 'title') }}" width="320" height="200" style="width:100%;height:200px;object-fit:cover;display:block" loading="lazy">
                    @else
                    <div style="width:100%;height:200px;background:linear-gradient(135deg,var(--color-primary),var(--color-primary-light));display:flex;align-items:center;justify-content:center;color:#fff;font-size:2rem"><i aria-hidden="true" class="fas fa-newspaper"></i></div>
                    @endif
                </a>
                <div style="padding:20px">
                    @if($post->category)
                    <a href="{{ route('posts.category', ['locale' => app()->getLocale(), 'slug' => $post->category->slug]) }}" style="display:inline-block;background:var(--color-primary);color:#fff;font-size:0.75rem;padding:3px 10px;border-radius:20px;text-decoration:none;margin-bottom:10px">{{ trans_field($post->category, 'name') }}</a>
                    @endif
                    <h2 style="font-size:1.1rem;font-weight:700;margin-bottom:8px;line-height:1.4">
                        <a href="{{ route('posts.show', ['locale' => app()->getLocale(), 'slug' => $post->slug]) }}" style="color:var(--color-text);text-decoration:none">{{ trans_field($post, 'title') }}</a>
                    </h2>
                    @if(trans_field($post, 'excerpt'))
                    <p style="color:var(--color-text-muted);font-size:0.85rem;line-height:1.6;margin-bottom:12px">{{ Str::limit(trans_field($post, 'excerpt'), 120) }}</p>
                    @endif
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:0.8rem;color:var(--color-text-muted);padding-top:12px;border-top:1px solid var(--color-border)">
                        <span>{{ $post->published_at->diffForHumans() }}</span>
                        <span><i aria-hidden="true" class="far fa-eye" style="margin-inline-end:4px"></i>{{ $post->views }}</span>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
        <div style="margin-top:2rem">
            {{ $posts->links() }}
        </div>
        @elseif(!$search)
        <div style="text-align:center;padding:4rem 2rem">
            <i aria-hidden="true" class="fas fa-newspaper" style="font-size:3rem;color:var(--color-text-muted);margin-bottom:1rem;display:block"></i>
            <p style="color:var(--color-text-muted);font-size:1.1rem">{{ __('blog.no_posts') }}</p>
        </div>
        @endif

        <div style="display:flex;justify-content:center;gap:16px;margin-top:2rem">
            <a href="{{ route('posts.categories', ['locale' => app()->getLocale()]) }}" style="display:inline-flex;align-items:center;gap:6px;color:var(--color-text-muted);text-decoration:none;font-size:0.85rem;padding:8px 16px;border:1px solid var(--color-border);border-radius:var(--radius-sm)" onmouseover="this.style.borderColor='var(--color-primary)'" onmouseout="this.style.borderColor='var(--color-border)'">
                <i aria-hidden="true" class="fas fa-folder-open"></i> {{ __('blog.categories') }}
            </a>
            <a href="{{ route('posts.tags', ['locale' => app()->getLocale()]) }}" style="display:inline-flex;align-items:center;gap:6px;color:var(--color-text-muted);text-decoration:none;font-size:0.85rem;padding:8px 16px;border:1px solid var(--color-border);border-radius:var(--radius-sm)" onmouseover="this.style.borderColor='var(--color-primary)'" onmouseout="this.style.borderColor='var(--color-border)'">
                <i aria-hidden="true" class="fas fa-tags"></i> {{ __('blog.tags') }}
            </a>
        </div>
    </div>
</section>
@endsection
