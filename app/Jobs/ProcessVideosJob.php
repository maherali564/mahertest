<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessVideosJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public $timeout = 600;
    public $tries = 3;

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
        return 600;
    }

    public function handle(): void
    {
        if (!class_exists($this->modelClass)) {
            Log::error('ProcessVideosJob: Model class not found', [
                'class' => $this->modelClass,
                'id' => $this->modelId,
            ]);
            return;
        }

        try {
            $model = $this->modelClass::find($this->modelId);
            if (!$model) {
                Log::warning('ProcessVideosJob: Model not found', [
                    'class' => $this->modelClass,
                    'id' => $this->modelId,
                ]);
                return;
            }

            try {
                $model->convertHevcVideos();
            } catch (\Exception $e) {
                Log::error('ProcessVideosJob: Video conversion failed', [
                    'class' => $this->modelClass,
                    'id' => $this->modelId,
                    'error' => $e->getMessage(),
                ]);
            }

            try {
                $model->generateVideoThumbnails();
            } catch (\Exception $e) {
                Log::error('ProcessVideosJob: Thumbnail generation failed', [
                    'class' => $this->modelClass,
                    'id' => $this->modelId,
                    'error' => $e->getMessage(),
                ]);
            }

            if ($model->isDirty()) {
                try {
                    $model->save();
                } catch (\Exception $e) {
                    Log::error('ProcessVideosJob: Failed to save model', [
                        'class' => $this->modelClass,
                        'id' => $this->modelId,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            Log::info('ProcessVideosJob: Completed successfully', [
                'class' => $this->modelClass,
                'id' => $this->modelId,
            ]);

        } catch (\Exception $e) {
            Log::error('ProcessVideosJob: Unexpected error', [
                'class' => $this->modelClass,
                'id' => $this->modelId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('ProcessVideosJob: Failed permanently', [
            'class' => $this->modelClass,
            'id' => $this->modelId,
            'error' => $exception->getMessage(),
        ]);
    }
}
