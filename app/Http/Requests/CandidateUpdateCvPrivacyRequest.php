<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CandidateUpdateCvPrivacyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'include_sensitive_personal_data_in_cv' => $this->boolean('include_sensitive_personal_data_in_cv'),
        ]);
    }

    public function rules(): array
    {
        return [
            'include_sensitive_personal_data_in_cv' => ['required', 'boolean'],
        ];
    }
}
