<?php

namespace App\Http\Requests;

use App\Models\CandidateRetiredArmyEmployment;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class CreateCandidateRetiredArmyEmploymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'ba_no_prefix' => filled($this->input('ba_no_prefix')) ? trim($this->input('ba_no_prefix')) : null,
            'ba_no' => filled($this->input('ba_no')) ? trim($this->input('ba_no')) : null,
            'rank' => filled($this->input('rank')) ? trim($this->input('rank')) : null,
            'type' => filled($this->input('type')) ? trim($this->input('type')) : null,
            'arms' => filled($this->input('arms')) ? trim($this->input('arms')) : null,
            'trade' => filled($this->input('trade')) ? trim($this->input('trade')) : null,
            'course' => filled($this->input('course')) ? trim($this->input('course')) : null,
            'date_of_commission' => $this->normalizeDate($this->input('date_of_commission')),
            'date_of_retirement' => $this->normalizeDate($this->input('date_of_retirement')),
        ]);
    }

    public function rules(): array
    {
        return CandidateRetiredArmyEmployment::$rules;
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
}
