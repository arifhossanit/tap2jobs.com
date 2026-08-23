<?php

namespace App\Imports;

use App\Models\Country;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CountriesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    private int $importedCount = 0;

    private int $skippedCount = 0;

    private array $seenNames = [];

    private array $seenShortCodes = [];

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:180',
            '*.short_code' => 'required|string|max:3',
            '*.phone_code' => 'nullable|numeric',
        ];
    }

    public function model(array $row): ?Country
    {
        $name = trim((string) $row['name']);
        $shortCode = Str::upper(trim((string) $row['short_code']));
        $normalizedName = Str::lower($name);

        if (
            isset($this->seenNames[$normalizedName])
            || isset($this->seenShortCodes[$shortCode])
            || Country::where('name', $name)->orWhere('short_code', $shortCode)->exists()
        ) {
            $this->skippedCount++;

            return null;
        }

        $this->seenNames[$normalizedName] = true;
        $this->seenShortCodes[$shortCode] = true;
        $this->importedCount++;

        return new Country([
            'name' => $name,
            'short_code' => $shortCode,
            'phone_code' => filled(Arr::get($row, 'phone_code')) ? trim((string) $row['phone_code']) : null,
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
