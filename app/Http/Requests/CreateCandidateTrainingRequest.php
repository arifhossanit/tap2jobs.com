<?php

namespace App\Http\Requests;

use App\Models\CandidateTraining;
use Illuminate\Foundation\Http\FormRequest;

class CreateCandidateTrainingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => filled($this->input('title')) ? trim($this->input('title')) : null,
            'country' => filled($this->input('country')) ? trim($this->input('country')) : null,
            'topics' => filled($this->input('topics')) ? trim($this->input('topics')) : null,
            'institute' => filled($this->input('institute')) ? trim($this->input('institute')) : null,
            'duration' => filled($this->input('duration')) ? trim($this->input('duration')) : null,
            'location' => filled($this->input('location')) ? trim($this->input('location')) : null,
        ]);
    }

    public function rules(): array
    {
        $rules = CandidateTraining::$rules;
        $rules['year'] = 'required|integer|min:1900|max:'.date('Y');

        return $rules;
    }
}
