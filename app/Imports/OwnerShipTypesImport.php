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

    private int $importedCount = 0;

    private int $skippedCount = 0;

    private array $seenNames = [];

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:150',
            '*.description' => 'nullable|string',
        ];
    }

    public function model(array $row): ?OwnerShipType
    {
        $name = trim((string) $row['name']);
        $normalizedName = strtolower($name);

        if (isset($this->seenNames[$normalizedName]) || OwnerShipType::where('name', $name)->exists()) {
            $this->skippedCount++;

            return null;
        }

        $this->seenNames[$normalizedName] = true;
        $this->importedCount++;

        return new OwnerShipType([
            'name' => $name,
            'description' => filled(Arr::get($row, 'description')) ? trim((string) $row['description']) : null,
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
