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

    protected function prepareForValidation(): void
    {
        $this->merge([
            'phone' => filled($this->input('phone')) ? preg_replace('/\D+/', '', (string) $this->input('phone')) : null,
            'region_code' => filled($this->input('region_code')) ? preg_replace('/\D+/', '', (string) $this->input('region_code')) : null,
            'secondary_mobile' => filled($this->input('secondary_mobile')) ? preg_replace('/\D+/', '', (string) $this->input('secondary_mobile')) : null,
            'emergency_contact' => filled($this->input('emergency_contact')) ? preg_replace('/\D+/', '', (string) $this->input('emergency_contact')) : null,
        ]);
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
            'phone' => ['nullable', 'string', 'regex:/^\d{1,11}$/'],
            'region_code' => ['nullable', 'string', 'regex:/^\d{1,4}$/'],
            'secondary_mobile' => ['nullable', 'string', 'regex:/^\d{1,11}$/'],
            'email' => 'required|email:filter|unique:users,email,'.$id,
            'alternate_email' => 'nullable|email:filter|max:150',
            'emergency_contact' => ['nullable', 'string', 'regex:/^\d{1,11}$/'],
            'blood_group' => ['nullable', 'max:10', Rule::in(ProfileReferenceOption::values(ProfileReferenceOption::TYPE_BLOOD_GROUP))],
            'height' => 'nullable|numeric|min:0|max:999',
            'weight' => 'nullable|numeric|min:0|max:999',
            'image' => 'nullable|mimes:jpeg,jpg,png|max:1024',
        ];
    }
}
