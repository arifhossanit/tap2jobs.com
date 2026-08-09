<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ApplyJobRequest
 */
class ApplyJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $expectedSalary = removeCommaFromNumbers($this->request->get('expected_salary'));

        $this->merge([
            'application_type' => strtolower((string) $this->input('application_type')),
            'expected_salary' => $expectedSalary === '' ? null : $expectedSalary,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'application_type' => 'required|in:apply,draft',
            'job_id' => 'required|integer|exists:jobs,id',
            'expected_salary' => 'required_if:application_type,apply|nullable|numeric|min:0|max:9999999999',
            'notes' => 'nullable|string|max:5000',
        ];

        if (getSettingValue('enable_google_recaptcha')) {
            $rules['g-recaptcha-response'] = 'required_if:application_type,apply';
        }

        return $rules;
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'g-recaptcha-response.required' => __('messages.flash.verify_google_recaptcha'),
        ];
    }
}
