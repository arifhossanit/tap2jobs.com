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

    private int $importedCount = 0;

    private int $skippedCount = 0;

    private array $seenNames = [];

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:150',
            '*.description' => 'nullable|string',
            '*.industry_type_id' => 'nullable|integer|exists:industry_types,id',
            '*.is_default' => 'nullable|boolean',
        ];
    }

    public function model(array $row): ?Industry
    {
        $name = trim((string) $row['name']);
        $normalizedName = strtolower($name);

        if (isset($this->seenNames[$normalizedName]) || Industry::where('name', $name)->exists()) {
            $this->skippedCount++;

            return null;
        }

        $this->seenNames[$normalizedName] = true;
        $this->importedCount++;

        return new Industry([
            'name' => $name,
            'description' => filled(Arr::get($row, 'description')) ? trim((string) $row['description']) : $name,
            'industry_type_id' => filled(Arr::get($row, 'industry_type_id')) ? (int) $row['industry_type_id'] : null,
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
