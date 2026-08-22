<?php

namespace App\Imports;

use App\Models\OwnerShipType;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class OwnerShipTypesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:150|unique:ownership_types,name',
            '*.description' => 'nullable|string',
        ];
    }

    public function model(array $row): OwnerShipType
    {
        return new OwnerShipType([
            'name' => trim((string) $row['name']),
            'description' => filled(Arr::get($row, 'description')) ? trim((string) $row['description']) : null,
        ]);
    }
}
