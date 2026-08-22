<?php

namespace App\Imports;

use App\Models\Industry;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class IndustriesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:150|unique:industries,name',
            '*.description' => 'nullable|string',
            '*.industry_type_id' => 'nullable|integer|exists:industry_types,id',
            '*.is_default' => 'nullable|boolean',
        ];
    }

    public function model(array $row): Industry
    {
        return new Industry([
            'name' => trim((string) $row['name']),
            'description' => filled(Arr::get($row, 'description')) ? trim((string) $row['description']) : trim((string) $row['name']),
            'industry_type_id' => filled(Arr::get($row, 'industry_type_id')) ? (int) $row['industry_type_id'] : null,
            'is_default' => filter_var(Arr::get($row, 'is_default'), FILTER_VALIDATE_BOOLEAN),
        ]);
    }
}
