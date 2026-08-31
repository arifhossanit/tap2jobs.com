<?php

namespace App\Http\Requests;

use App\Models\CityVillage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CreateCityVillageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            foreach ($this->cityVillageNames() as $name) {
                if (mb_strlen($name) > 720) {
                    $validator->errors()->add('name', 'Each City/Village name must not be greater than 720 characters.');
                    break;
                }
            }
        });
    }

    public function cityVillageNames(): array
    {
        $rawNames = str_replace(["\r\n", "\n", "\r"], ',', (string) $this->input('name'));

        return array_values(array_unique(array_filter(array_map('trim', explode(',', $rawNames)))));
    }
}
