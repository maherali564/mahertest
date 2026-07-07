<?php

namespace App\Filament\Concerns;

use App\Services\TranslationService;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

trait HasTranslateAll
{
    protected function getTranslateAllAction(): Action
    {
        $locales = [
            'ar' => 'العربية',
            'en' => 'English',
            'es' => 'Español',
            'id' => 'Bahasa Indonesia',
            'tr' => 'Türkçe',
            'sv' => 'Svenska',
        ];

        return Action::make('translate_all')
            ->label(__(key: 'Translate All'))
            ->icon('heroicon-o-language')
            ->modalHeading(__('Translate All Fields'))
            ->form([
                Select::make('source_locale')
                    ->label(__('Source Language'))
                    ->options($locales)
                    ->default('ar')
                    ->required(),
            ])
            ->action(function (array $data) use ($locales) {
                $source = $data['source_locale'];
                $modelClass = $this->getModel();
                $model = new $modelClass;
                $translatable = $model->translatable ?? [];

                if (empty($translatable)) {
                    Notification::make()
                        ->warning()
                        ->title(__('No translatable fields found on this model'))
                        ->send();
                    return;
                }

                $service = app(TranslationService::class);
                $translated = 0;

                foreach ($translatable as $field) {
                    $sourceText = $this->data[$field][$source] ?? '';
                    if (empty($sourceText)) continue;

                    foreach ($locales as $locale => $label) {
                        if ($locale === $source) continue;
                        if (!empty($this->data[$field][$locale] ?? '')) continue;

                        try {
                            $translatedText = $service->translate($sourceText, $source, $locale);
                            $this->data[$field][$locale] = $translatedText;
                            $translated++;
                        } catch (\Exception $e) {
                            Log::error('Translation failed', [
                                'field' => $field,
                                'from' => $source,
                                'to' => $locale,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }
                }

                Notification::make()
                    ->success()
                    ->title(__('Translation complete'))
                    ->body(__(':count fields translated', ['count' => $translated]))
                    ->send();
            });
    }
}
