<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TranslateAllPostsCommand extends Command
{
    protected $signature = 'posts:translate {--force : Overwrite existing translations}';
    protected $description = 'Translate all posts, categories, and tags to all supported languages';

    public function handle(): void
    {
        $force = $this->option('force');

        $models = [
            'posts' => Post::query(),
            'categories' => Category::query(),
            'tags' => Tag::query(),
        ];

        foreach ($models as $name => $query) {
            $count = $query->count();
            $this->info("Translating {$count} {$name}...");
            $query->chunk(100, function ($records) use ($name, $force) {
                foreach ($records as $model) {
                    try {
                        $model->autoTranslate();
                    } catch (\Exception $e) {
                        Log::error("Failed to translate {$name} record", [
                            'model_id' => $model->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
                sleep(1);
            });
        }

        $this->info('All translations dispatched to queue!');
    }
}
