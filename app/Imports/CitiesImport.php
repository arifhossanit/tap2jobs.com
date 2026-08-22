<?php

namespace App\Imports;

use App\Models\City;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CitiesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function rules(): array
    {
        return [
            '*.state_id' => 'required|integer|exists:states,id',
            '*.name' => 'required|string|max:180',
        ];
    }

    public function model(array $row): ?City
    {
        $attributes = [
            'state_id' => (int) $row['state_id'],
            'name' => trim((string) $row['name']),
        ];

        if (City::where($attributes)->exists()) {
            return null;
        }

        return new City($attributes);
    }
}
