<?php

namespace App\Imports;

use App\Models\CareerLevel;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CareerLevelsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function rules(): array
    {
        return [
            '*.level_name' => 'required|string|max:150|unique:career_levels,level_name',
            '*.is_default' => 'nullable|boolean',
        ];
    }

    public function model(array $row): CareerLevel
    {
        return new CareerLevel([
            'level_name' => trim((string) $row['level_name']),
            'is_default' => filter_var(Arr::get($row, 'is_default'), FILTER_VALIDATE_BOOLEAN),
        ]);
    }
}
