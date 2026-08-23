<?php

namespace App\Http\Requests;

use App\Models\Thana;
use Illuminate\Foundation\Http\FormRequest;

class CreateThanaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return Thana::$rules;
    }
}
