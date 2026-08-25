<?php

namespace App\Http\Requests;

use App\Models\Candidate;
use App\Models\ProfileReferenceOption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCandidateRequest extends FormRequest
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
        $currentSalary = removeCommaFromNumbers($this->request->get('current_salary'));
        $expectedSalary = removeCommaFromNumbers($this->request->get('expected_salary'));
        $phone = filled($this->request->get('phone')) ? preg_replace('/\D+/', '', (string) $this->request->get('phone')) : null;
        $regionCode = filled($this->request->get('region_code')) ? preg_replace('/\D+/', '', (string) $this->request->get('region_code')) : null;

        $this->request->set('current_salary', $currentSalary);
        $this->request->set('expected_salary', $expectedSalary);
        $this->request->set('phone', $phone);
        $this->request->set('region_code', $regionCode);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = Candidate::$rules;
        $rules['password'] = 'required|same:password_confirmation|min:6';
        $rules['password_confirmation'] = 'required|min:6';
        $rules['gender'] = ['required', Rule::in(ProfileReferenceOption::values(ProfileReferenceOption::TYPE_GENDER))];
        $rules['candidateSkills'] = 'required|array|min:1';
        $rules['candidateSkills.*'] = 'integer|exists:skills,id';
        $rules['candidateLanguage'] = 'required|array|min:1';
        $rules['candidateLanguage.*'] = 'integer|exists:languages,id';
        $rules['phone'] = ['nullable', 'string', 'regex:/^\d{1,11}$/'];
        $rules['region_code'] = ['nullable', 'string', 'regex:/^\d{1,4}$/'];

        return $rules;
    }
}
