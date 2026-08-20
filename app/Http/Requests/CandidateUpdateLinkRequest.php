<?php

namespace App\Http\Requests;

use App\Models\ProfileReferenceOption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CandidateUpdateLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'platform' => ['required', 'string', Rule::in(ProfileReferenceOption::values(ProfileReferenceOption::TYPE_ONLINE_PROFILE_PLATFORM))],
            'url' => ['required', 'url', 'max:191'],
        ];
    }
}
