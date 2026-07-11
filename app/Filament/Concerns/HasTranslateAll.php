<?php

namespace App\Filament\Concerns;

use App\Services\TranslationService;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Cache;
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
            ->visible(fn() => auth()->user()?->can('update_' . $this->getResource()::getPermissionSlug()))
            ->action(function (array $data) use ($locales) {
                $user = auth()->user();
                $cacheKey = 'translate_all_daily_' . ($user?->id ?? 'guest') . '_' . now()->toDateString();
                $dailyCount = (int) Cache::get($cacheKey, 0);
                $dailyLimit = 50;
                if ($dailyCount >= $dailyLimit) {
                    Notification::make()
                        ->danger()
                        ->title(__('Daily translation limit reached'))
                        ->body(__('You can translate up to :count fields per day.', ['count' => $dailyLimit]))
                        ->send();
                    return;
                }

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
                    if ($dailyCount + $translated >= $dailyLimit) break;
                    $sourceText = $this->data[$field][$source] ?? '';
                    if (empty($sourceText)) continue;

                    foreach ($locales as $locale => $label) {
                        if ($locale === $source) continue;
                        if (!empty($this->data[$field][$locale] ?? '')) continue;

                        try {
                            $translatedText = $service->translate($sourceText, $source, $locale);
                            $this->data[$field][$locale] = $translatedText;
                            $translated++;
                            Cache::increment($cacheKey);
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
