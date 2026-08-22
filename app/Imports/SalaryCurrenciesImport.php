<?php

namespace App\Imports;

use App\Models\SalaryCurrency;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SalaryCurrenciesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function rules(): array
    {
        return [
            '*.currency_name' => 'required|string|max:150|unique:salary_currencies,currency_name',
            '*.currency_code' => 'required|string|min:3|max:3|unique:salary_currencies,currency_code',
            '*.currency_icon' => 'required|string|max:20|unique:salary_currencies,currency_icon',
            '*.is_default' => 'nullable|boolean',
        ];
    }

    public function model(array $row): SalaryCurrency
    {
        return new SalaryCurrency([
            'currency_name' => trim((string) $row['currency_name']),
            'currency_code' => Str::upper(trim((string) $row['currency_code'])),
            'currency_icon' => trim((string) $row['currency_icon']),
            'is_default' => filter_var(Arr::get($row, 'is_default'), FILTER_VALIDATE_BOOLEAN),
        ]);
    }
}
