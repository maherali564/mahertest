<?php

namespace App\Models;

use App\Jobs\ProcessVideosJob;
use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasTranslations;

    protected $fillable = [
        'slug', 'title', 'description', 'content', 'excerpt',
        'image', 'images', 'video_url', 'video_type', 'videos', 'video_thumbnails', 'goal_amount', 'raised_amount',
        'is_featured', 'sort_order', 'is_active', 'program_id',
    ];

    public array $translatable = ['title', 'description', 'content', 'excerpt'];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'images' => 'array',
        'videos' => 'array',
        'video_thumbnails' => 'array',
        'goal_amount' => 'decimal:2',
        'raised_amount' => 'decimal:2',
    ];

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

    public function progressPercent(): float
    {
        if ($this->goal_amount <= 0) return 0;
        return min(100, round(($this->raised_amount / $this->goal_amount) * 100, 1));
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function media()
    {
        return $this->hasMany(ProjectMedia::class)->orderBy('order');
    }

    public function images()
    {
        return $this->hasMany(ProjectMedia::class)->where('type', 'image')->orderBy('order');
    }

    public function videos()
    {
        return $this->hasMany(ProjectMedia::class)->where('type', 'video')->orderBy('order');
    }

    public function hasVideo(): bool
    {
        return $this->videos()->exists();
    }

    protected static function booted(): void
    {
        static::saving(function (Project $project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->getTranslation('title', 'en') ?: 'project-'.uniqid());
            }
        });

        static::saved(function (Project $project) {
            $paths = [];

            if ($project->image) {
                $paths[] = ['path' => $project->image, 'type' => 'image', 'order' => 0];
            }

            if ($project->images) {
                foreach ($project->images as $i => $img) {
                    $paths[] = ['path' => $img, 'type' => 'image', 'order' => $i + 1];
                }
            }

            if ($project->video_url && !in_array($project->video_url, $project->videos ?? [])) {
                $order = count($paths);
                $paths[] = ['path' => $project->video_url, 'type' => 'video', 'order' => $order];
            }

            foreach ($project->videos ?? [] as $i => $v) {
                if (!in_array($v, array_column($paths, 'path'))) {
                    $order = count($paths);
                    $paths[] = ['path' => $v, 'type' => 'video', 'order' => $order];
                }
            }

            foreach ($paths as $item) {
                $project->media()->firstOrCreate(
                    ['path' => $item['path'], 'type' => $item['type']],
                    ['order' => $item['order']]
                );
            }

            $legacyPaths = array_column($paths, 'path');
            $project->media()->whereNotIn('path', $legacyPaths)->delete();

            if (($project->wasChanged('videos') && !empty($project->videos)) || $project->wasChanged('video_url')) {
                ProcessVideosJob::dispatch(Project::class, $project->id);
            }
        });

        static::deleted(function (Project $project) {
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            foreach ($project->images ?? [] as $img) {
                Storage::disk('public')->delete($img);
            }
            if ($project->video_url && !str_starts_with($project->video_url, 'http')) {
                Storage::disk('public')->delete($project->video_url);
            }
            foreach ($project->videos ?? [] as $v) {
                Storage::disk('public')->delete($v);
            }
            foreach ($project->media as $m) {
                Storage::disk('public')->delete($m->path);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
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
        $ffmpeg = static::ffmpegPath();
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
        $ffmpeg = static::ffmpegPath();

        foreach ($this->media()->where('type', 'video')->whereNull('thumbnail')->get() as $media) {
            if (str_starts_with($media->path, 'http')) {
                if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $media->path, $m)) {
                    $media->update(['thumbnail' => 'https://img.youtube.com/vi/'.$m[1].'/hqdefault.jpg']);
                }
                continue;
            }

            $videoPath = Storage::disk('public')->path($media->path);
            if (!file_exists($videoPath)) continue;

            $thumbName = pathinfo($media->path, PATHINFO_DIRNAME) . '/' . pathinfo($media->path, PATHINFO_FILENAME) . '_thumb.jpg';
            $thumbPath = Storage::disk('public')->path($thumbName);
            $dir = dirname($thumbPath);
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            if (file_exists($thumbPath)) { $media->update(['thumbnail' => $thumbName]); continue; }
            if (!$ffmpeg) continue;

            $thumb = Process::timeout(120)->run("$ffmpeg -i " . escapeshellarg($videoPath) . ' -ss 00:00:00.5 -vframes 1 -q:v 2 ' . escapeshellarg($thumbPath) . ' 2>&1');
            if ($thumb->successful()) $media->update(['thumbnail' => $thumbName]);
        }

        $videos = $this->videos ?? [];
        $thumbnails = $this->video_thumbnails ?? [];
        $changed = false;

        foreach ($videos as $video) {
            if (str_starts_with($video, 'http')) continue;
            if (isset($thumbnails[$video]) && Storage::disk('public')->exists($thumbnails[$video])) continue;

            $videoPath = Storage::disk('public')->path($video);
            if (!file_exists($videoPath)) continue;

            $thumbName = pathinfo($video, PATHINFO_DIRNAME) . '/' . pathinfo($video, PATHINFO_FILENAME) . '_thumb.jpg';
            $thumbPath = Storage::disk('public')->path($thumbName);
            $dir = dirname($thumbPath);
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            if (file_exists($thumbPath)) { $thumbnails[$video] = $thumbName; $changed = true; continue; }
            if (!$ffmpeg) continue;

            $thumb = Process::timeout(120)->run("$ffmpeg -i " . escapeshellarg($videoPath) . ' -ss 00:00:00.5 -vframes 1 -q:v 2 ' . escapeshellarg($thumbPath) . ' 2>&1');
            if ($thumb->successful()) { $thumbnails[$video] = $thumbName; $changed = true; }
        }

        if ($changed) {
            $this->video_thumbnails = $thumbnails;
        }
    }
}
