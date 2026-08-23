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

    private int $importedCount = 0;

    private int $skippedCount = 0;

    private array $seenNames = [];

    public function rules(): array
    {
        return [
            '*.level_name' => 'required|string|max:150',
            '*.is_default' => 'nullable|boolean',
        ];
    }

    public function model(array $row): ?CareerLevel
    {
        $levelName = trim((string) $row['level_name']);
        $normalizedName = strtolower($levelName);

        if (isset($this->seenNames[$normalizedName]) || CareerLevel::where('level_name', $levelName)->exists()) {
            $this->skippedCount++;

            return null;
        }

        $this->seenNames[$normalizedName] = true;
        $this->importedCount++;

        return new CareerLevel([
            'level_name' => $levelName,
            'is_default' => filter_var(Arr::get($row, 'is_default'), FILTER_VALIDATE_BOOLEAN),
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
