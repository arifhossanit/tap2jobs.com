<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CandidateUpdateAddressDetailsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'present_address_type' => 'required|in:inside,outside',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required_if:present_address_type,inside|nullable|exists:states,id',
            'city_id' => 'nullable|exists:cities,id',
            'present_state_division' => 'nullable|max:255',
            'present_post_office' => 'required_if:present_address_type,inside|nullable|max:255',
            'address' => 'required|max:2000',
            'permanent_same_as_present' => 'nullable|boolean',
            'permanent_address_selected' => 'nullable|boolean',
            'permanent_address_type' => 'required_if:permanent_address_selected,1|nullable|in:inside,outside',
            'permanent_country_id' => 'required_if:permanent_address_type,outside|nullable|exists:countries,id',
            'permanent_state_id' => 'nullable|exists:states,id',
            'permanent_state_division' => 'nullable|max:255',
            'permanent_city_id' => 'nullable|exists:cities,id',
            'permanent_post_office' => 'nullable|max:255',
            'permanent_address' => 'nullable|max:2000',
        ];
    }
}
