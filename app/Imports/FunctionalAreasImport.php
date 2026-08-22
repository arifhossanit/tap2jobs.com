<?php

namespace App\Imports;

use App\Models\FunctionalArea;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class FunctionalAreasImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:150|unique:functional_areas,name',
            '*.is_default' => 'nullable|boolean',
        ];
    }

    public function model(array $row): FunctionalArea
    {
        return new FunctionalArea([
            'name' => trim((string) $row['name']),
            'is_default' => filter_var(Arr::get($row, 'is_default'), FILTER_VALIDATE_BOOLEAN),
        ]);
    }
}
