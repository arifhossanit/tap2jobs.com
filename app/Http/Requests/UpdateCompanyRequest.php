<?php

namespace App\Http\Requests;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UpdateCompanyRequest extends FormRequest
{
    /**
     * @throws ValidationException
     */
    public function prepareForValidation()
    {
        $employerDetails = trim(request()->get('details'));
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
        $rules['email'] = 'required|email:filter|unique:users,email,'.$this->input('user_id');
        $rules['password'] = 'nullable|same:password_confirmation|min:6';
        $rules['phone'] = 'nullable';
        $rules['image'] = 'nullable|mimes:jpeg,jpg,png';
        $rules['company_name_bn'] = 'nullable|string|max:180';
        $rules['company_summary'] = 'nullable|string|max:1000';
        $rules['company_summary_bn'] = 'nullable|string|max:1000';
        $rules['trade_license_no'] = 'nullable|string|max:100';
        $rules['rl_no'] = 'nullable|string|max:100';
        $rules['employee_range'] = 'required|in:1-25,26-50,51-100,101-500,501-1000,1000+';
        $rules['industry_ids'] = 'required|array|min:1';
        $industryExistsRule = Rule::exists('industries', 'id');
        if (Auth::check() && Auth::user()->hasRole('Employer')) {
            $industryExistsRule->where(function ($query) {
                $query->whereNull('created_by')->orWhere('created_by', Auth::id());
            });
        }
        $rules['industry_ids.*'] = ['integer', $industryExistsRule];
        $rules['billing_phone'] = 'required|string|max:30';
        $rules['billing_region_code'] = 'required|string|max:10';
        $rules['billing_email'] = 'required|email:filter|max:170';
        $rules['has_disability_facilities'] = 'required|boolean';
        $rules['disability_inclusion_policy'] = 'required_if:has_disability_facilities,1|nullable|boolean';
        $rules['disability_inclusion_support'] = 'required_if:disability_inclusion_policy,0|nullable|boolean';
        $rules['disability_inclusion_training'] = 'required_if:has_disability_facilities,1|nullable|boolean';
        $rules['disability_facilities'] = 'nullable|array';
        $rules['disability_facilities.*'] = 'string|in:accessible_documentation,accessible_washrooms,adapted_transport,assistive_software,flexible_shifts,work_from_home,ramps_lifts,reasonable_accommodation,warning_indicators,workstation_adaptations';

        return $rules;
    }
}
