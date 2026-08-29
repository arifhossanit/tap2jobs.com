<?php

namespace App\Http\Requests;

use App\Models\CompanySize;
use App\Rules\ValidCompanySizeRange;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanySizeRequest extends FormRequest
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
        $companySize = $this->route('companySize');
        $companySizeId = is_object($companySize) ? $companySize->id : $companySize;
        $rules = CompanySize::$rules;
        $rules['size'] = [
            'required',
            'unique:company_sizes,size,'.$companySizeId,
            'regex:/^[0-9+\s\-]+$/',
            new ValidCompanySizeRange((int) $companySizeId),
        ];
        $rules['company_category_id'] = ['nullable', 'integer', Rule::exists('company_categories', 'id')];

        return $rules;
    }
}
