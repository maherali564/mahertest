<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class MediaOptimizer
{
    public static function compressImage(string $path): bool
    {
        $disk = Storage::disk('public');
        if (!$disk->exists($path)) return false;

        $fullPath = $disk->path($path);
        $info = pathinfo($fullPath);
        $ext = strtolower($info['extension']);

        $maxWidth = 1920;
        $maxHeight = 1080;

        if ($ext === 'jpg' || $ext === 'jpeg') {
            $img = @imagecreatefromjpeg($fullPath);
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
            $img = @imagecreatefrompng($fullPath);
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
        $ffmpeg = config('services.ffmpeg.path', 'ffmpeg');

        $tmp = $fullPath . '.tmp.mp4';

        $cmd = sprintf(
            '"%s" -i "%s" -c:v libx264 -crf 28 -preset medium -c:a aac -b:a 96k -movflags +faststart -y "%s" 2>&1',
            $ffmpeg,
            $fullPath,
            $tmp
        );

        exec($cmd, $output, $code);

        if ($code === 0 && file_exists($tmp) && filesize($tmp) > 1024) {
            if (filesize($tmp) < filesize($fullPath)) {
                rename($tmp, $fullPath);
                return true;
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
