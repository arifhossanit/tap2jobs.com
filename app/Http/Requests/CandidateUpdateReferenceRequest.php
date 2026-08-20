<?php

namespace App\Http\Requests;

use App\Models\ProfileReferenceOption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CandidateUpdateReferenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:191'],
            'designation' => ['required', 'string', 'max:191'],
            'organization' => ['required', 'string', 'max:191'],
            'email' => ['nullable', 'email', 'max:191'],
            'relation' => ['nullable', 'string', Rule::in(ProfileReferenceOption::values(ProfileReferenceOption::TYPE_REFERENCE_RELATION))],
            'mobile' => ['nullable', 'string', 'max:30'],
            'office_phone' => ['nullable', 'string', 'max:30'],
            'residential_phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
