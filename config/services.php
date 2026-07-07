<?php

return [
    'ffmpeg' => [
        'path' => env('FFMPEG_PATH', 'ffmpeg'),
        'probe' => env('FFPROBE_PATH', (function () {
            $ffmpeg = env('FFMPEG_PATH', 'ffmpeg');
            if (str_ends_with($ffmpeg, 'ffmpeg.exe')) {
                return str_replace('ffmpeg.exe', 'ffprobe.exe', $ffmpeg);
            }
            if (str_ends_with($ffmpeg, 'ffmpeg')) {
                return str_replace('ffmpeg', 'ffprobe', $ffmpeg);
            }
            return 'ffprobe';
        })()),
    ],

    'cloudflare' => [
        'account_id' => env('CLOUDFLARE_ACCOUNT_ID'),
        'api_token' => env('CLOUDFLARE_API_TOKEN'),
    ],

    'libretranslate' => [
        'url' => env('LIBRETRANSLATE_URL', 'http://localhost:5000'),
    ],
];
