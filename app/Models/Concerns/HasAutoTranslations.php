<?php

namespace App\Models\Concerns;

use App\Jobs\TranslateModelJob;

trait HasAutoTranslations
{
    public function autoTranslate(?array $fields = null, ?array $targetLangs = null): void
    {
        $fields = $fields ?? $this->translatable ?? [];
        $targetLangs = $targetLangs ?? static::supportedLocales();

        dispatch(new TranslateModelJob($this, $fields, $targetLangs));
    }
}
