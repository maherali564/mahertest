<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Services\BlogService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(protected BlogService $blogService) {}

    public function index(Request $request): View
    {
        $search = $request->query('q');
        $posts = $this->blogService->getPublishedPosts(12, $search);

        return view('posts.index', compact('posts', 'search'));
    }

    public function show(string $locale, string $slug): View
    {
        $post = $this->blogService->findPublishedBySlug($slug);
        $relatedPosts = $this->blogService->getRelatedPosts($post);

        return view('posts.show', compact('post', 'relatedPosts'));
    }
}
