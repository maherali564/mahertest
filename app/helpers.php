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

        $supportedLocales = config('app.supported_locales', ['ar', 'en']);
        if (!in_array($locale, $supportedLocales)) {
            $locale = config('app.fallback_locale', 'en');
        }

        if (in_array(HasTranslations::class, class_uses_recursive($model), true)) {
            return $model->getTranslation($field, $locale, false) ?: $model->getTranslation($field, config('app.fallback_locale'), false);
        }

        return $model->{$field} ?? null;
    }
}

/** Strip unsafe HTML tags and attributes, allowing only a safe subset. */
if (! function_exists('safe_html')) {
    function safe_html(?string $html): string
    {
        if (! $html) {
            return '';
        }

        if (class_exists(\HTMLPurifier::class)) {
            $config = \HTMLPurifier_Config::createDefault();
            $config->set('HTML.Allowed', 'p,a[href|title],b,i,u,strong,em,h1,h2,h3,h4,h5,h6,ul,ol,li,br,img[src|alt|width|height],blockquote,pre,code,figure,figcaption,span,div,hr,table,thead,tbody,tr,th,td,sup,sub,del,ins');
            $config->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%');
            $config->set('HTML.TargetBlank', true);
            $config->set('HTML.Nofollow', true);
            $purifier = new \HTMLPurifier($config);
            return $purifier->purify($html);
        }

        $allowed = '<p><a><b><i><u><strong><em><h1><h2><h3><h4><h5><h6><ul><ol><li><br><img><blockquote><pre><code><figure><figcaption><span><div><hr><table><thead><tbody><tr><th><td><sup><sub><del><ins><hr>';

        $html = preg_replace('/\s+on\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]*)/i', '', $html);
        $html = preg_replace('/href\s*=\s*["\']javascript:[^"\']*["\']/i', 'href="#', $html);
        $html = preg_replace('/src\s*=\s*["\']javascript:[^"\']*["\']/i', 'src=""', $html);

        return strip_tags($html, $allowed);
    }
}

/** Generate a URL prefixed with the given locale. */
if (! function_exists('locale_url')) {
    function locale_url(string $locale, string $path = ''): string
    {
        $supportedLocales = config('app.supported_locales', ['ar', 'en']);
        if (!in_array($locale, $supportedLocales)) {
            $locale = config('app.fallback_locale', 'en');
        }

        $path = ltrim($path, '/');

        return url('/'.$locale.($path ? '/'.$path : ''));
    }
}
