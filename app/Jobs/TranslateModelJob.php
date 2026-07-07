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
        try {
            $translator->translateModel($this->model, $this->fields, $this->targetLangs);
        } catch (\Exception $e) {
            Log::error('Translation Job failed for '.get_class($this->model).' ID '.$this->model->id.': '.$e->getMessage());
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Translation Job failed permanently for '.get_class($this->model).' ID '.$this->model->id.': '.$exception->getMessage());
    }
}
