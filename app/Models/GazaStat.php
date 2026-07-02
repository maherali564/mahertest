<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class GazaStat extends Model
{
    use HasTranslations;

    protected $fillable = [
        'label', 'value', 'prefix', 'icon', 'sort_order', 'is_active',
    ];

    public array $translatable = ['label'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
