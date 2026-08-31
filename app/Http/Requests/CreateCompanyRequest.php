<?php

namespace App\Http\Requests;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CreateCompanyRequest extends FormRequest
{
    /**
     * @throws ValidationException
     */
    public function prepareForValidation()
    {
        $employerDetails = trim((string) request()->get('details'));
        $industryIds = collect($this->input('industry_ids', filled($this->input('industry_id')) ? [$this->input('industry_id')] : []))
            ->filter(fn ($id) => filled($id))
            ->unique()
            ->values()
            ->all();
        $hasDisabilityFacilities = $this->boolean('has_disability_facilities');

        $this->merge([
            'phone' => filled($this->input('phone')) ? preg_replace('/\D+/', '', (string) $this->input('phone')) : null,
            'region_code' => filled($this->input('region_code')) ? preg_replace('/\D+/', '', (string) $this->input('region_code')) : null,
            'industry_ids' => $industryIds,
            'industry_id' => $industryIds[0] ?? null,
            'has_disability_facilities' => $hasDisabilityFacilities,
            'disability_inclusion_policy' => $hasDisabilityFacilities ? $this->input('disability_inclusion_policy') : null,
            'disability_inclusion_support' => $hasDisabilityFacilities && (string) $this->input('disability_inclusion_policy') === '0'
                ? $this->input('disability_inclusion_support')
                : null,
            'disability_inclusion_training' => $hasDisabilityFacilities ? $this->input('disability_inclusion_training') : null,
            'disability_facilities' => $hasDisabilityFacilities
                ? collect($this->input('disability_facilities', []))->filter()->unique()->values()->all()
                : [],
        ]);

        if (empty($employerDetails)) {
            throw ValidationException::withMessages([
                'details' => __('messages.employer_details_required'),
            ]);
        }
    }

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
        $rules = Company::$rules;
        $rules['name'] = 'required|max:180';
        $rules['username'] = ['required', 'string', 'max:100', 'regex:/^[\p{L}\p{M}\p{N}._-]+$/u', 'unique:users,username'];
        $rules['email'] = 'required|email:filter|unique:users,email';
        $rules['password'] = 'required|same:password_confirmation|min:6';
        $rules['phone'] = ['required', 'string', 'regex:/^\d{1,11}$/'];
        $rules['region_code'] = ['required', 'string', 'regex:/^\d{1,4}$/'];
        $rules['image'] = 'nullable|mimes:jpg,jpeg,png';
        $rules['company_name_bn'] = 'nullable|string|max:180';
        $rules['contact_person_name'] = 'required|string|max:180';
        $rules['ceo'] = 'required|string|max:180';
        $rules['details'] = 'required|string';
        $rules['location'] = 'required|string|max:255';
        $rules['company_address_bn'] = 'nullable|string|max:1000';
        $rules['country_id'] = ['required', 'integer', Rule::exists('countries', 'id')];
        $rules['state_id'] = [
            'required',
            'integer',
            Rule::exists('states', 'id')->where('country_id', $this->input('country_id')),
        ];
        $rules['city_id'] = [
            'required',
            'integer',
            Rule::exists('cities', 'id')->where('state_id', $this->input('state_id')),
        ];
        $rules['city_village_id'] = [
            'nullable',
            'integer',
            Rule::exists('city_villages', 'id')->where('city_id', $this->input('city_id')),
        ];
        $rules['thana_id'] = [
            'nullable',
            'integer',
            Rule::exists('thanas', 'id')->where(function ($query) {
                if ($this->filled('city_village_id')) {
                    return $query->where('city_village_id', $this->input('city_village_id'));
                }

                return $query->where('city_id', $this->input('city_id'));
            }),
        ];
        $rules['industry_ids'] = 'required|array|min:1';
        $rules['industry_ids.*'] = ['integer', Rule::exists('industries', 'id')];
        $rules['industry_id'] = ['required', 'integer', Rule::exists('industries', 'id'), Rule::in((array) $this->input('industry_ids'))];
        $rules['ownership_type_id'] = ['required', 'integer', Rule::exists('ownership_types', 'id')];
        $rules['company_size_id'] = ['required', 'integer', Rule::exists('company_sizes', 'id')];
        $rules['established_in'] = ['required', 'integer', 'between:1800,'.date('Y')];
        $rules['no_of_offices'] = 'required|integer|min:1|max:1000';
        $rules['trade_license_no'] = 'nullable|string|max:100';
        $rules['rl_no'] = ['nullable', 'string', 'max:100', 'regex:/^\d+$/'];
        $rules['has_disability_facilities'] = 'nullable|boolean';
        $rules['disability_inclusion_policy'] = 'required_if:has_disability_facilities,1|nullable|boolean';
        $rules['disability_inclusion_support'] = 'required_if:disability_inclusion_policy,0|nullable|boolean';
        $rules['disability_inclusion_training'] = 'required_if:has_disability_facilities,1|nullable|boolean';
        $rules['disability_facilities'] = 'nullable|array';
        $rules['disability_facilities.*'] = 'string|in:accessible_documentation,accessible_washrooms,adapted_transport,assistive_software,flexible_shifts,work_from_home,ramps_lifts,reasonable_accommodation,warning_indicators,workstation_adaptations';

        return $rules;
    }

//    /**
//     * @return array|string[]
//     */
//    public function messages()
//    {
//        return [
//            'country_id.required' => 'The country field is required.',
//            'website.url' => ''
//        ];
//    }
}
