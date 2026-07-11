<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|in:donation,response,site,volunteer,other',
            'description' => 'required|string|min:20|max:5000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'consent' => 'accepted',
        ];
    }

    public function messages(): array
    {
        return [
            'subject.required' => __('validation.required', ['attribute' => __('complaints.subject')]),
            'subject.in' => __('validation.in', ['attribute' => __('complaints.subject')]),
            'description.min' => __('validation.min.string', ['attribute' => __('complaints.description'), 'min' => 20]),
            'consent.accepted' => __('complaints.consent_required'),
        ];
    }
}
