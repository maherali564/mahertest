<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComplaintRequest;
use App\Models\Complaint;
use App\Notifications\ComplaintReceived;
use Illuminate\Support\Facades\Storage;

class ComplaintController extends Controller
{
    public function create()
    {
        return view('complaints.create');
    }

    public function store(StoreComplaintRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('complaints', 'public');
        }

        $data['user_id'] = auth('donor')->id();

        $complaint = Complaint::create($data);

        $admins = \App\Models\User::permission('view_any_complaint')->get();
        foreach ($admins as $admin) {
            $admin->notify(new ComplaintReceived($complaint));
        }

        return redirect()->route('complaints.create', ['locale' => app()->getLocale()])
            ->with('success', __('complaints.success', ['id' => $complaint->id]));
    }


}
