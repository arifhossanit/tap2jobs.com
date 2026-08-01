<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CandidateUpdateCareerApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'current_salary' => removeCommaFromNumbers($this->get('current_salary')),
            'expected_salary' => removeCommaFromNumbers($this->get('expected_salary')),
        ]);
    }

    public function rules(): array
    {
        return [
            'objective' => 'required|max:2000',
            'current_salary' => 'nullable|numeric|min:0|max:999999999',
            'expected_salary' => 'nullable|numeric|min:0|max:999999999',
            'job_level' => 'required|in:entry,mid,top',
            'job_nature' => 'required|in:full_time,part_time,contract,internship,freelance',
        ];
    }
}
