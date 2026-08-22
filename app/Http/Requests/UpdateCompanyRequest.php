<?php

namespace App\Http\Requests;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $normalized = [
            'phone' => preg_replace('/\D+/', '', (string) $this->input('phone')),
            'region_code' => preg_replace('/\D+/', '', (string) $this->input('region_code')),
            'billing_phone' => preg_replace('/\D+/', '', (string) $this->input('billing_phone')),
            'billing_region_code' => preg_replace('/\D+/', '', (string) $this->input('billing_region_code')),
        ];

        $company = $this->route('company');
        if ($this->routeIs('company.update.form') && $company instanceof Company) {
            $industryIds = array_values(array_filter((array) $this->input('industry_ids')));
            $normalized['industry_id'] = $industryIds[0] ?? null;
        $normalized = [
            'phone' => preg_replace('/\D+/', '', (string) $this->input('phone')),
            'region_code' => preg_replace('/\D+/', '', (string) $this->input('region_code')),
            'billing_phone' => preg_replace('/\D+/', '', (string) $this->input('billing_phone')),
            'billing_region_code' => preg_replace('/\D+/', '', (string) $this->input('billing_region_code')),
        ];

        $company = $this->route('company');
        if ($this->routeIs('company.update.form') && $company instanceof Company) {
            $industryIds = array_values(array_filter((array) $this->input('industry_ids')));
            $normalized['industry_id'] = $industryIds[0] ?? null;
        }

        $this->merge($normalized);
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $company = $this->route('company');

        if (Auth::check() && Auth::user()->hasRole('Employer')) {
            return $company instanceof Company && (int) $company->user_id === (int) Auth::id();
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        /** @var Company|null $company */
        $company = $this->route('company');
        $companyUserId = $company instanceof Company ? $company->user_id : null;
        $rules = Company::$rules;
        $rules['name'] = 'required|string|max:180';
        $rules['email'] = ['required', 'email:filter', Rule::unique('users', 'email')->ignore($companyUserId)];
        $rules['phone'] = ['required', 'string', 'regex:/^\d{4,15}$/'];
        $rules['region_code'] = ['required', 'string', 'regex:/^\d{1,4}$/'];
        $rules['image'] = 'nullable|mimes:jpeg,jpg,png';
        $rules['company_name_bn'] = 'nullable|string|max:180';
        $rules['ceo'] = 'required|string|max:180';
        $rules['company_summary'] = 'nullable|string|max:1000';
        $rules['company_summary_bn'] = 'nullable|string|max:1000';
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
        $rules['industry_id'] = ['required', 'integer', Rule::exists('industries', 'id')];
        $rules['ownership_type_id'] = ['required', 'integer', Rule::exists('ownership_types', 'id')];
        $rules['company_size_id'] = ['required', 'integer', Rule::exists('company_sizes', 'id')];
        $rules['established_in'] = ['required', 'integer', 'between:1800,'.date('Y')];
        $rules['no_of_offices'] = 'required|integer|min:1|max:1000';
        $rules['trade_license_no'] = 'nullable|string|max:100';
        $rules['rl_no'] = ['nullable', 'string', 'max:100', 'regex:/^\d+$/'];

        if ($this->routeIs('company.update.form')) {
            unset($rules['ownership_type_id'], $rules['company_size_id'], $rules['no_of_offices']);

            $rules['contact_person_name'] = 'required|string|max:180';
            $rules['billing_address'] = 'required|string|max:255';
            $rules['employee_range'] = 'required|exists:company_sizes,size';
            $rules['industry_ids'] = 'required|array|min:1';
            $industryExistsRule = Rule::exists('industries', 'id');
            $industryExistsRule->where(function ($query) {
                $query->whereNull('created_by')->orWhere('created_by', Auth::id());
            });
            $rules['industry_ids.*'] = ['integer', $industryExistsRule];
            $rules['industry_id'][] = Rule::in((array) $this->input('industry_ids'));
            $rules['billing_phone'] = ['required', 'string', 'regex:/^\d{4,15}$/'];
            $rules['billing_region_code'] = ['required', 'string', 'regex:/^\d{1,4}$/'];
            $rules['billing_email'] = 'required|email:filter|max:170';
            $rules['has_disability_facilities'] = 'required|boolean';
            $rules['disability_inclusion_policy'] = 'required_if:has_disability_facilities,1|nullable|boolean';
            $rules['disability_inclusion_support'] = 'required_if:disability_inclusion_policy,0|nullable|boolean';
            $rules['disability_inclusion_training'] = 'required_if:has_disability_facilities,1|nullable|boolean';
            $rules['disability_facilities'] = 'nullable|array';
            $rules['disability_facilities.*'] = 'string|in:accessible_documentation,accessible_washrooms,adapted_transport,assistive_software,flexible_shifts,work_from_home,ramps_lifts,reasonable_accommodation,warning_indicators,workstation_adaptations';
        }

        return $rules;
    }
}
