<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CandidateUpdateAwardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:191'],
            'issued_on' => ['required', 'date'],
            'url' => ['nullable', 'url', 'max:191'],
            'description' => ['required', 'string', 'max:5000'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $text = trim(strip_tags((string) $this->input('description', '')));

            if ($text === '') {
                $validator->errors()->add('description', 'Description field is required.');
            }

            if (mb_strlen($text) > 300) {
                $validator->errors()->add('description', 'Description may not be greater than 300 characters.');
            }
        });
    }
}
