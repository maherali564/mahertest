<?php

namespace App\Console\Commands;

use App\Models\EmergencyCampaign;
use App\Models\Project;
use App\Models\Story;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateVideoThumbnails extends Command
{
    protected $signature = 'videos:generate-thumbnails {--force : Regenerate existing thumbnails}';
    protected $description = 'Generate missing video thumbnails for projects, stories, and emergency campaigns';

    public function handle(): void
    {
        set_time_limit(3600);

        $force = $this->option('force');

        $this->info('Generating project video thumbnails...');
        Project::chunk(100, function ($projects) use ($force) {
            foreach ($projects as $project) {
                try {
                    if ($force) {
                        $project->media()->where('type', 'video')->update(['thumbnail' => null]);
                        $project->video_thumbnails = [];
                        $project->saveQuietly();
                    }
                    $project->convertHevcVideos();
                    if ($project->isDirty('videos')) $project->saveQuietly();
                    $project->generateVideoThumbnails();
                } catch (\Exception $e) {
                    Log::error('Failed to generate project video thumbnails', [
                        'project_id' => $project->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        });
        $projectCount = \App\Models\ProjectMedia::where('type', 'video')->whereNotNull('thumbnail')->count();

        $this->info('Generating story video thumbnails...');
        Story::chunk(100, function ($stories) use ($force) {
            foreach ($stories as $story) {
                try {
                    if ($force) {
                        $story->video_thumbnails = [];
                        $story->saveQuietly();
                    }
                    $story->convertHevcVideos();
                    if ($story->isDirty('videos')) $story->saveQuietly();
                    $story->generateVideoThumbnails();
                } catch (\Exception $e) {
                    Log::error('Failed to generate story video thumbnails', [
                        'story_id' => $story->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        });

        $this->info('Generating emergency campaign video thumbnails...');
        EmergencyCampaign::chunk(100, function ($campaigns) use ($force) {
            foreach ($campaigns as $campaign) {
                try {
                    if ($force) {
                        $campaign->video_thumbnail = null;
                        $campaign->saveQuietly();
                    }
                    $campaign->convertHevcVideo();
                    if ($campaign->isDirty('video')) $campaign->saveQuietly();
                    $campaign->generateVideoThumbnail();
                } catch (\Exception $e) {
                    Log::error('Failed to generate campaign video thumbnails', [
                        'campaign_id' => $campaign->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        });

        $this->info("Done. Project media with thumbnails: $projectCount");
    }
}
