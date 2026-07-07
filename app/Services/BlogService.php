<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BlogService
{
    public function getPublishedPosts(int $perPage = 12, ?string $search = null): LengthAwarePaginator
    {
        $query = Post::published()
            ->with(['category', 'user'])
            ->orderBy('published_at', 'desc');

        if ($search) {
            $locale = app()->getLocale();
            $fallbackLocale = config('app.fallback_locale', 'en');
            $driver = \DB::getDriverName();
            $operator = $driver === 'pgsql' ? 'ilike' : 'like';
            $searchTerm = "%{$search}%";

            $query->where(function (Builder $q) use ($searchTerm, $locale, $operator) {
                $q->where("title->{$locale}", $operator, $searchTerm);
                $q->orWhere("content->{$locale}", $operator, $searchTerm);
                $q->orWhere("excerpt->{$locale}", $operator, $searchTerm);
            });

            if ($fallbackLocale !== $locale) {
                $query->orWhere(function (Builder $q) use ($searchTerm, $fallbackLocale, $operator) {
                    $q->where("title->{$fallbackLocale}", $operator, $searchTerm);
                    $q->orWhere("content->{$fallbackLocale}", $operator, $searchTerm);
                    $q->orWhere("excerpt->{$fallbackLocale}", $operator, $searchTerm);
                });
            }
        }

        return $query->paginate($perPage);
    }

    public function findPublishedBySlug(string $slug): Post
    {
        $post = Post::published()
            ->with(['category', 'user', 'tags'])
            ->where('slug', $slug)
            ->firstOrFail();

        $post->increment('views');

        return $post;
    }

    public function getRelatedPosts(Post $post, int $limit = 3)
    {
        return Post::published()
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->take($limit)
            ->get();
    }

    public function getPostsByCategory(string $slug, int $perPage = 12): array
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $posts = Post::published()
            ->with(['category', 'user'])
            ->where('category_id', $category->id)
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);

        return [$category, $posts];
    }

    public function getPostsByTag(string $slug, int $perPage = 12): array
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $posts = $tag->posts()
            ->published()
            ->with(['category', 'user'])
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);

        return [$tag, $posts];
    }

    public function getCategoriesWithPostCount()
    {
        return Category::withCount('posts')
            ->orderBy('slug')
            ->get();
    }

    public function getTagsWithPostCount()
    {
        return Tag::withCount('posts')
            ->orderBy('slug')
            ->get();
    }

    public function getLatestPosts(int $limit = 3)
    {
        return Post::published()
            ->with(['category', 'user'])
            ->orderBy('published_at', 'desc')
            ->take($limit)
            ->get();
    }
}
