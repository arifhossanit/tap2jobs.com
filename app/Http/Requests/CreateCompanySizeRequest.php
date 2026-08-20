<?php

namespace App\Http\Requests;

use App\Models\CompanySize;
use App\Rules\ValidCompanySizeRange;
use Illuminate\Foundation\Http\FormRequest;

class CreateCompanySizeRequest extends FormRequest
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
        $rules = CompanySize::$rules;
        $rules['size'] = [
            'required',
            'unique:company_sizes,size',
            'regex:/^[0-9+\s\-]+$/',
            new ValidCompanySizeRange(),
        ];

        return $rules;
    }
}
