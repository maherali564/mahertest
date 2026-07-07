<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Category $category;
    protected Tag $tag;
    protected Post $post;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->category = Category::factory()->create();
        $this->tag = Tag::factory()->create();

        $this->post = Post::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
        ]);

        $this->post->tags()->attach($this->tag);
    }

    public function test_lists_published_posts_on_blog_index()
    {
        Post::factory()->draft()->create();

        $response = $this->get(route('posts.index', 'en'));

        $response->assertOk();
        $response->assertSee($this->post->getTranslation('title', 'en'));
    }

    public function test_shows_a_single_post()
    {
        $response = $this->get(route('posts.show', ['locale' => 'en', 'slug' => $this->post->slug]));

        $response->assertOk();
        $response->assertSee($this->post->getTranslation('title', 'en'));
    }

    public function test_filters_posts_by_category()
    {
        $response = $this->get(route('posts.category', ['locale' => 'en', 'slug' => $this->category->slug]));

        $response->assertOk();
        $response->assertSee($this->post->getTranslation('title', 'en'));
    }

    public function test_filters_posts_by_tag()
    {
        $response = $this->get(route('posts.tag', ['locale' => 'en', 'slug' => $this->tag->slug]));

        $response->assertOk();
        $response->assertSee($this->post->getTranslation('title', 'en'));
    }

    public function test_returns_404_for_unpublished_post()
    {
        $draft = Post::factory()->draft()->create();

        $response = $this->get(route('posts.show', ['locale' => 'en', 'slug' => $draft->slug]));

        $response->assertNotFound();
    }

    public function test_lists_categories_on_categories_page()
    {
        $response = $this->get(route('posts.categories', 'en'));

        $response->assertOk();
        $response->assertSee($this->category->getTranslation('name', 'en'));
    }

    public function test_lists_tags_on_tags_page()
    {
        $response = $this->get(route('posts.tags', 'en'));

        $response->assertOk();
        $response->assertSee($this->tag->getTranslation('name', 'en'));
    }

    public function test_returns_valid_sitemap_with_blog_posts()
    {
        $response = $this->get(route('sitemap'));

        $response->assertOk();
        $response->assertSee($this->post->slug);
    }

    public function test_returns_valid_rss_feed()
    {
        $response = $this->get(route('rss'));

        $response->assertOk();
        $response->assertSee($this->post->getTranslation('title', 'en'));
    }

    public function test_shows_homepage_with_latest_blog_posts()
    {
        $response = $this->get(route('home', 'en'));

        $response->assertOk();
        $response->assertSee($this->post->getTranslation('title', 'en'));
    }
}
