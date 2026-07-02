<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\Story;
use Illuminate\Console\Command;

class GenerateVideoThumbnails extends Command
{
    protected $signature = 'videos:generate-thumbnails';
    protected $description = 'Generate missing video thumbnails for all projects and stories';

    public function handle(): void
    {
        Project::chunk(100, function ($projects) {
            foreach ($projects as $project) {
                $project->convertHevcVideos();
                if ($project->isDirty('videos')) $project->saveQuietly();
                $project->generateVideoThumbnails();
            }
        });
        $count = \App\Models\ProjectMedia::where('type', 'video')->whereNotNull('thumbnail')->count();

        Story::chunk(100, function ($stories) {
            foreach ($stories as $story) {
                $story->convertHevcVideos();
                if ($story->isDirty('videos')) $story->saveQuietly();
            }
        });

        $this->info("Done. Project media with thumbnails: $count");
    }
}
