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

    private int $importedCount = 0;

    private int $skippedCount = 0;

    private array $seenNames = [];

    public function rules(): array
    {
        return [
            '*.name' => 'nullable',
            '*.description' => 'nullable|string',
            '*.is_featured' => 'nullable',
        ];
    }

    public function model(array $row): ?JobCategory
    {
        $name = trim((string) ($row['name'] ?? $row['category'] ?? $row['job_category'] ?? $row['job_category_name'] ?? ''));

        if (empty($name)) {
            $this->skippedCount++;
            return null;
        }

        $normalizedName = strtolower($name);
        if (isset($this->seenNames[$normalizedName]) || JobCategory::where('name', $name)->exists()) {
            $this->skippedCount++;
            return null;
        }

        $this->seenNames[$normalizedName] = true;
        $this->importedCount++;

        return new JobCategory([
            'name' => $name,
            'description' => trim((string) ($row['description'] ?? '')),
            'is_featured' => array_key_exists('is_featured', $row) && $row['is_featured'] !== null
                ? filter_var($row['is_featured'], FILTER_VALIDATE_BOOLEAN)
                : false,
        ]);
    }

    public function importedCount(): int
    {
        return $this->importedCount;
    }

    public function skippedCount(): int
    {
        return $this->skippedCount;
    }
}
