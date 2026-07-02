<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasTranslations;

    protected $casts = [
        'logos' => 'array',
        'supported_locales' => 'array',
    ];

    protected $fillable = [
        'site_name',
        'tagline',
        'hero_title',
        'hero_subtitle',
        'about_title',
        'about_content',
        'about_features',
        'donate_title',
        'donate_description',
        'donate_methods',
        'footer_description',
        'phone',
        'email',
        'whatsapp',
        'twitter',
        'facebook',
        'instagram',
        'linkedin',
        'youtube',
        'logo',
        'logos',
        'hero_image',
        'about_image',
        'supported_locales',
    ];

    public array $translatable = [
        'site_name',
        'tagline',
        'hero_title',
        'hero_subtitle',
        'about_title',
        'about_content',
        'about_features',
        'donate_title',
        'donate_description',
        'donate_methods',
        'footer_description',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate([]);
    }
}
