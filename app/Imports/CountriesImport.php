<?php

namespace App\Imports;

use App\Models\Country;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CountriesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:180|unique:countries,name',
            '*.short_code' => 'required|string|max:3|unique:countries,short_code',
            '*.phone_code' => 'nullable|numeric|unique:countries,phone_code',
        ];
    }

    public function model(array $row): Country
    {
        return new Country([
            'name' => trim((string) $row['name']),
            'short_code' => Str::upper(trim((string) $row['short_code'])),
            'phone_code' => filled(Arr::get($row, 'phone_code')) ? trim((string) $row['phone_code']) : null,
        ]);
    }
}
