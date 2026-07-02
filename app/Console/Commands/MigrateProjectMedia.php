<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\ProjectMedia;
use Illuminate\Console\Command;

class MigrateProjectMedia extends Command
{
    protected $signature = 'projects:migrate-media';
    protected $description = 'Migrate existing images JSON and video_url to project_media table';

    public function handle(): void
    {
        Project::chunk(100, function ($projects) {
            foreach ($projects as $project) {
                $order = 0;

                if ($project->image) {
                    ProjectMedia::firstOrCreate([
                        'project_id' => $project->id,
                        'path' => $project->image,
                        'type' => 'image',
                    ], ['order' => $order++]);
                }

                if ($project->images) {
                    foreach ($project->images as $img) {
                        if ($img === $project->image) continue;
                        ProjectMedia::firstOrCreate([
                            'project_id' => $project->id,
                            'path' => $img,
                            'type' => 'image',
                        ], ['order' => $order++]);
                    }
                }

                if ($project->video_url) {
                    ProjectMedia::firstOrCreate([
                        'project_id' => $project->id,
                        'path' => $project->video_url,
                        'type' => 'video',
                    ], [
                        'order' => $order,
                        'thumbnail' => null,
                    ]);
                }
            }
        });

        $this->info('Project media migrated successfully.');
    }
}
