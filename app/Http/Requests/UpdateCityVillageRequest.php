<?php

namespace App\Http\Requests;

use App\Models\CityVillage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCityVillageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'city_id' => 'required|exists:cities,id',
            'name' => [
                'required',
                'max:720',
                Rule::unique('city_villages', 'name')
                    ->where('city_id', $this->input('city_id'))
                    ->ignore($this->route('cityVillage')->id),
            ],
        ];
    }
}
