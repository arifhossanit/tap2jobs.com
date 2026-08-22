<?php

namespace App\Imports;

use App\Models\JobCategory;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class JobCategoriesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function rules(): array
    {
        return [
            '*.name' => 'nullable',
        ];
    }

    public function model(array $row): ?JobCategory
    {
        $name = trim((string) ($row['name'] ?? $row['category'] ?? ''));

        if (empty($name)) {
            return null;
        }

        if (JobCategory::where('name', $name)->exists()) {
            return null;
        }

        return new JobCategory([
            'name' => $name,
            'description' => trim((string) ($row['description'] ?? '')),
            'is_featured' => array_key_exists('is_featured', $row) && $row['is_featured'] !== null ? (bool) $row['is_featured'] : false,
        ]);
    }
}
