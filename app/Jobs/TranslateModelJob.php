<?php

namespace App\Jobs;

use App\Services\TranslationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TranslateModelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600;
    public $tries = 3;

    public function __construct(
        public $model,
        public array $fields,
        public ?array $targetLangs = null
    ) {}

    public function handle(TranslationService $translator): void
    {
        if (!$this->model || !$this->model->exists) {
            Log::warning('TranslateModelJob: Model not found', [
                'model' => $this->model ? get_class($this->model) : 'null',
                'id' => $this->model->id ?? null,
            ]);
            return;
        }

        if (empty($this->fields)) {
            Log::warning('TranslateModelJob: No fields to translate', [
                'model' => get_class($this->model),
                'id' => $this->model->id,
            ]);
            return;
        }

        $targetLangs = $this->targetLangs ?? config('app.supported_locales', ['ar', 'en', 'es']);

        try {
            $translator->translateModel($this->model, $this->fields, $targetLangs);

            Log::info('TranslateModelJob completed', [
                'model' => get_class($this->model),
                'id' => $this->model->id,
                'fields' => $this->fields,
                'target_langs' => $targetLangs,
            ]);
        } catch (\Exception $e) {
            Log::error('TranslateModelJob failed', [
                'model' => get_class($this->model),
                'id' => $this->model->id,
                'fields' => $this->fields,
                'target_langs' => $targetLangs,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('TranslateModelJob permanently failed', [
            'model' => $this->model ? get_class($this->model) : 'null',
            'id' => $this->model->id ?? null,
            'fields' => $this->fields,
            'error' => $exception->getMessage(),
        ]);
    }
}
