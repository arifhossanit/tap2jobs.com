<?php

namespace App\Http\Requests;

use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Foundation\Http\FormRequest;

class CandidateUpdateRelevantInformationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'career_summary' => $this->sanitizeCareerSummary($this->input('career_summary')),
            'special_qualification' => filled($this->input('special_qualification')) ? trim($this->input('special_qualification')) : null,
            'keywords' => filled($this->input('keywords')) ? trim($this->input('keywords')) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'career_summary' => 'nullable|max:3000',
            'special_qualification' => 'nullable|max:2000',
            'keywords' => 'required|max:1000',
        ];
    }

    private function sanitizeCareerSummary(?string $value): ?string
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
