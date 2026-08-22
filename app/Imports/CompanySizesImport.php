<?php

namespace App\Imports;

use App\Models\CompanySize;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CompanySizesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function rules(): array
    {
        return [
            '*.size' => 'required|string|max:170',
        ];
    }

    public function model(array $row): ?CompanySize
    {
        $size = trim((string) ($row['size'] ?? $row['name'] ?? ''));

        if (empty($size)) {
            return null;
        }

        if (CompanySize::where('size', $size)->exists()) {
            return null;
        }

        return new CompanySize([
            'size' => $size,
        ]);
    }
}
