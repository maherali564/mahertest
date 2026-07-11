<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\ProjectMedia;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateProjectMedia extends Command
{
    protected $signature = 'projects:migrate-media';
    protected $description = 'Migrate existing images JSON and video_url to project_media table';

    public function handle(): void
    {
        Project::chunk(100, function ($projects) {
            foreach ($projects as $project) {
                try {
                    DB::transaction(function () use ($project) {
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

                        if ($project->video_url && filter_var($project->video_url, FILTER_VALIDATE_URL)) {
                            ProjectMedia::firstOrCreate([
                                'project_id' => $project->id,
                                'path' => $project->video_url,
                                'type' => 'video',
                            ], [
                                'order' => $order,
                                'thumbnail' => null,
                            ]);
                        }
                    });
                } catch (\Exception $e) {
                    $this->error("Failed to migrate project {$project->id}: {$e->getMessage()}");
                }
            }
        });

        $this->info('Project media migrated successfully.');
    }
}
