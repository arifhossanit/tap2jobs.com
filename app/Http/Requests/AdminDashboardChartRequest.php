<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class AdminDashboardChartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $startDate = Carbon::parse($this->input('start_date'));
                $endDate = Carbon::parse($this->input('end_date'));

                if ($startDate->diffInDays($endDate) > 366) {
                    $validator->errors()->add('end_date', 'The dashboard date range may not exceed 366 days.');
                }
        });
    }
}