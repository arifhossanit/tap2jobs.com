<?php

namespace App\Http\Requests;

use App\Models\CandidateExperience;
use Carbon\Carbon;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateCandidateExperienceRequest
 */
class CreateCandidateExperienceRequest extends FormRequest
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
        $company = filled($this->input('company')) ? trim($this->input('company')) : null;
        $experienceTitle = filled($this->input('experience_title')) ? trim($this->input('experience_title')) : null;
        $companyBusiness = filled($this->input('company_business')) ? trim($this->input('company_business')) : $company;
        $areaOfExpertise = $this->trimArray($this->input('area_of_expertise', []));

        if (empty(array_filter($areaOfExpertise)) && filled($experienceTitle)) {
            $areaOfExpertise = [$experienceTitle];
        }

        $this->merge([
            'currently_working' => $this->has('currently_working'),
            'experience_title' => $experienceTitle,
            'company' => $company,
            'company_business' => $companyBusiness,
            'department' => filled($this->input('department')) ? trim($this->input('department')) : null,
            'company_location' => filled($this->input('company_location')) ? trim($this->input('company_location')) : null,
            'start_date' => $this->normalizeDate($this->input('start_date')),
            'end_date' => $this->has('currently_working') ? null : $this->normalizeDate($this->input('end_date')),
            'description' => $this->sanitizeDescription($this->input('description')),
            'area_of_expertise' => $areaOfExpertise,
            'expertise_duration' => $this->trimArray($this->input('expertise_duration', [])),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return CandidateExperience::$rules;
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

    private function normalizeDate(?string $value): ?string
    {
        if (! filled($value)) {
            return null;
        }

        foreach (['d M Y', 'Y-m-d', 'm/d/Y', 'm/d/y'] as $format) {
            try {
                return Carbon::createFromFormat($format, trim($value))->format('Y-m-d');
            } catch (\Throwable) {
            }
        }

        return $value;
    }

    private function trimArray(array $values): array
    {
        return array_map(function ($value) {
            return filled($value) ? trim($value) : null;
        }, $values);
    }

    private function sanitizeDescription(?string $value): ?string
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
}
