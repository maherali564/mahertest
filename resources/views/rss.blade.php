<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:content="http://purl.org/rss/1.0/modules/content/">
    <channel>
        <title>{{ __('blog.rss_title') }}</title>
        <link>{{ url($locale) }}</link>
        <description>{{ __('blog.rss_description') }}</description>
        <language>{{ $locale }}</language>
        <atom:link href="{{ url($locale.'/rss.xml') }}" rel="self" type="application/rss+xml"/>
        @foreach($items as $post)
        <item>
            <title><![CDATA[{{ trans_field($post, 'title') }}]]></title>
            <link>{{ route('posts.show', ['locale' => $locale, 'slug' => $post->slug]) }}</link>
            <guid isPermaLink="true">{{ route('posts.show', ['locale' => $locale, 'slug' => $post->slug]) }}</guid>
            <pubDate>{{ $post->published_at->format('r') }}</pubDate>
            @if($post->user)
            <author>{{ $post->user->email }} ({{ $post->user->name }})</author>
            @endif
            @if(trans_field($post, 'excerpt'))
            <description><![CDATA[{{ trans_field($post, 'excerpt') }}]]></description>
            @endif
            @if($post->featured_image)
            <enclosure url="{{ asset('storage/'.$post->featured_image) }}" type="image/jpeg" length="0"/>
            @endif
        </item>
        @endforeach
    </channel>
</rss>
