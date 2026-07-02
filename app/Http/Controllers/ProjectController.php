<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /** List all active projects. */
    public function index(): View
    {
        return view('projects.index', [
            'projects' => Project::active()->get(),
        ]);
    }

    /** Show a single project page. */
    public function show(string $locale, string $slug): View
    {
        $project = Project::with('media')->where('slug', $slug)->active()->firstOrFail();

        return view('projects.show', compact('project'));
    }
}
