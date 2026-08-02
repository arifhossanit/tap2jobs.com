<?php

namespace App\Http\Requests;

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
            'platform' => ['required', 'string', Rule::in(['Facebook', 'GitHub', 'LinkedIn', 'Twitter', 'Website'])],
            'url' => ['required', 'url', 'max:191'],
        ];
    }
}
