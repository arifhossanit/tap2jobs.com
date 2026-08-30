<?php

namespace App\Http\Requests;

use App\Models\ProfileReferenceOption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreConsultationLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ad_id' => ['nullable', 'integer', 'exists:ads,id'],
            'company_size_id' => ['required', 'integer', 'exists:company_sizes,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'consultation_type' => [
                'required',
                'string',
                Rule::in(ProfileReferenceOption::values(
                    ProfileReferenceOption::TYPE_CONSULTATION_TYPE,
                    [ProfileReferenceOption::SCOPE_EMPLOYER]
                )),
            ],
            'preferred_contact_method' => [
                'nullable',
                'string',
                Rule::in(ProfileReferenceOption::values(
                    ProfileReferenceOption::TYPE_CONSULTATION_CONTACT_METHOD,
                    [ProfileReferenceOption::SCOPE_EMPLOYER]
                )),
            ],
            'preferred_contact_time' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:5000'],
            'source_page' => ['nullable', 'string', 'max:255'],
            'clicked_url' => ['nullable', 'string', 'max:255'],
            'utm_source' => ['nullable', 'string', 'max:255'],
            'utm_medium' => ['nullable', 'string', 'max:255'],
            'utm_campaign' => ['nullable', 'string', 'max:255'],
        ];
    }
}
