@extends('layouts.app')

@section('meta_title', trans_field($post, 'meta_title') ?: trans_field($post, 'title'))
@section('meta_description', trans_field($post, 'meta_description') ?: trans_field($post, 'excerpt'))
@section('og_type', 'article')
@section('og_image', $post->featured_image ? asset('storage/'.$post->featured_image) : '')
@php $ogTitle = trans_field($post, 'meta_title') ?: trans_field($post, 'title'); @endphp
@push('head')
@if($post->published_at)
<meta property="article:published_time" content="{{ $post->published_at->toIso8601String() }}">
@endif
<meta name="twitter:title" content="{{ $ogTitle }}">
<meta name="twitter:description" content="{{ trans_field($post, 'meta_description') ?: trans_field($post, 'excerpt') ?: '' }}">
<meta name="twitter:image" content="{{ $post->featured_image ? asset('storage/'.$post->featured_image) : '' }}">
@endpush

@section('content')
<section class="section">
    <div class="container" style="max-width:800px">
        <article style="background:var(--color-bg);border-radius:var(--radius-md);box-shadow:var(--shadow-sm);border:1px solid var(--color-border);overflow:hidden">
            @if($post->featured_image)
            <img src="{{ asset('storage/'.$post->featured_image) }}" alt="{{ trans_field($post, 'title') }}" style="width:100%;height:auto;max-height:400px;object-fit:cover;display:block" loading="lazy">
            @endif

            <div style="padding:2rem">
                @if($post->category)
                <a href="{{ route('posts.category', ['locale' => app()->getLocale(), 'slug' => $post->category->slug]) }}" style="display:inline-block;background:var(--color-primary);color:#fff;font-size:0.8rem;padding:4px 14px;border-radius:20px;text-decoration:none;margin-bottom:12px">{{ trans_field($post->category, 'name') }}</a>
                @endif

                <h1 style="font-size:2rem;font-weight:800;margin-bottom:16px;line-height:1.3">{{ trans_field($post, 'title') }}</h1>

                <div style="display:flex;flex-wrap:wrap;gap:16px;font-size:0.85rem;color:var(--color-text-muted);padding-bottom:16px;margin-bottom:24px;border-bottom:1px solid var(--color-border)">
                    @if($post->user)
                    <span><i aria-hidden="true" class="far fa-user" style="margin-inline-end:4px"></i>{{ __('blog.by_author') }} {{ $post->user->name }}</span>
                    @endif
                    <span><i aria-hidden="true" class="far fa-calendar-alt" style="margin-inline-end:4px"></i>{{ $post->published_at->format('Y-m-d') }}</span>
                    <span><i aria-hidden="true" class="far fa-clock" style="margin-inline-end:4px"></i>{{ $post->reading_time }} {{ __('blog.minutes_read') }}</span>
                    <span><i aria-hidden="true" class="far fa-eye" style="margin-inline-end:4px"></i>{{ $post->views }}</span>
                </div>

                @if(trans_field($post, 'excerpt'))
                <p style="font-size:1rem;line-height:1.7;color:var(--color-text-muted);margin-bottom:24px;padding:16px;background:var(--color-bg-alt);border-radius:var(--radius-sm);border-inline-start:3px solid var(--color-primary)">{{ trans_field($post, 'excerpt') }}</p>
                @endif

                <div style="font-size:1rem;line-height:1.9;color:var(--color-text)">
                    {!! safe_html(trans_field($post, 'content')) !!}
                </div>

                @if($post->tags->count())
                <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:32px;padding-top:20px;border-top:1px solid var(--color-border)">
                    @foreach($post->tags as $tag)
                    <a href="{{ route('posts.tag', ['locale' => app()->getLocale(), 'slug' => $tag->slug]) }}" style="display:inline-block;background:var(--color-bg-alt);color:var(--color-text-muted);font-size:0.8rem;padding:4px 12px;border-radius:20px;text-decoration:none;border:1px solid var(--color-border)">#{{ trans_field($tag, 'name') }}</a>
                    @endforeach
                </div>
                @endif

                <div style="display:flex;justify-content:space-between;align-items:center;margin-top:32px;padding-top:20px;border-top:1px solid var(--color-border)">
                    <a href="{{ route('posts.index', ['locale' => app()->getLocale()]) }}" style="color:var(--color-primary);text-decoration:none;font-size:0.9rem">
                        <i aria-hidden="true" class="fas fa-arrow-{{ $isRtl ? 'right' : 'left' }}" style="margin-inline-end:6px"></i>{{ __('blog.back_to_posts') }}
                    </a>
                    <div style="display:flex;gap:12px">
                        <span style="font-size:0.85rem;color:var(--color-text-muted)">{{ __('blog.share') }}:</span>
                        <a href="https://wa.me/?text={{ urlencode(trans_field($post, 'title')) }}%20{{ urlencode(request()->url()) }}" target="_blank" style="color:#25D366;text-decoration:none;font-size:1.2rem"><i aria-hidden="true" class="fab fa-whatsapp"></i></a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode(trans_field($post, 'title')) }}&url={{ urlencode(request()->url()) }}" target="_blank" style="color:#1DA1F2;text-decoration:none;font-size:1.2rem"><i aria-hidden="true" class="fab fa-x-twitter"></i></a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" style="color:#1877F2;text-decoration:none;font-size:1.2rem"><i aria-hidden="true" class="fab fa-facebook"></i></a>
                    </div>
                </div>
            </div>
        </article>

        @if($relatedPosts->count())
        <section style="margin-top:3rem">
            <h3 style="font-size:1.3rem;font-weight:700;margin-bottom:1.5rem">{{ __('blog.related_posts') }}</h3>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px">
                @foreach($relatedPosts as $related)
                <a href="{{ route('posts.show', ['locale' => app()->getLocale(), 'slug' => $related->slug]) }}" style="display:block;background:var(--color-bg);border-radius:var(--radius-md);overflow:hidden;box-shadow:var(--shadow-sm);border:1px solid var(--color-border);text-decoration:none;transition:box-shadow 0.2s" onmouseover="this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.boxShadow='var(--shadow-sm)'">
                    @if($related->featured_image)
                    <img src="{{ asset('storage/'.$related->featured_image) }}" alt="" style="width:100%;height:120px;object-fit:cover" loading="lazy">
                    @endif
                    <div style="padding:12px">
                        <h4 style="font-size:0.9rem;font-weight:700;color:var(--color-text);line-height:1.4;margin:0">{{ trans_field($related, 'title') }}</h4>
                        <p style="font-size:0.75rem;color:var(--color-text-muted);margin:6px 0 0">{{ $related->published_at->diffForHumans() }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
        @endif
    </div>
</section>
@endsection
