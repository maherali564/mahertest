<?php

namespace App\Filament\Concerns;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;

trait HasLocaleTabs
{
    /** Generate a Section + Tabs with per-locale inputs for a translatable field. */
    protected static function localeTabs(string $field, string $label, string $type = 'text'): Section
    {
        $locales = [
            'ar' => ['label' => 'العربية', 'dir' => 'rtl'],
            'en' => ['label' => 'English', 'dir' => 'ltr'],
            'es' => ['label' => 'Español', 'dir' => 'ltr'],
            'id' => ['label' => 'Bahasa Indonesia', 'dir' => 'ltr'],
            'tr' => ['label' => 'Türkçe', 'dir' => 'ltr'],
            'sv' => ['label' => 'Svenska', 'dir' => 'ltr'],
        ];

        $tabs = Tabs::make($field.'_tabs')
            ->tabs(collect($locales)->map(function ($meta, $locale) use ($field, $type) {
                $name = "{$field}.{$locale}";

                $input = match ($type) {
                    'textarea' => Textarea::make($name)->label($meta['label'])->rows(4)->extraAttributes(['dir' => $meta['dir']]),
                    'richtext' => RichEditor::make($name)->label($meta['label']),
                    default => TextInput::make($name)->label($meta['label'])->extraAttributes(['dir' => $meta['dir']]),
                };

                return Tab::make($locale)->label($meta['label'])->schema([$input]);
            })->values()->all());

        return Section::make($label)->schema([$tabs])->compact();
    }
}
