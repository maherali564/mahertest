<?php

namespace App\Models;

use App\Jobs\ProcessVideosJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class EmergencyCampaign extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $fillable = [
        'title', 'description', 'excerpt',
        'target_amount', 'currency', 'collected_amount', 'image', 'video', 'video_thumbnail', 'slug',
        'is_active', 'is_featured', 'starts_at', 'ends_at',
        'target_country', 'target_country_code', 'target_flag',
        'target_latitude', 'target_longitude', 'target_location',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'collected_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'target_latitude' => 'decimal:7',
        'target_longitude' => 'decimal:7',
    ];

    public array $translatable = ['title', 'description', 'excerpt'];

    /**
     * Country coordinate map used to derive lat/lng/code/flag/location
     * from the selected target_country (enforced server-side).
     */
    private static function countryCoordinates(): array
    {
        return [
            'فلسطين' => ['lat' => 31.5, 'lng' => 34.5, 'code' => 'PS', 'flag' => '🇵🇸', 'loc' => 'فلسطين - غزة'],
            'أمريكا' => ['lat' => 37.0, 'lng' => -95.0, 'code' => 'US', 'flag' => '🇺🇸', 'loc' => 'أمريكا'],
            'أوكرانيا' => ['lat' => 48.3, 'lng' => 31.1, 'code' => 'UA', 'flag' => '🇺🇦', 'loc' => 'أوكرانيا'],
            'تركيا' => ['lat' => 38.9, 'lng' => 35.2, 'code' => 'TR', 'flag' => '🇹🇷', 'loc' => 'تركيا'],
            'سوريا' => ['lat' => 34.8, 'lng' => 39.0, 'code' => 'SY', 'flag' => '🇸🇾', 'loc' => 'سوريا'],
            'اليمن' => ['lat' => 15.5, 'lng' => 48.0, 'code' => 'YE', 'flag' => '🇾🇪', 'loc' => 'اليمن'],
            'السودان' => ['lat' => 15.5, 'lng' => 30.0, 'code' => 'SD', 'flag' => '🇸🇩', 'loc' => 'السودان'],
            'لبنان' => ['lat' => 33.8, 'lng' => 35.8, 'code' => 'LB', 'flag' => '🇱🇧', 'loc' => 'لبنان'],
            'باكستان' => ['lat' => 30.3, 'lng' => 69.3, 'code' => 'PK', 'flag' => '🇵🇰', 'loc' => 'باكستان'],
            'إندونيسيا' => ['lat' => -0.7, 'lng' => 113.9, 'code' => 'ID', 'flag' => '🇮🇩', 'loc' => 'إندونيسيا'],
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($campaign) {
            if (empty($campaign->slug)) {
                $campaign->slug = \Illuminate\Support\Str::slug($campaign->getTranslation('title', 'ar') ?: 'campaign-' . now()->timestamp);
            }
            $campaign->applyCoordinatesFromCountry();
        });

        static::updating(function ($campaign) {
            if ($campaign->isDirty('target_country')) {
                $campaign->applyCoordinatesFromCountry();
            }
        });

        static::saved(function ($campaign) {
            if ($campaign->wasChanged('video') && $campaign->video) {
                ProcessVideosJob::dispatch(EmergencyCampaign::class, $campaign->id);
            }
        });
    }

    /**
     * Derive lat/lng/code/flag/location from target_country server-side.
     * Ignores any client-submitted values for these derived fields.
     */
    private function applyCoordinatesFromCountry(): void
    {
        $coords = self::countryCoordinates();
        $country = $this->target_country;
        if (isset($coords[$country])) {
            $this->target_latitude = $coords[$country]['lat'];
            $this->target_longitude = $coords[$country]['lng'];
            $this->target_country_code = $coords[$country]['code'];
            $this->target_flag = $coords[$country]['flag'];
            $this->target_location = $coords[$country]['loc'];
        }
    }

    public function donations(): HasMany
    {
        return $this->hasMany(EmergencyDonation::class);
    }

    public function getProgressPercentAttribute(): float
    {
        return $this->target_amount > 0
            ? round($this->collected_amount / $this->target_amount * 100, 1)
            : 0;
    }

    public function getDonorCountAttribute(): int
    {
        return $this->donations()->count();
    }

    public function remainingDays(): ?int
    {
        return $this->ends_at ? max(0, now()->diffInDays($this->ends_at, false)) : null;
    }

    public function isActive(): bool
    {
        return $this->is_active && ($this->ends_at === null || now()->lt($this->ends_at));
    }

    public function convertHevcVideo(): void
    {
        if (!$this->video || str_starts_with($this->video, 'http')) return;

        $ffmpeg = $this->ffmpegPath();
        if (!$ffmpeg) return;

        $videoPath = Storage::disk('public')->path($this->video);
        if (!file_exists($videoPath)) return;

        $probePath = escapeshellarg(config('services.ffmpeg.probe', 'ffprobe'));
        $ffprobe = Process::timeout(30)->run("$probePath -v error -select_streams v:0 -show_entries stream=codec_name -of default=noprint_wrappers=1:nokey=1 " . escapeshellarg($videoPath) . ' 2>&1');
        $codec = trim($ffprobe->output());
        $isHevc = str_contains(strtolower($codec), 'hevc');
        if (!$isHevc && empty($codec) && $ffmpeg) {
            $detect = Process::timeout(30)->run("$ffmpeg -i " . escapeshellarg($videoPath) . ' 2>&1');
            $isHevc = str_contains(strtolower($detect->output()), 'hevc');
        }
        if (!$isHevc) return;

        $newName = pathinfo($this->video, PATHINFO_DIRNAME) . '/' . pathinfo($this->video, PATHINFO_FILENAME) . '_h264.mp4';
        $newPath = Storage::disk('public')->path($newName);
        $dir = dirname($newPath);
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $conv = Process::timeout(300)->run("$ffmpeg -i " . escapeshellarg($videoPath) . " -c:v libx264 -crf 23 -preset medium -b:v 2M -maxrate 2.5M -bufsize 5M -c:a aac -b:a 96k -movflags +faststart " . escapeshellarg($newPath) . " 2>&1");
        if ($conv->successful()) {
            Storage::disk('public')->delete($this->video);
            $this->video = $newName;
        }
    }

    public function generateVideoThumbnail(): void
    {
        if (!$this->video || str_starts_with($this->video, 'http')) return;

        $ffmpeg = $this->ffmpegPath();
        if (!$ffmpeg) return;

        if ($this->video_thumbnail && Storage::disk('public')->exists($this->video_thumbnail)) return;

        $videoPath = Storage::disk('public')->path($this->video);
        if (!file_exists($videoPath)) return;

        $thumbName = pathinfo($this->video, PATHINFO_DIRNAME) . '/' . pathinfo($this->video, PATHINFO_FILENAME) . '_thumb.jpg';
        $thumbPath = Storage::disk('public')->path($thumbName);
        $dir = dirname($thumbPath);
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        if (file_exists($thumbPath)) {
            $this->video_thumbnail = $thumbName;
            return;
        }

        $thumb = Process::timeout(120)->run("$ffmpeg -i " . escapeshellarg($videoPath) . ' -ss 00:00:00.5 -vframes 1 -q:v 3 ' . escapeshellarg($thumbPath) . ' 2>&1');
        if ($thumb->successful()) {
            $this->video_thumbnail = $thumbName;
        } else {
            Log::warning('EmergencyCampaign thumbnail generation failed', ['campaign_id' => $this->id, 'video' => $this->video]);
        }
    }

    public function convertHevcVideos(): void
    {
        $this->convertHevcVideo();
    }

    public function generateVideoThumbnails(): void
    {
        $this->generateVideoThumbnail();
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
}
