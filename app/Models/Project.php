<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasTranslations;

    protected $fillable = [
        'slug', 'title', 'description', 'content',
        'image', 'images', 'video_url', 'video_type', 'videos', 'goal_amount', 'raised_amount',
        'is_featured', 'sort_order', 'is_active', 'program_id',
    ];

    public array $translatable = ['title', 'description', 'content'];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'images' => 'array',
        'videos' => 'array',
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

            dispatch(new \App\Jobs\ProcessVideosJob(Project::class, $project->id));
        });

        static::deleted(function (Project $project) {
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            foreach ($project->images ?? [] as $img) {
                Storage::disk('public')->delete($img);
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
        if ($path !== 'ffmpeg') return $path;
        return exec('ffmpeg -version 2>&1') ? 'ffmpeg' : null;
    }

    public function convertHevcVideos(): void
    {
        $ffmpeg = static::ffmpegPath();
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

            exec("$ffmpeg -i " . escapeshellarg($videoPath) . ' -ss 00:00:01 -vframes 1 -q:v 2 ' . escapeshellarg($thumbPath) . ' 2>&1', $output, $code);
            if ($code === 0) $media->update(['thumbnail' => $thumbName]);
        }
    }
}
