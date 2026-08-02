<?php

namespace App\Http\Requests;

use App\Models\CandidateEducation;
use App\Models\RequiredDegreeLevel;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

/**
 * Class CreateCandidateEducationRequest
 */
class CreateCandidateEducationRequest extends FormRequest
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
            'show_summary' => $this->has('show_summary'),
            'foreign_institute' => $this->has('foreign_institute'),
            'degree_title' => filled($this->input('degree_title')) ? trim($this->input('degree_title')) : null,
            'major' => filled($this->input('major')) ? trim($this->input('major')) : null,
            'board' => filled($this->input('board')) ? trim($this->input('board')) : null,
            'institute' => filled($this->input('institute')) ? trim($this->input('institute')) : null,
            'foreign_university_country' => filled($this->input('foreign_university_country')) ? trim($this->input('foreign_university_country')) : null,
            'result' => filled($this->input('result')) ? trim($this->input('result')) : null,
            'marks_percentage' => filled($this->input('marks_percentage')) ? trim($this->input('marks_percentage')) : null,
            'duration' => filled($this->input('duration')) ? trim($this->input('duration')) : null,
            'achievement' => $this->sanitizeAchievement($this->input('achievement')),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = CandidateEducation::$rules;
        $degreeLevelId = $this->input('degree_level_id');
        $rules['foreign_university_country'] = 'required_if:foreign_institute,1|nullable|max:120';
        $rules['result'] = 'required|max:150';
        $rules['marks_percentage'] = 'required_if:result,First Division/Class,Second Division/Class,Third Division/Class|nullable|numeric|min:0|max:100';
        $rules['cgpa'] = 'required_if:result,Grade|nullable|numeric|min:0|max:100';
        $rules['scale'] = 'required_if:result,Grade|nullable|integer|min:1|max:100';
        $rules['year'] = 'required|integer|min:1900|max:'.$this->maxEducationYear();

        if (Schema::hasTable('education_degree_titles')) {
            $rules['degree_title'] = [
                'required',
                'max:150',
                Rule::exists('education_degree_titles', 'name')->where(function ($query) use ($degreeLevelId) {
                    return $query
                        ->where('required_degree_level_id', $degreeLevelId)
                        ->where('is_active', true);
                }),
            ];
        }

        $levelType = $this->educationLevelType();
        if (in_array($levelType, ['psc', 'jsc'], true)) {
            $rules['board'] = 'required|max:100';
            $rules['major'] = 'nullable|max:150';
        }

        if (in_array($levelType, ['secondary', 'higher_secondary'], true)) {
            $rules['major'] = 'required|max:150';
            $rules['board'] = 'required|max:100';
        }

        if (in_array($levelType, ['diploma', 'bachelor', 'masters', 'phd'], true)) {
            $rules['major'] = 'required|max:150';
            $rules['board'] = 'nullable|max:100';
        }

        if (Schema::hasTable('education_boards')) {
            $boardRule = Rule::exists('education_boards', 'name')->where('is_active', true);
            $rules['board'] = str_starts_with((string) $rules['board'], 'required')
                ? ['required', 'max:100', $boardRule]
                : ['nullable', 'max:100', $boardRule];
        }

        return $rules;
    }

    /**
     * @return array|string[]
     */
    public function messages(): array
    {
        return [
            'country_id.required' => __('messages.country_id_required'),
        ];
    }

    private function sanitizeAchievement(?string $value): ?string
    {
        if (! filled($value)) {
            return null;
        }

        $config = HTMLPurifier_Config::createDefault();
        $config->set('Core.Encoding', 'UTF-8');
        $config->set('HTML.Allowed', 'p,b,strong,i,em,ul,ol,li,br');
        $config->set('AutoFormat.AutoParagraph', false);
        $config->set('AutoFormat.RemoveEmpty', true);
        $config->set('Cache.DefinitionImpl', null);

        $cleanValue = (new HTMLPurifier($config))->purify($value);

        return filled(strip_tags($cleanValue)) ? $cleanValue : null;
    }

    private function maxEducationYear(): int
    {
        if ($this->input('result') === 'Appeared') {
            return (int) date('Y') + 10;
        }

        return (int) date('Y');
    }

    private function educationLevelType(): string
    {
        $level = RequiredDegreeLevel::whereKey($this->input('degree_level_id'))->first();
        if ($level && filled($level->code)) {
            return $level->code;
        }

        $levelName = $level->name ?? '';
        $levelName = strtolower($levelName);

        if (str_contains($levelName, 'psc') || str_contains($levelName, '5 pass')) {
            return 'psc';
        }

        if (str_contains($levelName, 'jsc') || str_contains($levelName, 'jdc') || str_contains($levelName, '8 pass')) {
            return 'jsc';
        }

        if (str_contains($levelName, 'higher secondary') || str_contains($levelName, 'hsc')) {
            return 'higher_secondary';
        }

        if (str_contains($levelName, 'secondary') || str_contains($levelName, 'ssc')) {
            return 'secondary';
        }

        if (str_contains($levelName, 'diploma')) {
            return 'diploma';
        }

        if (str_contains($levelName, 'bachelor') || str_contains($levelName, 'honors')) {
            return 'bachelor';
        }

        if (str_contains($levelName, 'master')) {
            return 'masters';
        }

        if (str_contains($levelName, 'phd') || str_contains($levelName, 'ph.d')) {
            return 'phd';
        }

        return 'default';
    }
}
