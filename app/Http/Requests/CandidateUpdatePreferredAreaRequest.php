<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CandidateUpdatePreferredAreaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('preferred_job_categories') && ! $this->has('preferred_functional_categories')) {
            $this->merge([
                'preferred_functional_categories' => $this->input('preferred_job_categories'),
            ]);
        } elseif ($this->has('preferred_functional_categories') && ! $this->has('preferred_job_categories')) {
            $this->merge([
                'preferred_job_categories' => $this->input('preferred_functional_categories'),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'preferred_job_categories' => 'nullable|array|max:6',
            'preferred_job_categories.*' => 'integer|exists:job_categories,id',
            'preferred_functional_categories' => 'nullable|array|max:6',
            'preferred_functional_categories.*' => 'integer',
            'preferred_job_locations_inside' => 'nullable|array|max:10',
            'preferred_job_locations_inside.*' => 'integer|exists:cities,id',
        ];
    }
}
