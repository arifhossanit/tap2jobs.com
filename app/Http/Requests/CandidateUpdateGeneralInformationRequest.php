<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CandidateUpdateGeneralInformationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'candidateSkillsUpdated' => 'nullable|boolean',
            'candidateSkills' => 'nullable|array',
            'candidateSkills.*' => 'nullable|integer|exists:skills,id',
            'candidateSkillNames' => 'nullable|array|max:20',
            'candidateSkillNames.*' => 'required_with:candidateSkillsUpdated|string|max:150',
            'candidateSkillSources' => 'nullable|array',
            'candidateSkillSources.*' => 'nullable|array',
            'candidateSkillSources.*.*' => 'nullable|string|in:Self,Job,Educational,Professional Training,NTVQF',
            'candidateLanguageUpdated' => 'nullable|boolean',
            'candidateLanguage' => 'nullable|array',
            'candidateLanguage.*' => 'nullable|integer|exists:languages,id',
            'candidateLanguageNames' => 'nullable|array|max:20',
            'candidateLanguageNames.*' => 'required_with:candidateLanguageUpdated|string|max:150',
            'candidateLanguageLevels' => 'nullable|array',
            'candidateLanguageLevels.*' => 'nullable|string|in:Basic,Conversational,Fluent,Native,High,Medium,Low',
            'candidateLanguageReadingLevels' => 'nullable|array',
            'candidateLanguageReadingLevels.*' => 'required_with:candidateLanguageUpdated|string|in:High,Medium,Low',
            'candidateLanguageWritingLevels' => 'nullable|array',
            'candidateLanguageWritingLevels.*' => 'required_with:candidateLanguageUpdated|string|in:High,Medium,Low',
            'candidateLanguageSpeakingLevels' => 'nullable|array',
            'candidateLanguageSpeakingLevels.*' => 'required_with:candidateLanguageUpdated|string|in:High,Medium,Low',
            'first_name' => 'required|max:150',
            'last_name' => 'required|max:150',
            'phone' => 'nullable|min:10|max:10',
        ];
    }
}
