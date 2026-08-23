<?php

namespace App\Imports;

use App\Models\SalaryCurrency;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SalaryCurrenciesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    private int $importedCount = 0;

    private int $skippedCount = 0;

    private array $seenNames = [];

    private array $seenCodes = [];

    private array $seenIcons = [];

    public function rules(): array
    {
        return [
            '*.currency_name' => 'required|string|max:150',
            '*.currency_code' => 'required|string|min:3|max:3',
            '*.currency_icon' => 'required|string|max:20',
            '*.is_default' => 'nullable|boolean',
        ];
    }

    public function model(array $row): ?SalaryCurrency
    {
        $currencyName = trim((string) $row['currency_name']);
        $currencyCode = Str::upper(trim((string) $row['currency_code']));
        $currencyIcon = trim((string) $row['currency_icon']);
        $normalizedName = strtolower($currencyName);
        $normalizedCode = strtolower($currencyCode);
        $normalizedIcon = strtolower($currencyIcon);

        if (isset($this->seenNames[$normalizedName]) ||
            isset($this->seenCodes[$normalizedCode]) ||
            isset($this->seenIcons[$normalizedIcon]) ||
            SalaryCurrency::where('currency_name', $currencyName)
                ->orWhere('currency_code', $currencyCode)
                ->orWhere('currency_icon', $currencyIcon)
                ->exists()) {
            $this->skippedCount++;

            return null;
        }

        $this->seenNames[$normalizedName] = true;
        $this->seenCodes[$normalizedCode] = true;
        $this->seenIcons[$normalizedIcon] = true;
        $this->importedCount++;

        return new SalaryCurrency([
            'currency_name' => $currencyName,
            'currency_code' => $currencyCode,
            'currency_icon' => $currencyIcon,
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
