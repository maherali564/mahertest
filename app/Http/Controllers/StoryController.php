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

    /** Show a single story by slug (falls back to id for backward compatibility before migration). */
    public function show(string $locale, string $slug): View
    {
        $story = Story::active()->where('slug', $slug)->first();
        if (!$story && ctype_digit($slug)) {
            $story = Story::active()->findOrFail($slug);
        }
        if (!$story) abort(404);

        return view('stories.show', compact('story'));
    }
}
