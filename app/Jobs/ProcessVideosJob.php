<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessVideosJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public function __construct(
        protected string $modelClass,
        protected int $modelId
    ) {}

    public function uniqueId(): string
    {
        return $this->modelClass . '-' . $this->modelId;
    }

    public function uniqueFor(): int
    {
        return 3600;
    }

    public function handle(): void
    {
        $model = $this->modelClass::find($this->modelId);
        if (!$model) return;

        $model->convertHevcVideos();
        $model->generateVideoThumbnails();

        $model->saveQuietly();
    }
}
