<?php

namespace App\Console\Commands;

use App\Models\EmergencyCampaign;
use App\Models\Partner;
use App\Models\PaymentGateway;
use App\Models\Post;
use App\Models\Project;
use App\Models\ProjectMedia;
use App\Models\SiteSetting;
use App\Models\Slider;
use App\Models\Story;
use App\Services\MediaOptimizer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ConvertImagesToWebp extends Command
{
    protected $signature = 'images:convert-to-webp
        {--dry-run : Show what would be converted without making changes}
        {--force : Force conversion even if WebP is larger than original}';

    protected $description = 'Convert existing JPG/PNG images to WebP and update DB references';

    private array $stats = ['converted' => 0, 'skipped' => 0, 'errors' => 0];

    public function handle(): int
    {
        if (!function_exists('imagewebp')) {
            $this->error('PHP GD WebP support (imagewebp) is required but not available.');
            return Command::FAILURE;
        }

        $this->line(sprintf('PHP memory_limit: %s', ini_get('memory_limit')));
        if ($this->option('dry-run')) {
            $this->warn('DRY RUN — no changes will be made.');
        }

        $this->processSimpleFields();
        $this->processJsonFields();
        $this->processProjectMedia();
        $this->processVideoThumbnails(Project::class);
        $this->processVideoThumbnails(Story::class);
        $this->processOrphanedFiles();

        $this->newLine();
        $this->table(
            ['Status', 'Count'],
            [
                ['Converted', $this->stats['converted']],
                ['Skipped (already WebP / missing)', $this->stats['skipped']],
                ['Errors', $this->stats['errors']],
            ]
        );

        if ($this->option('dry-run')) {
            $this->warn('DRY RUN — no actual changes were made.');
        } else {
            $this->info('Done. Run "php artisan cache:clear" if any image caches exist.');
        }

        return Command::SUCCESS;
    }

    private function processSimpleFields(): void
    {
        $models = [
            Slider::class          => ['image'],
            SiteSetting::class     => ['logo', 'hero_image', 'about_image'],
            Story::class           => ['image'],
            Project::class         => ['image'],
            Post::class            => ['featured_image'],
            Partner::class         => ['logo'],
            PaymentGateway::class  => ['logo'],
            EmergencyCampaign::class => ['image', 'video_thumbnail'],
        ];

        foreach ($models as $modelClass => $fields) {
            $records = $modelClass::all();
            if ($records->isEmpty()) continue;
            $this->newLine();
            $this->line(sprintf('Processing <comment>%s</comment> (%d records)...', class_basename($modelClass), $records->count()));

            foreach ($records as $record) {
                foreach ($fields as $field) {
                    $path = $record->{$field};
                    if (!$this->isConvertible($path)) continue;

                    $newPath = $this->convert($path);
                    if ($newPath === false) continue;

                    $record->{$field} = $newPath;
                    $record->saveQuietly();
                }
            }
        }
    }

    private function processJsonFields(): void
    {
        // site_settings.logos — JSON object {"en": "path", "ar": "path", ...}
        $this->line("\nProcessing <comment>SiteSetting.logos</comment>...");
        $setting = SiteSetting::current();
        $logos = $setting->logos ?? [];
        $changed = false;

        foreach ($logos as $locale => $path) {
            if (!$this->isConvertible($path)) continue;
            $newPath = $this->convert($path);
            if ($newPath === false) continue;
            $logos[$locale] = $newPath;
            $changed = true;
        }

        if ($changed) {
            $setting->logos = $logos;
            $setting->saveQuietly();
        }

        // stories.images and projects.images — JSON arrays
        foreach ([Story::class, Project::class] as $modelClass) {
            $this->line(sprintf('Processing <comment>%s.images</comment>...', class_basename($modelClass)));
            foreach ($modelClass::all() as $record) {
                $images = $record->images ?? [];
                $changed = false;

                foreach ($images as $i => $path) {
                    if (!$this->isConvertible($path)) continue;
                    $newPath = $this->convert($path);
                    if ($newPath === false) continue;
                    $images[$i] = $newPath;
                    $changed = true;
                }

                if ($changed) {
                    $record->images = array_values($images);
                    $record->saveQuietly();
                }
            }
        }
    }

    private function processProjectMedia(): void
    {
        $this->line("\nProcessing <comment>ProjectMedia</comment>...");
        foreach (ProjectMedia::all() as $media) {
            foreach (['path', 'thumbnail'] as $field) {
                $path = $media->{$field};
                if (!$this->isConvertible($path)) continue;
                $newPath = $this->convert($path);
                if ($newPath === false) continue;
                $media->{$field} = $newPath;
                $media->saveQuietly();
            }
        }
    }

    private function processVideoThumbnails(string $modelClass): void
    {
        $this->line(sprintf("\nProcessing <comment>%s.video_thumbnails</comment>...", class_basename($modelClass)));
        foreach ($modelClass::all() as $record) {
            $thumbs = $record->video_thumbnails ?? [];
            $changed = false;

            foreach ($thumbs as $videoPath => $thumbPath) {
                if (!$this->isConvertible($thumbPath)) continue;
                $newPath = $this->convert($thumbPath);
                if ($newPath === false) continue;
                $thumbs[$videoPath] = $newPath;
                $changed = true;
            }

            if ($changed) {
                $record->video_thumbnails = $thumbs;
                $record->saveQuietly();
            }
        }
    }

    private function processOrphanedFiles(): void
    {
        $this->line("\nProcessing <comment>orphaned files</comment> (not referenced in DB)...");

        $disk = Storage::disk('public');
        $referenced = $this->getAllReferencedPaths();
        $base = $disk->path('');
        $orphans = 0;

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($base, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($files as $file) {
            if (!$file->isFile()) continue;

            $rel = str_replace('\\', '/', substr($file->getPathname(), strlen($base) + 1));
            $ext = strtolower($file->getExtension());

            if (!in_array($ext, ['jpg', 'jpeg', 'png'], true)) continue;
            if (in_array($rel, $referenced, true)) continue;
            if (str_contains($rel, '.tmp.')) continue;
            if (str_contains($rel, 'logos/')) continue;

            $orphans++;
            $this->convert($rel);
        }

        if ($orphans === 0) {
            $this->line('  No orphaned images found.');
        }
    }

    private function getAllReferencedPaths(): array
    {
        $paths = [];

        $models = [
            Slider::class          => ['image'],
            SiteSetting::class     => ['logo', 'hero_image', 'about_image'],
            Story::class           => ['image'],
            Project::class         => ['image'],
            Post::class            => ['featured_image'],
            Partner::class         => ['logo'],
            PaymentGateway::class  => ['logo'],
            EmergencyCampaign::class => ['image', 'video_thumbnail'],
        ];

        foreach ($models as $modelClass => $fields) {
            foreach ($modelClass::all() as $record) {
                foreach ($fields as $field) {
                    if ($path = $record->{$field}) {
                        $paths[] = $path;
                    }
                }
            }
        }

        // JSON fields
        $setting = SiteSetting::current();
        foreach ($setting->logos ?? [] as $path) {
            if ($path) $paths[] = $path;
        }

        foreach (Story::all() as $r) {
            foreach ($r->images ?? [] as $p) { if ($p) $paths[] = $p; }
            foreach ($r->video_thumbnails ?? [] as $p) { if ($p) $paths[] = $p; }
        }

        foreach (Project::all() as $r) {
            foreach ($r->images ?? [] as $p) { if ($p) $paths[] = $p; }
            foreach ($r->video_thumbnails ?? [] as $p) { if ($p) $paths[] = $p; }
        }

        foreach (ProjectMedia::all() as $m) {
            if ($m->path) $paths[] = $m->path;
            if ($m->thumbnail) $paths[] = $m->thumbnail;
        }

        return array_unique($paths);
    }

    private function isConvertible(?string $path): bool
    {
        if (empty($path)) return false;
        if (!is_string($path)) return false;
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png'], true);
    }

    /**
     * Convert a single image to WebP.
     * Returns the new .webp path on success, or false if skipped/failed.
     */
    private function convert(string $path): string|false
    {
        $disk = Storage::disk('public');

        // Already WebP?
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if ($ext === 'webp') {
            $this->stats['skipped']++;
            return false;
        }

        // If .webp equivalent already exists (converted by earlier record), just return it
        $webpPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $path);
        if ($disk->exists($webpPath)) {
            if (!$this->option('dry-run')) {
                $this->stats['converted']++;
                $this->line("  <info>already exists</info> {$path} → {$webpPath}");
                return $webpPath;
            }
            $this->line("  [DRY-RUN] already exists: {$path} → {$webpPath}");
            return $webpPath;
        }

        // Original file missing?
        if (!$disk->exists($path)) {
            $this->stats['skipped']++;
            return false;
        }

        if ($this->option('dry-run')) {
            $this->line("  [DRY-RUN] would convert: {$path}");
            $this->stats['converted']++;
            return $webpPath;
        }

        // Convert
        $result = MediaOptimizer::convertToWebp($path);

        if ($result) {
            $this->stats['converted']++;
            $this->line("  <info>converted</info> {$path} → {$result}");
            return $result;
        }

        $this->stats['errors']++;
        $this->line("  <error>failed</error> {$path}");
        return false;
    }
}
