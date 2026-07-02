<?php

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/** Get a translated field from a translatable model, falling back to the app locale and then fallback locale. */
if (! function_exists('trans_field')) {
    function trans_field(?Model $model, string $field, ?string $locale = null): ?string
    {
        if (! $model) {
            return null;
        }

        $locale = $locale ?? app()->getLocale();

        if (in_array(HasTranslations::class, class_uses_recursive($model), true)) {
            return $model->getTranslation($field, $locale, false) ?: $model->getTranslation($field, config('app.fallback_locale'), false);
        }

        return $model->{$field} ?? null;
    }
}

/** Generate a URL prefixed with the given locale. */
if (! function_exists('locale_url')) {
    function locale_url(string $locale, string $path = ''): string
    {
        $path = ltrim($path, '/');

        return url('/'.$locale.($path ? '/'.$path : ''));
    }
}
