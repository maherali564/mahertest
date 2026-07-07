<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Services\BlogService;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(protected BlogService $blogService) {}

    public function index(string $locale): View
    {
        $categories = $this->blogService->getCategoriesWithPostCount();

        return view('posts.categories', compact('categories'));
    }

    public function show(string $locale, string $slug): View
    {
        [$category, $posts] = $this->blogService->getPostsByCategory($slug);

        return view('posts.index', compact('posts', 'category'));
    }
}
