<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\View\View;

class StoryController extends Controller
{
    /** List all active stories. */
    public function index(): View
    {
        return view('stories.index', [
            'stories' => Story::active()->get(),
        ]);
    }

    /** Show a single story. */
    public function show(string $locale, string $id): View
    {
        $story = Story::active()->findOrFail($id);

        return view('stories.show', compact('story'));
    }
}
