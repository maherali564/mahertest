<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Console\Command;

class TranslateAllPostsCommand extends Command
{
    protected $signature = 'posts:translate {--force : Overwrite existing translations}';
    protected $description = 'Translate all posts, categories, and tags to all supported languages';

    public function handle(): void
    {
        $models = [
            'posts' => Post::all(),
            'categories' => Category::all(),
            'tags' => Tag::all(),
        ];

        foreach ($models as $name => $collection) {
            $this->info("Translating {$collection->count()} {$name}...");
            foreach ($collection as $model) {
                $model->autoTranslate();
            }
        }

        $this->info('All translations dispatched to queue!');
    }
}
