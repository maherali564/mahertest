<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(6);

        return [
            'slug' => Str::slug($title),
            'title' => ['en' => $title, 'ar' => $title],
            'content' => ['en' => '<p>'.fake()->paragraphs(3, true).'</p>', 'ar' => '<p>'.fake()->paragraphs(3, true).'</p>'],
            'excerpt' => ['en' => fake()->sentence(), 'ar' => fake()->sentence()],
            'featured_image' => null,
            'status' => 'published',
            'published_at' => now()->subHours(rand(1, 720)),
            'is_featured' => fake()->boolean(20),
            'views' => fake()->numberBetween(0, 500),
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }
}
