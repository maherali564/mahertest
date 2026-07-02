<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /** Store a contact form submission. */
    public function store(Request $request): RedirectResponse
    {
        if ($request->filled('hp_website')) {
            abort(422, 'Spam detected');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        ContactSubmission::create([
            ...$validated,
            'locale' => app()->getLocale(),
        ]);

        return back()->with('success', __('site.contact_success'));
    }
}
