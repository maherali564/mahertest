<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title', 'content', 'person_name', 'age',
        'location', 'image', 'images', 'video_url', 'video_type', 'videos', 'goal_amount', 'raised_amount',
        'is_active', 'sort_order',
    ];

    public array $translatable = ['title', 'content', 'person_name', 'location'];

    protected $casts = [
        'is_active' => 'boolean',
        'images' => 'array',
        'videos' => 'array',
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
            dispatch(new \App\Jobs\ProcessVideosJob(Story::class, $story->id));
        });
    }

    public static function ffmpegPath(): ?string
    {
        $path = config('services.ffmpeg.path', 'ffmpeg');
        if ($path !== 'ffmpeg') return $path;
        return exec('ffmpeg -version 2>&1') ? 'ffmpeg' : null;
    }

    public function convertHevcVideos(): void
    {
        $ffmpeg = self::ffmpegPath();
        if (!$ffmpeg || empty($this->videos)) return;

        $converted = [];
        foreach ($this->videos as $video) {
            $videoPath = Storage::disk('public')->path($video);
            if (!file_exists($videoPath)) { $converted[] = $video; continue; }

            exec("$ffmpeg -i " . escapeshellarg($videoPath) . ' 2>&1', $ffOut, $ffCode);
            $isHevc = preg_grep('/hevc/i', $ffOut ?? []);
            if (empty($isHevc)) { $converted[] = $video; continue; }

            $newName = pathinfo($video, PATHINFO_DIRNAME) . '/' . pathinfo($video, PATHINFO_FILENAME) . '_h264.mp4';
            $newPath = Storage::disk('public')->path($newName);
            $dir = dirname($newPath);
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            exec("$ffmpeg -i " . escapeshellarg($videoPath) . " -c:v libx264 -crf 28 -preset medium -b:v 2M -maxrate 2.5M -bufsize 5M -c:a aac -b:a 96k -movflags +faststart " . escapeshellarg($newPath) . " 2>&1", $output, $code);
            if ($code === 0) {
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

        foreach ($videos as $video) {
            if (str_starts_with($video, 'http')) continue;

            $videoPath = Storage::disk('public')->path($video);
            if (!file_exists($videoPath)) continue;

            $thumbName = pathinfo($video, PATHINFO_DIRNAME) . '/' . pathinfo($video, PATHINFO_FILENAME) . '_thumb.jpg';
            $thumbPath = Storage::disk('public')->path($thumbName);
            $dir = dirname($thumbPath);
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            if (file_exists($thumbPath)) continue;
            if (!$ffmpeg) continue;

            exec("$ffmpeg -i " . escapeshellarg($videoPath) . ' -ss 00:00:01 -vframes 1 -q:v 3 ' . escapeshellarg($thumbPath) . ' 2>&1', $output, $code);
        }
    }
}
