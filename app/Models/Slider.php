<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title', 'subtitle', 'image', 'button_text', 'button_link',
        'text_color', 'text_position', 'button_color', 'button_text_color', 'overlay_opacity',
        'sort_order', 'is_active',
    ];

    public array $translatable = ['title', 'subtitle', 'button_text'];

    protected $casts = [
        'is_active' => 'boolean',
        'overlay_opacity' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
