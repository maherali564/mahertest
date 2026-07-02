<?php

namespace App\Console\Commands;

use App\Services\MediaOptimizer;
use Illuminate\Console\Command;

class OptimizeMedia extends Command
{
    protected $signature = 'media:optimize {--recent=0 : Only files modified within N minutes}';
    protected $description = 'Compress images and videos for faster loading';

    public function handle(): void
    {
        $ffmpeg = config('services.ffmpeg.path', 'ffmpeg');
        $recentMinutes = (int) $this->option('recent');
        $cutoff = $recentMinutes > 0 ? now()->subMinutes($recentMinutes) : null;

        $base = storage_path('app/public');
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($base));
        $count = 0;

        foreach ($files as $f) {
            if (!$f->isFile()) continue;

            $rel = substr($f->getPathname(), strlen($base) + 1);
            $rel = str_replace('\\', '/', $rel);

            if ($cutoff && $f->getMTime() < $cutoff->timestamp) continue;
            if (str_contains($rel, '_thumb.')) continue;
            if (str_contains($rel, '.tmp.')) continue;

            $ext = strtolower($f->getExtension());

            if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                $before = $f->getSize();
                $ok = MediaOptimizer::compressImage($rel);
                if ($ok) {
                    $after = filesize($f->getPathname());
                    $saved = $before - $after;
                    $pct = $before > 0 ? round(100 * $after / $before) : 0;
                    $this->line("  <info>compressed</info> {$rel} ({$pct}%)");
                    $count++;
                }
            } elseif (in_array($ext, ['mp4', 'webm', 'mov', 'avi'])) {
                $before = $f->getSize();
                $ok = MediaOptimizer::compressVideo($rel);
                if ($ok) {
                    $after = filesize($f->getPathname());
                    $saved = $before - $after;
                    $pct = $before > 0 ? round(100 * $after / $before) : 0;
                    $this->line("  <info>compressed</info> {$rel} ({$pct}%)");
                    $count++;
                }
            }
        }

        $this->info("Optimized {$count} files.");
    }
}
