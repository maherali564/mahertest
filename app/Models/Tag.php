<?php

namespace App\Models;

use App\Models\Concerns\HasAutoTranslations;
use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasTranslations, HasAutoTranslations;

    public array $translatable = ['name'];

    protected $fillable = ['slug', 'name'];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}
