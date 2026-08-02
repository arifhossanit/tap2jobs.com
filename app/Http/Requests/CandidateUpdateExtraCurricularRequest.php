<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CandidateUpdateExtraCurricularRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => 'required|string|max:5000',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $text = trim(strip_tags((string) $this->input('description', '')));

            if ($text === '') {
                $validator->errors()->add('description', 'Extracurricular Activities field is required.');
            }

            if (mb_strlen($text) > 500) {
                $validator->errors()->add('description', 'Extracurricular Activities may not be greater than 500 characters.');
            }
        });
    }
}
