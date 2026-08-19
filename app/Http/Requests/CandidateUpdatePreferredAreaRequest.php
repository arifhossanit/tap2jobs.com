<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CandidateUpdatePreferredAreaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'preferred_functional_categories' => 'nullable|array|max:10',
            'preferred_functional_categories.*' => 'integer|exists:functional_areas,id',
            'preferred_special_skills' => 'nullable|array|max:10',
            'preferred_special_skills.*' => 'integer|exists:skills,id',
            'preferred_job_locations_inside' => 'nullable|array|max:64',
            'preferred_job_locations_inside.*' => 'integer|exists:states,id',
            'preferred_job_locations_outside' => 'nullable|array|max:100',
            'preferred_job_locations_outside.*' => 'integer|exists:countries,id',
            'preferred_organization_types' => 'nullable|array|max:50',
            'preferred_organization_types.*' => 'integer|exists:ownership_types,id',
        ];
    }
}
