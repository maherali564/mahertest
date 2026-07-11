<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TranslationService
{
    protected string $driver;
    protected string $libreUrl;

    public function __construct()
    {
        $this->driver = config('services.translation.driver', 'libretranslate');
        $this->libreUrl = rtrim(config('services.libretranslate.url', 'http://localhost:5000'), '/');
    }

    public function translate(string $text, string $source, string $target): string
    {
        if (empty(trim($text)) || $source === $target) {
            return $text;
        }

        if (strlen($text) > 5000) {
            throw new \RuntimeException('Text too long for translation (max 5000 characters)');
        }

        return $this->driver === 'google'
            ? $this->translateGoogle($text, $source, $target)
            : $this->translateLibre($text, $source, $target);
    }

    protected function translateLibre(string $text, string $source, string $target): string
    {
        $response = Http::timeout(30)
            ->post("{$this->libreUrl}/translate", [
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

        return $response->json('translatedText') ?? $text;
    }

    protected function translateGoogle(string $text, string $source, string $target): string
    {
        $key = config('services.google_translate.key');
        $project = config('services.google_translate.project');

        if (empty($key) || empty($project)) {
            Log::warning('Google Translate not configured, falling back to LibreTranslate');
            return $this->translateLibre($text, $source, $target);
        }

        $map = [
            'ar' => 'ar', 'en' => 'en', 'es' => 'es',
            'id' => 'id', 'tr' => 'tr', 'sv' => 'sv',
        ];
        $sourceLang = $map[$source] ?? $source;
        $targetLang = $map[$target] ?? $target;

        $response = Http::timeout(15)
            ->withHeaders(['Authorization' => "Bearer {$key}"])
            ->post("https://translation.googleapis.com/v3/projects/{$project}:translateText", [
                'sourceLanguageCode' => $sourceLang,
                'targetLanguageCode' => $targetLang,
                'contents' => [$text],
                'mimeType' => 'text/plain',
            ]);

        if (!$response->successful()) {
            Log::error('Google Translate API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            Log::info('Falling back to LibreTranslate');
            return $this->translateLibre($text, $source, $target);
        }

        $translations = $response->json('translations');

        return $translations[0]['translatedText'] ?? $text;
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
