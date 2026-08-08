<?php

namespace App\Http\Requests;

use App\Models\Candidate;
use App\Models\JobApplication;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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

        $this->request->set('expected_salary', $expectedSalary);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'job_id' => 'required',
            'resume_id' => [
                'required',
                Rule::exists('media', 'id')->where(function ($query) {
                    return $query->where('model_type', Candidate::class)
                        ->where('model_id', Auth::user()->owner_id)
                        ->where('collection_name', Candidate::RESUME_PATH);
                }),
            ],
            'expected_salary' => 'required|numeric|min:0|max:9999999999',
        ];

        if (getSettingValue('enable_google_recaptcha')) {
            $rules['g-recaptcha-response'] = 'required';
        }

        return $rules;
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'resume_id.required' => __('messages.flash.resume_field_required'),
            'g-recaptcha-response.required' => __('messages.flash.verify_google_recaptcha'),
        ];
    }
}
