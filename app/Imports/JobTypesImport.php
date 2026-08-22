<?php

namespace App\Imports;

use App\Models\JobType;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class JobTypesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:170',
            '*.description' => 'nullable|string',
        ];
    }

    public function model(array $row): ?JobType
    {
        $name = trim((string) ($row['name'] ?? $row['type'] ?? ''));

        if (empty($name)) {
            return null;
        }

        if (JobType::where('name', $name)->exists()) {
            return null;
        }

        return new JobType([
            'name' => $name,
            'description' => trim((string) ($row['description'] ?? '')),
        ]);
    }
}
