<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ValidatesJob;
use App\Models\Job;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateJobRequest extends FormRequest
{
    use ValidatesJob;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $job = $this->route('job');

        if (Auth::check() && Auth::user()->hasRole('Employer')) {
            return $job instanceof Job && (int) $job->company_id === (int) Auth::user()->owner_id;
        }

        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $job = $this->route('job');

        if ($job instanceof Job) {
            $savedLocation = [];

            foreach (['country_id', 'state_id', 'city_id', 'thana_id'] as $field) {
                if (! $this->filled($field) && $job->{$field} !== null) {
                    $savedLocation[$field] = $job->{$field};
                }
            }

            $this->merge($savedLocation);
        }

        $this->prepareJobForValidation();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return $this->jobRules();
    }

    public function messages(): array
    {
        return $messages = [
            'state_id.required' => __('messages.state_required'),
        ];
    }
}
