<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Services\BlogService;
use Illuminate\View\View;

class TagController extends Controller
{
    public function __construct(protected BlogService $blogService) {}

    public function index(string $locale): View
    {
        $tags = $this->blogService->getTagsWithPostCount();

        return view('posts.tags', compact('tags'));
    }

    public function show(string $locale, string $slug): View
    {
        [$tag, $posts] = $this->blogService->getPostsByTag($slug);

        return view('posts.index', compact('posts', 'tag'));
    }
}
