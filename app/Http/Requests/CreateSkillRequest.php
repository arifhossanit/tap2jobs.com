<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CreateSkillRequest extends FormRequest
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
            'name' => 'required|string',
            'description' => 'nullable',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $names = $this->skillNames();

            if (empty($names)) {
                $validator->errors()->add('name', 'The name field is required.');
            }

            if (count($names) > 1000) {
                $validator->errors()->add('name', 'You can add up to 1000 skills at a time.');
            }

            foreach ($names as $name) {
                if (mb_strlen($name) > 150) {
                    $validator->errors()->add('name', 'Each skill name must not be greater than 150 characters.');
                    break;
                }
            }
        });
    }

    public function skillNames(): array
    {
        $rawNames = str_replace(["\r\n", "\n", "\r"], ',', (string) $this->input('name'));

        return array_values(array_unique(array_filter(array_map('trim', explode(',', $rawNames)))));
    }
}
