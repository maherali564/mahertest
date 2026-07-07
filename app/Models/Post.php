<?php

namespace App\Models;

use App\Models\Concerns\HasAutoTranslations;
use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasTranslations, HasAutoTranslations, SoftDeletes;

    public array $translatable = [
        'title', 'content', 'excerpt', 'meta_title', 'meta_description', 'meta_keywords',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    protected $fillable = [
        'slug', 'title', 'content', 'excerpt', 'featured_image',
        'images', 'video_url', 'video_type',
        'status', 'published_at', 'is_featured', 'views',
        'user_id', 'category_id',
        'meta_title', 'meta_description', 'meta_keywords',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getReadingTimeAttribute()
    {
        $words = str_word_count(strip_tags($this->getTranslation('content', 'ar') ?? ''));
        return max(1, ceil($words / 200));
    }
}
