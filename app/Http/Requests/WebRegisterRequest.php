<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WebRegisterRequest extends FormRequest
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
        $rules = [
            'privacyPolicy' => 'required',
            'type' => 'required|in:1,2',
        ];

        if ((int) $this->input('type') === 2) {
            $rules = array_merge($rules, [
                'username' => ['required', 'string', 'max:100', 'regex:/^[\p{L}\p{M}\p{N}._-]+$/u', 'unique:users,username'],
                'company_name' => 'required|string|max:180',
                'company_name_bn' => 'nullable|string|max:180',
                'established_in' => 'required|integer|min:1800|max:'.date('Y'),
                'employee_range' => 'required|exists:company_sizes,size',
                'country_id' => 'required|integer|exists:countries,id',
                'state_id' => [
                    'required',
                    'integer',
                    Rule::exists('states', 'id')->where(fn ($query) => $query->where('country_id', $this->input('country_id'))),
                ],
                'city_id' => [
                    'required',
                    'integer',
                    Rule::exists('cities', 'id')->where(fn ($query) => $query->where('state_id', $this->input('state_id'))),
                ],
                'company_address' => 'required|string|max:255',
                'company_address_bn' => 'nullable|string|max:1000',
                'industry_ids' => 'required_without:custom_industries|array',
                'industry_ids.*' => [
                    'integer',
                    Rule::exists('industries', 'id')->where(fn ($query) => $query->whereNull('created_by')),
                ],
                'custom_industries' => 'required_without:industry_ids|array|max:10',
                'custom_industries.*.industry_type_id' => 'required|integer|exists:industry_types,id',
                'custom_industries.*.name' => [
                    'required',
                    'string',
                    'max:150',
                    'distinct',
                    Rule::unique('industries', 'name'),
                ],
                'details' => 'nullable|string|max:5000',
                'trade_license_no' => 'nullable|string|max:100',
                'rl_no' => ['nullable', 'string', 'max:100', 'regex:/^\d+$/'],
                'rl_no' => ['nullable', 'string', 'max:100', 'regex:/^\d+$/'],
                'website' => 'nullable|url|max:255',
                'contact_person_name' => 'required|string|max:180',
                'contact_person_designation' => 'required|string|max:180',
                'email' => 'required|email:filter|max:170|unique:users,email',
                'phone' => ['required', 'string', 'regex:/^\d{4,15}$/'],
                'region_code' => ['required', 'string', 'regex:/^\d{1,4}$/'],
                'password' => 'required|same:password_confirmation|min:6',
                'has_disability_facilities' => 'nullable|boolean',
                'disability_inclusion_policy' => 'required_if:has_disability_facilities,1|nullable|boolean',
                'disability_inclusion_support' => 'required_if:disability_inclusion_policy,0|nullable|boolean',
                'disability_inclusion_training' => 'required_if:has_disability_facilities,1|nullable|boolean',
                'disability_facilities' => 'nullable|array',
                'disability_facilities.*' => 'string|in:accessible_documentation,accessible_washrooms,adapted_transport,assistive_software,flexible_shifts,work_from_home,ramps_lifts,reasonable_accommodation,warning_indicators,workstation_adaptations',
            ]);
        } else {
            $rules = array_merge($rules, [
                'first_name' => 'required|string|max:180',
                'email' => 'required|email:filter|max:170|unique:users,email',
                'phone' => 'required|numeric|unique:users,phone',
                'password' => 'required|same:password_confirmation|min:6',
            ]);
        }

        if (getSettingValue('enable_google_recaptcha')) {
            $rules['g-recaptcha-response'] = 'required';
        }

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        if ((int) $this->input('type') !== 2) {
            return;
        }

        $industryIds = collect($this->input('industry_ids', []))
            ->filter(fn ($id) => filled($id))
            ->unique()
            ->values()
            ->all();
        $customIndustries = collect($this->input('custom_industries', []))
            ->filter(fn ($industry) => is_array($industry) && filled($industry['name'] ?? null))
            ->map(fn ($industry) => [
                'industry_type_id' => $industry['industry_type_id'] ?? null,
                'name' => trim($industry['name']),
            ])
            ->unique(fn ($industry) => mb_strtolower($industry['name']))
            ->values()
            ->all();

        $hasDisabilityFacilities = $this->boolean('has_disability_facilities');
        $disabilityFacilities = $hasDisabilityFacilities
            ? collect($this->input('disability_facilities', []))->filter()->unique()->values()->all()
            : [];
        $phone = preg_replace('/\D+/', '', (string) $this->input('phone'));
        $regionCode = preg_replace('/\D+/', '', (string) $this->input('region_code'));

        $this->merge([
            'first_name' => $this->input('company_name'),
            'industry_ids' => $industryIds,
            'custom_industries' => $customIndustries,
            'has_disability_facilities' => $hasDisabilityFacilities,
            'disability_inclusion_policy' => $hasDisabilityFacilities
                ? $this->input('disability_inclusion_policy')
                : null,
            'disability_inclusion_support' => $hasDisabilityFacilities
                && (string) $this->input('disability_inclusion_policy') === '0'
                    ? $this->input('disability_inclusion_support')
                    : null,
            'disability_inclusion_training' => $hasDisabilityFacilities
                ? $this->input('disability_inclusion_training')
                : null,
            'disability_facilities' => $disabilityFacilities,
            'phone' => $phone,
            'region_code' => $regionCode,
        ]);
    }

    /**
     * @return array|string[]
     */
    public function messages(): array
    {
        return [
            'privacyPolicy.required' => __('messages.terms_and_conditions_required'),
            'g-recaptcha-response.required' => __('messages.verify_captcha'),
        ];
    }
}
