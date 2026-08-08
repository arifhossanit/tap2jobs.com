<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ValidatesJob;
use Illuminate\Foundation\Http\FormRequest;

class CreateJobRequest extends FormRequest
{
    use ValidatesJob;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->prepareJobForValidation();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return $this->jobRules();
    }
}
