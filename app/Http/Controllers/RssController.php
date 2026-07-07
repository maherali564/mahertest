<?php

namespace App\Http\Controllers;

use App\Services\BlogService;
use Illuminate\Support\Facades\Response;

class RssController extends Controller
{
    public function __construct(protected BlogService $blogService) {}

    public function index()
    {
        $posts = $this->blogService->getPublishedPosts(50, null);
        $items = $posts->items();
        $locale = app()->getLocale();

        $content = view('rss', compact('items', 'locale'))->render();

        return Response::make($content, 200, [
            'Content-Type' => 'application/rss+xml; charset=utf-8',
        ]);
    }

    public function showLocale(string $locale)
    {
        $posts = $this->blogService->getPublishedPosts(50, null);
        $items = $posts->items();

        $content = view('rss', compact('items', 'locale'))->render();

        return Response::make($content, 200, [
            'Content-Type' => 'application/rss+xml; charset=utf-8',
        ]);
    }
}
