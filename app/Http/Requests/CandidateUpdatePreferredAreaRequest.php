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
            'preferred_functional_categories' => 'nullable|array|max:3',
            'preferred_functional_categories.*' => 'integer|exists:functional_areas,id',
            'preferred_job_locations_inside' => 'nullable|array|max:15',
            'preferred_job_locations_inside.*' => 'integer|exists:cities,id',
        ];
    }
}
