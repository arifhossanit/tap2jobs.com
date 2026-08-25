<?php

namespace App\Http\Requests;

use App\Models\ProfileReferenceOption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CandidateUpdateGeneralInformationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'phone' => filled($this->input('phone')) ? preg_replace('/\D+/', '', (string) $this->input('phone')) : null,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $skillSourceValues = ProfileReferenceOption::values(ProfileReferenceOption::TYPE_SKILL_LEARNING_SOURCE);
        $languageLevelValues = ProfileReferenceOption::values(ProfileReferenceOption::TYPE_LANGUAGE_PROFICIENCY);

        return [
            'candidateSkillsUpdated' => 'nullable|boolean',
            'candidateSkills' => 'nullable|array',
            'candidateSkills.*' => 'nullable|integer|exists:skills,id',
            'candidateSkillNames' => 'nullable|array|max:20',
            'candidateSkillNames.*' => 'required_with:candidateSkillsUpdated|string|max:150',
            'candidateSkillSources' => 'nullable|array',
            'candidateSkillSources.*' => 'nullable|array',
            'candidateSkillSources.*.*' => ['nullable', 'string', Rule::in($skillSourceValues)],
            'candidateLanguageUpdated' => 'nullable|boolean',
            'candidateLanguage' => 'nullable|array',
            'candidateLanguage.*' => 'nullable|integer|exists:languages,id',
            'candidateLanguageNames' => 'nullable|array|max:20',
            'candidateLanguageNames.*' => 'required_with:candidateLanguageUpdated|string|max:150',
            'candidateLanguageLevels' => 'nullable|array',
            'candidateLanguageLevels.*' => ['nullable', 'string', Rule::in(array_unique(array_merge(['Basic', 'Conversational', 'Fluent', 'Native'], $languageLevelValues)))],
            'candidateLanguageReadingLevels' => 'nullable|array',
            'candidateLanguageReadingLevels.*' => ['required_with:candidateLanguageUpdated', 'string', Rule::in($languageLevelValues)],
            'candidateLanguageWritingLevels' => 'nullable|array',
            'candidateLanguageWritingLevels.*' => ['required_with:candidateLanguageUpdated', 'string', Rule::in($languageLevelValues)],
            'candidateLanguageSpeakingLevels' => 'nullable|array',
            'candidateLanguageSpeakingLevels.*' => ['required_with:candidateLanguageUpdated', 'string', Rule::in($languageLevelValues)],
            'first_name' => 'required|max:150',
            'last_name' => 'required|max:150',
            'phone' => ['nullable', 'string', 'regex:/^\d{1,11}$/'],
        ];
    }
}
