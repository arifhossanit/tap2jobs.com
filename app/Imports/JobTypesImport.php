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

    private int $importedCount = 0;

    private int $skippedCount = 0;

    private array $seenNames = [];

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:170',
            '*.description' => 'nullable|string',
        ];
    }

    public function model(array $row): ?JobType
    {
        $name = trim((string) ($row['name'] ?? $row['type'] ?? $row['job_type'] ?? $row['job_type_name'] ?? ''));

        if (empty($name)) {
            $this->skippedCount++;
            return null;
        }

        $normalizedName = strtolower($name);
        if (isset($this->seenNames[$normalizedName]) || JobType::where('name', $name)->exists()) {
            $this->skippedCount++;
            return null;
        }

        $this->seenNames[$normalizedName] = true;
        $this->importedCount++;

        return new JobType([
            'name' => $name,
            'description' => trim((string) ($row['description'] ?? '')),
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
