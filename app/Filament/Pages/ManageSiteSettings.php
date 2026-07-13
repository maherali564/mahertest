<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasLocaleTabs;
use App\Models\SiteSetting;
use App\Services\MediaOptimizer;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;

class ManageSiteSettings extends Page implements HasForms
{
    use HasLocaleTabs;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && $user->can('manage_settings');
    }

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.manage-site-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(SiteSetting::current()->toArray());
    }

    public function form(Form $form): Form
    {
        $locales = [
            'ar' => ['label' => 'العربية', 'dir' => 'rtl'],
            'en' => ['label' => 'English', 'dir' => 'ltr'],
            'es' => ['label' => 'Español', 'dir' => 'ltr'],
            'id' => ['label' => 'Bahasa Indonesia', 'dir' => 'ltr'],
            'tr' => ['label' => 'Türkçe', 'dir' => 'ltr'],
            'sv' => ['label' => 'Svenska', 'dir' => 'ltr'],
        ];

        $lbl = fn(string $key) => __("filament.pages.manage_site_settings.{$key}");

        return $form
            ->schema([

                Tabs::make('translations')
                    ->label(__('filament.pages.manage_site_settings.translatable_content'))
                    ->columnSpanFull()
                    ->tabs(collect($locales)->map(fn($meta, $locale) => Tab::make($locale)
                        ->label($meta['label'])
                        ->extraAttributes(['dir' => $meta['dir']])
                        ->schema([
                            TextInput::make("site_name.{$locale}")
                                ->label($lbl('site_name'))
                                ->required($locale === 'ar')
                                ->maxLength(255)
                                ->extraAttributes(['dir' => $meta['dir']]),
                            TextInput::make("tagline.{$locale}")
                                ->label($lbl('tagline'))
                                ->maxLength(255)
                                ->extraAttributes(['dir' => $meta['dir']]),
                            TextInput::make("hero_title.{$locale}")
                                ->label($lbl('hero_title'))
                                ->maxLength(255)
                                ->extraAttributes(['dir' => $meta['dir']]),
                            Textarea::make("hero_subtitle.{$locale}")
                                ->label($lbl('hero_subtitle'))
                                ->rows(3)
                                ->extraAttributes(['dir' => $meta['dir']]),
                            TextInput::make("about_title.{$locale}")
                                ->label($lbl('about_title'))
                                ->maxLength(255)
                                ->extraAttributes(['dir' => $meta['dir']]),
                            Textarea::make("about_content.{$locale}")
                                ->label($lbl('about_content'))
                                ->rows(4)
                                ->extraAttributes(['dir' => $meta['dir']]),
                            TextInput::make("donate_title.{$locale}")
                                ->label($lbl('donate_title'))
                                ->maxLength(255)
                                ->extraAttributes(['dir' => $meta['dir']]),
                            Textarea::make("donate_description.{$locale}")
                                ->label($lbl('donate_description'))
                                ->rows(3)
                                ->extraAttributes(['dir' => $meta['dir']]),
                            Textarea::make("footer_description.{$locale}")
                                ->label($lbl('footer_description'))
                                ->rows(3)
                                ->extraAttributes(['dir' => $meta['dir']]),
                        ])
                    )->values()->all()),

                Section::make($lbl('section.media'))
                    ->schema([
                        FileUpload::make('hero_image')
                            ->label($lbl('hero_image'))
                            ->image()
                            ->directory('site')
                            ->visibility('public')
                            ->nullable()
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->rules(['mimes:jpeg,png,webp', 'max:2048'])
                            ->saveUploadedFileUsing(MediaOptimizer::saveUploadedImage('site'))
                            ->afterStateHydrated(function (FileUpload $component, $state) {
                                if (is_string($state) && filled($state)) {
                                    $component->state([$state]);
                                }
                            })
                            ->removeUploadedFileButtonPosition('right'),
                        FileUpload::make('about_image')
                            ->label($lbl('about_image'))
                            ->image()
                            ->directory('site')
                            ->visibility('public')
                            ->nullable()
                            ->saveUploadedFileUsing(MediaOptimizer::saveUploadedImage('site'))
                            ->afterStateHydrated(function (FileUpload $component, $state) {
                                if (is_string($state) && filled($state)) {
                                    $component->state([$state]);
                                }
                            })
                            ->removeUploadedFileButtonPosition('right'),
                        FileUpload::make('logo')
                            ->label($lbl('default_logo'))
                            ->helperText(__('filament.pages.manage_site_settings.default_logo_help'))
                            ->image()
                            ->directory('site')
                            ->visibility('public')
                            ->nullable()
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->rules(['mimes:jpeg,png,webp', 'max:2048'])
                            ->saveUploadedFileUsing(MediaOptimizer::saveUploadedImage('site'))
                            ->afterStateHydrated(function (FileUpload $component, $state) {
                                if (is_string($state) && filled($state)) {
                                    $component->state([$state]);
                                }
                            })
                            ->removeUploadedFileButtonPosition('right'),
                    ])->columns(2),

                Section::make($lbl('section.per_language_logos'))
                    ->schema(
                        collect($locales)->map(fn($meta, $locale) =>
                            FileUpload::make("logos.{$locale}")
                                ->label($meta['label'])
                                ->image()
                                ->directory('site/logos')
                                ->visibility('public')
                                ->nullable()
                                ->saveUploadedFileUsing(MediaOptimizer::saveUploadedImage('site/logos'))
                                ->afterStateHydrated(function (FileUpload $component, $state) {
                                    if (is_string($state) && filled($state)) $component->state([$state]);
                                })
                                ->removeUploadedFileButtonPosition('right')
                        )->values()->all()
                    )->columns(3),

                Section::make($lbl('section.contact'))
                    ->schema([
                        TextInput::make('phone')->label($lbl('phone'))->tel(),
                        TextInput::make('email')->label($lbl('email'))->email(),
                        TextInput::make('whatsapp')->label($lbl('whatsapp')),
                        TextInput::make('twitter')->label($lbl('twitter')),
                        TextInput::make('facebook')->label($lbl('facebook')),
                        TextInput::make('instagram')->label($lbl('instagram')),
                        TextInput::make('linkedin')->label($lbl('linkedin')),
                        TextInput::make('youtube')->label($lbl('youtube')),
                    ])->columns(3),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $setting = SiteSetting::current();
        $old = $setting->replicate();

        $data = $this->form->getState();
        $setting->update($data);

        $imageFields = ['hero_image', 'logo', 'about_image'];
        foreach ($imageFields as $field) {
            $oldPath = $old->{$field};
            $newPath = $data[$field] ?? null;
            if ($oldPath && $oldPath !== $newPath) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        $oldLogos = $old->logos ?? [];
        $newLogos = $data['logos'] ?? [];
        foreach ($oldLogos as $locale => $path) {
            if ($path && (!isset($newLogos[$locale]) || $newLogos[$locale] !== $path)) {
                Storage::disk('public')->delete($path);
            }
        }

        Notification::make()
            ->title(__('filament.pages.manage_site_settings.saved'))
            ->success()
            ->send();
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.pages.manage_site_settings.navigation_label');
    }

    public function getTitle(): string
    {
        return __('filament.pages.manage_site_settings.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('filament.nav.groups.settings');
    }
}
