<?php

namespace App\Http\Controllers;

use App\Models\Volunteer;
use App\Models\VolunteerOpportunity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VolunteerController extends Controller
{
    /** Store a new volunteer registration. */
    public function store(Request $request): RedirectResponse
    {
        if ($request->filled('hp_website')) {
            abort(422, 'Spam detected');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'national_id' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:1000',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:50',
            'skills' => 'nullable|string|max:2000',
            'availability' => 'nullable|string|max:1000',
            'message' => 'nullable|string|max:2000',
            'volunteer_opportunity_id' => 'nullable|exists:volunteer_opportunities,id',
        ]);

        $volunteer = Volunteer::create([
            ...$validated,
            'locale' => app()->getLocale(),
            'status' => 'pending',
        ]);

        session()->flash('volunteer_id', $volunteer->id);

        return back()->with('success', __('common.volunteer_success'));
    }

    /** Show the volunteer dashboard with tasks and opportunities. */
    public function dashboard(Request $request): View
    {
        $volunteerId = session('volunteer_id');
        $volunteer = null;

        if ($volunteerId) {
            $volunteer = Volunteer::with('tasks')->find($volunteerId);
        }

        if (!$volunteer && $request->has('ref') && $request->filled('email')) {
            $volunteer = Volunteer::where('id', $request->query('ref'))
                ->where('email', $request->input('email'))
                ->with('tasks')
                ->first();
        }

        $opportunities = VolunteerOpportunity::active()->get();

        return view('volunteer.dashboard', compact('volunteer', 'opportunities'));
    }

    /** Show the volunteer registration form. */
    public function register(): View
    {
        $opportunities = VolunteerOpportunity::active()->get();
        return view('volunteer.register', compact('opportunities'));
    }
}
