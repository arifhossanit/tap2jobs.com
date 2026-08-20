<?php

namespace App\Http\Requests;

use App\Models\ProfileReferenceOption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CandidateUpdatePersonalDetailsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = Auth::id();

        return [
            'first_name' => 'required|max:150',
            'last_name' => 'required|max:150',
            'father_name' => 'nullable|max:150',
            'mother_name' => 'nullable|max:150',
            'dob' => 'nullable|date|before_or_equal:today',
            'gender' => ['required', Rule::in(ProfileReferenceOption::values(ProfileReferenceOption::TYPE_GENDER))],
            'religion' => ['nullable', 'max:100', Rule::in(ProfileReferenceOption::values(ProfileReferenceOption::TYPE_RELIGION))],
            'marital_status_id' => 'required|integer|exists:marital_status,id',
            'nationality' => 'nullable|max:150',
            'national_id_card' => 'nullable|max:150',
            'passport_number' => 'nullable|max:150',
            'passport_issue_date' => 'nullable|date|before_or_equal:today',
            'phone' => 'nullable|max:30',
            'region_code' => 'nullable|max:10',
            'secondary_mobile' => 'nullable|max:30',
            'email' => 'required|email:filter|unique:users,email,'.$id,
            'alternate_email' => 'nullable|email:filter|max:150',
            'emergency_contact' => 'nullable|max:30',
            'blood_group' => ['nullable', 'max:10', Rule::in(ProfileReferenceOption::values(ProfileReferenceOption::TYPE_BLOOD_GROUP))],
            'height' => 'nullable|numeric|min:0|max:999',
            'weight' => 'nullable|numeric|min:0|max:999',
            'image' => 'nullable|mimes:jpeg,jpg,png|max:1024',
        ];
    }
}
