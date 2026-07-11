<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class MediaOptimizer
{
    public static function compressImage(string $path): bool
    {
        $disk = Storage::disk('public');
        if (!$disk->exists($path)) return false;

        $fullPath = $disk->path($path);
        $realPath = realpath($fullPath);
        $storagePath = realpath($disk->path(''));
        if ($realPath === false || !str_starts_with($realPath, $storagePath)) {
            Log::warning('MediaOptimizer: Invalid file path', ['path' => $path]);
            return false;
        }

        if (!in_array(exif_imagetype($fullPath), [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
            Log::warning('MediaOptimizer: Unsupported image type', ['path' => $path]);
            return false;
        }

        $info = pathinfo($fullPath);
        $ext = strtolower($info['extension']);

        $maxWidth = 1920;
        $maxHeight = 1080;

        if ($ext === 'jpg' || $ext === 'jpeg') {
            try {
                $img = imagecreatefromjpeg($fullPath);
            } catch (\Throwable $e) {
                Log::warning('MediaOptimizer: Failed to load JPEG', ['path' => $path, 'error' => $e->getMessage()]);
                return false;
            }
            if (!$img) return false;

            $w = imagesx($img);
            $h = imagesy($img);

            if ($w > $maxWidth || $h > $maxHeight) {
                $ratio = min($maxWidth / $w, $maxHeight / $h, 1);
                $nw = (int)($w * $ratio);
                $nh = (int)($h * $ratio);
                $resized = imagescale($img, $nw, $nh);
                if ($resized) {
                    imagedestroy($img);
                    $img = $resized;
                }
            }

            $tmp = $fullPath . '.tmp';
            $ok = imagejpeg($img, $tmp, 70);
            imagedestroy($img);
            if ($ok && filesize($tmp) < filesize($fullPath)) {
                rename($tmp, $fullPath);
                return true;
            }
            @unlink($tmp);
            return $ok;

        } elseif ($ext === 'png') {
            try {
                $img = imagecreatefrompng($fullPath);
            } catch (\Throwable $e) {
                Log::warning('MediaOptimizer: Failed to load PNG', ['path' => $path, 'error' => $e->getMessage()]);
                return false;
            }
            if (!$img) return false;

            imagesavealpha($img, true);
            imagealphablending($img, false);

            $w = imagesx($img);
            $h = imagesy($img);

            if ($w > $maxWidth || $h > $maxHeight) {
                $ratio = min($maxWidth / $w, $maxHeight / $h, 1);
                $nw = (int)($w * $ratio);
                $nh = (int)($h * $ratio);
                $resized = imagescale($img, $nw, $nh);
                if ($resized) {
                    imagedestroy($img);
                    $img = $resized;
                    imagesavealpha($img, true);
                    imagealphablending($img, false);
                }
            }

            $tmp = $fullPath . '.tmp';
            $ok = imagepng($img, $tmp, 7);
            imagedestroy($img);
            if ($ok && filesize($tmp) < filesize($fullPath)) {
                rename($tmp, $fullPath);
                return true;
            }
            @unlink($tmp);
            return $ok;
        }

        return false;
    }

    public static function compressVideo(string $path): bool
    {
        $disk = Storage::disk('public');
        if (!$disk->exists($path)) return false;

        $fullPath = $disk->path($path);
        $realPath = realpath($fullPath);
        $storagePath = realpath($disk->path(''));
        if ($realPath === false || !str_starts_with($realPath, $storagePath)) {
            return false;
        }
        $ffmpeg = config('services.ffmpeg.path', 'ffmpeg');
        $allowed = ['ffmpeg', '/usr/bin/ffmpeg', '/usr/local/bin/ffmpeg'];
        if ($ffmpeg !== 'ffmpeg' && !in_array($ffmpeg, $allowed, true)) return false;

        $tmp = $fullPath . '.tmp.mp4';

        $cmd = sprintf(
            '"%s" -i "%s" -c:v libx264 -crf 23 -preset medium -c:a aac -b:a 96k -movflags +faststart -y "%s" 2>&1',
            $ffmpeg,
            $fullPath,
            $tmp
        );

        $result = Process::timeout(300)->run($cmd);

        if ($result->successful() && file_exists($tmp) && filesize($tmp) > 1024) {
            if (filesize($tmp) < filesize($fullPath)) {
                $backup = $fullPath . '.bak';
                rename($fullPath, $backup);
                if (rename($tmp, $fullPath)) {
                    @unlink($backup);
                    return true;
                }
                rename($backup, $fullPath);
            }
            @unlink($tmp);
        }

        return false;
    }

    public static function saveUploadedImage(string $directory): \Closure
    {
        return function ($file) use ($directory) {
            $path = $file->store($directory, 'public');
            static::compressImage($path);
            return $path;
        };
    }
}
