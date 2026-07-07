<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TranslationService
{
    protected string $url;

    public function __construct()
    {
        $this->url = rtrim(config('services.libretranslate.url', 'http://localhost:5000'), '/');
    }

    public function translate(string $text, string $source, string $target): string
    {
        if (empty(trim($text)) || $source === $target) {
            return $text;
        }

        $response = Http::timeout(30)
            ->post("{$this->url}/translate", [
                'q' => $text,
                'source' => $source,
                'target' => $target,
                'format' => 'text',
            ]);

        if ($response->failed()) {
            Log::error('LibreTranslate API error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'source' => $source,
                'target' => $target,
            ]);

            throw new \RuntimeException(
                __('Translation service error: :status', ['status' => $response->status()])
            );
        }

        $result = $response->json();

        return $result['translatedText'] ?? $text;
    }

    public function translateModel($model, array $fields, ?array $targetLangs = null): void
    {
        $targetLangs = $targetLangs ?? config('app.supported_locales', ['ar', 'en', 'es']);
        $sourceLang = 'ar';

        foreach ($targetLangs as $lang) {
            if ($lang === $sourceLang) {
                continue;
            }

            foreach ($fields as $field) {
                $text = $model->getTranslation($field, $sourceLang);

                if (!$text || $model->hasTranslation($field, $lang)) {
                    continue;
                }

                try {
                    $translated = $this->translate($text, $sourceLang, $lang);
                    $model->setTranslation($field, $lang, $translated);
                } catch (\Exception $e) {
                    Log::error('Translation failed for '.get_class($model).' ID '.$model->id.' field '.$field.' to '.$lang.': '.$e->getMessage());
                }
            }
        }

        $model->save();
    }
}
