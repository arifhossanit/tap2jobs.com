<?php

namespace App\Http\Requests;

use App\Models\CandidateEducation;
use App\Models\RequiredDegreeLevel;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Foundation\Http\FormRequest;

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
            'result' => filled($this->input('result')) ? trim($this->input('result')) : null,
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
        $rules['year'] = 'nullable|integer|min:1900|max:'.date('Y');

        $levelType = $this->educationLevelType();
        if (in_array($levelType, ['psc', 'jsc'], true)) {
            $rules['board'] = 'required|max:100';
            $rules['major'] = 'nullable|max:150';
        }

        if (in_array($levelType, ['secondary', 'higher_secondary', 'advanced'], true)) {
            $rules['major'] = 'required|max:150';
            $rules['board'] = 'nullable|max:100';
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

    private function educationLevelType(): string
    {
        $levelName = RequiredDegreeLevel::whereKey($this->input('degree_level_id'))->value('name') ?? '';
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

        if (
            str_contains($levelName, 'diploma') ||
            str_contains($levelName, 'bachelor') ||
            str_contains($levelName, 'honors') ||
            str_contains($levelName, 'master') ||
            str_contains($levelName, 'phd') ||
            str_contains($levelName, 'ph.d')
        ) {
            return 'advanced';
        }

        return 'advanced';
    }
}
