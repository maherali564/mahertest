<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Jobs\ProcessVideosJob;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class Story extends Model
{
    use HasTranslations;

    protected $fillable = [
        'slug', 'title', 'content', 'excerpt', 'person_name', 'age',
        'location', 'image', 'images', 'video_url', 'video_type', 'videos', 'video_thumbnails', 'goal_amount', 'raised_amount',
        'is_active', 'sort_order',
    ];

    public array $translatable = ['title', 'content', 'excerpt', 'person_name', 'location'];

    protected $casts = [
        'is_active' => 'boolean',
        'images' => 'array',
        'videos' => 'array',
        'video_thumbnails' => 'array',
        'goal_amount' => 'decimal:2',
        'raised_amount' => 'decimal:2',
    ];

    public function progressPercent(): float
    {
        if ($this->goal_amount <= 0) return 0;
        return min(100, round(($this->raised_amount / $this->goal_amount) * 100, 1));
    }

    public function getFirstImageAttribute(): ?string
    {
        if ($this->image) {
            return $this->image;
        }
        if ($this->images && count($this->images) > 0) {
            return $this->images[0];
        }
        return null;
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    protected static function booted(): void
    {
        static::saved(function (Story $story) {
            $videoChanged = $story->wasChanged('videos') || $story->wasChanged('video_url');
            $hasVideos = !empty($story->videos) || $story->video_url;

            if ($videoChanged && $hasVideos) {
                ProcessVideosJob::dispatch(Story::class, $story->id);
            }
        });
    }

    public static function ffmpegPath(): ?string
    {
        $path = config('services.ffmpeg.path', 'ffmpeg');
        $allowed = ['ffmpeg', '/usr/bin/ffmpeg', '/usr/local/bin/ffmpeg'];
        if ($path !== 'ffmpeg' && !in_array($path, $allowed, true)) return null;
        if ($path !== 'ffmpeg') return escapeshellarg($path);
        $result = Process::timeout(10)->run('ffmpeg -version 2>&1');
        return $result->successful() ? 'ffmpeg' : null;
    }

    public function convertHevcVideos(): void
    {
        $ffmpeg = self::ffmpegPath();
        if (!$ffmpeg || empty($this->videos)) return;

        $converted = [];
        foreach ($this->videos as $video) {
            $videoPath = Storage::disk('public')->path($video);
            if (!file_exists($videoPath)) { $converted[] = $video; continue; }

            $probePath = escapeshellarg(config('services.ffmpeg.probe', 'ffprobe'));
            $ffprobe = Process::timeout(30)->run("$probePath -v error -select_streams v:0 -show_entries stream=codec_name -of default=noprint_wrappers=1:nokey=1 " . escapeshellarg($videoPath) . ' 2>&1');
            $codec = trim($ffprobe->output());
            $isHevc = str_contains(strtolower($codec), 'hevc');
            if (!$isHevc && empty($codec) && $ffmpeg) {
                $detect = Process::timeout(30)->run("$ffmpeg -i " . escapeshellarg($videoPath) . ' 2>&1');
                $isHevc = str_contains(strtolower($detect->output()), 'hevc');
            }
            if (!$isHevc) { $converted[] = $video; continue; }

            $newName = pathinfo($video, PATHINFO_DIRNAME) . '/' . pathinfo($video, PATHINFO_FILENAME) . '_h264.mp4';
            $newPath = Storage::disk('public')->path($newName);
            $dir = dirname($newPath);
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            $conv = Process::timeout(300)->run("$ffmpeg -i " . escapeshellarg($videoPath) . " -c:v libx264 -crf 23 -preset medium -b:v 2M -maxrate 2.5M -bufsize 5M -c:a aac -b:a 96k -movflags +faststart " . escapeshellarg($newPath) . " 2>&1");
            if ($conv->successful()) {
                Storage::disk('public')->delete($video);
                $converted[] = $newName;
            } else {
                $converted[] = $video;
            }
        }
        $this->videos = $converted;
    }

    public function generateVideoThumbnails(): void
    {
        $ffmpeg = self::ffmpegPath();
        $videos = $this->videos ?? [];
        $thumbnails = $this->video_thumbnails ?? [];
        $changed = false;

        if (empty($videos) && $this->video_url && !str_starts_with($this->video_url, 'http')) {
            $videos = [$this->video_url];
        }

        foreach ($videos as $video) {
            if (str_starts_with($video, 'http')) continue;
            if (isset($thumbnails[$video]) && Storage::disk('public')->exists($thumbnails[$video])) continue;

            $videoPath = Storage::disk('public')->path($video);
            if (!file_exists($videoPath)) continue;

            $thumbName = pathinfo($video, PATHINFO_DIRNAME) . '/' . pathinfo($video, PATHINFO_FILENAME) . '_thumb.jpg';
            $thumbPath = Storage::disk('public')->path($thumbName);
            $dir = dirname($thumbPath);
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            if (file_exists($thumbPath)) {
                $thumbnails[$video] = $thumbName;
                $changed = true;
                continue;
            }
            if (!$ffmpeg) continue;

            $thumb = Process::timeout(120)->run("$ffmpeg -i " . escapeshellarg($videoPath) . ' -ss 00:00:00.5 -vframes 1 -q:v 3 ' . escapeshellarg($thumbPath) . ' 2>&1');
            if ($thumb->successful()) {
                $thumbnails[$video] = $thumbName;
                $changed = true;
            } else {
                Log::warning('Story thumbnail generation failed', ['story_id' => $this->id, 'video' => $video]);
            }
        }

        if ($changed) {
            $this->video_thumbnails = $thumbnails;
        }
    }
}
