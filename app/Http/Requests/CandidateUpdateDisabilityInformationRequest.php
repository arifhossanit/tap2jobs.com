<?php

namespace App\Http\Requests;

use App\Models\ProfileReferenceOption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CandidateUpdateDisabilityInformationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'has_disability_id' => $this->has('has_disability_id') ? $this->input('has_disability_id') : null,
            'disability_id_number' => filled($this->input('disability_id_number')) ? trim($this->input('disability_id_number')) : null,
            'disability_id_show_on_profile' => $this->has('disability_id_show_on_profile') ? $this->input('disability_id_show_on_profile') : null,
        ]);
    }

    public function rules(): array
    {
        $difficultyRule = ['nullable', Rule::in(ProfileReferenceOption::values(ProfileReferenceOption::TYPE_DISABILITY_DIFFICULTY))];

        return [
            'has_disability_id' => 'required|boolean',
            'disability_id_number' => 'required_if:has_disability_id,1|nullable|max:100',
            'disability_id_show_on_profile' => 'required_if:has_disability_id,1|nullable|boolean',
            'disability_difficulty_seeing' => $difficultyRule,
            'disability_difficulty_hearing' => $difficultyRule,
            'disability_difficulty_remembering' => $difficultyRule,
            'disability_difficulty_walking' => $difficultyRule,
            'disability_difficulty_communicating' => $difficultyRule,
            'disability_difficulty_self_care' => $difficultyRule,
        ];
    }
}
