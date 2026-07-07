<?php

namespace App\Models;

use App\Models\Concerns\HasAutoTranslations;
use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasTranslations, HasAutoTranslations;

    public array $translatable = ['name', 'description'];

    protected $fillable = ['slug', 'name', 'description', 'color', 'parent_id'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
