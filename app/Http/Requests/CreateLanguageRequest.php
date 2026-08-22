<?php

namespace App\Http\Requests;

use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;

class CreateLanguageRequest extends FormRequest
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
            'language' => 'required|string',
            'iso_code' => 'nullable|string|max:150',
        ];
    }

    public function messages(): array
    {
        $messages['iso_code.required'] = __('messages.iso_code_required');

        return $messages;
    }
}
