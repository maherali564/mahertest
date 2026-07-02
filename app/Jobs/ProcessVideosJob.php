<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessVideosJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected string $modelClass,
        protected int $modelId
    ) {}

    public function handle(): void
    {
        $model = $this->modelClass::find($this->modelId);
        if (!$model) return;

        $model->convertHevcVideos();
        $model->generateVideoThumbnails();

        $model->saveQuietly();
    }
}
