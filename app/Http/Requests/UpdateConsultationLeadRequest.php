<?php

namespace App\Http\Requests;

use App\Models\ConsultationLead;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateConsultationLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('Admin');
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string', Rule::in(array_keys(ConsultationLead::STATUSES))],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
