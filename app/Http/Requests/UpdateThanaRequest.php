<?php

namespace App\Http\Requests;

use App\Models\Thana;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateThanaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = Thana::$rules;
        $rules['name'] = [
            'required',
            'max:720',
            Rule::unique('thanas', 'name')
                ->where('city_id', $this->input('city_id'))
                ->ignore($this->route('thana')->id),
        ];

        return $rules;
    }
}
