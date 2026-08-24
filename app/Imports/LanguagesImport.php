<?php

namespace App\Imports;

use App\Models\Language;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class LanguagesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    private int $importedCount = 0;

    private int $skippedCount = 0;

    private array $seenLanguages = [];

    private array $seenIsoCodes = [];

    public function rules(): array
    {
        return [
            '*.language' => 'required|string|max:150',
            '*.iso_code' => 'nullable|string|max:150',
        ];
    }

    public function model(array $row): ?Language
    {
        $name = trim((string) ($row['language'] ?? $row['name'] ?? $row['language_name'] ?? ''));

        if (empty($name)) {
            $this->skippedCount++;
            return null;
        }

        $normalizedName = strtolower($name);
        if (isset($this->seenLanguages[$normalizedName]) || Language::where('language', $name)->exists()) {
            $this->skippedCount++;
            return null;
        }

        $givenIso = trim((string) ($row['iso_code'] ?? ''));

        if (! empty($givenIso)) {
            $isoCode = $this->uniqueIsoCode(strtolower($givenIso), $name);
        } else {
            $cleanName = preg_replace('/[^a-zA-Z]/', '', $name);
            $isoCode = strtolower(substr($cleanName, 0, 2));
            if (strlen($isoCode) < 2) {
                $isoCode = strtolower(substr(md5($name), 0, 2));
            }

            $isoCode = $this->uniqueIsoCode($isoCode, $name);
        }

        $path = base_path('lang/').$isoCode;
        if (! \File::exists($path)) {
            \File::makeDirectory($path, 0755, true, true);
        }

        $this->seenLanguages[$normalizedName] = true;
        $this->seenIsoCodes[strtolower($isoCode)] = true;
        $this->importedCount++;

        return new Language([
            'language' => $name,
            'iso_code' => $isoCode,
        ]);
    }

    private function uniqueIsoCode(string $isoCode, string $language): string
    {
        $isoCode = preg_replace('/[^a-z0-9_-]/', '', strtolower($isoCode)) ?: strtolower(substr(md5($language), 0, 2));
        $originalIso = $isoCode;
        $counter = 1;

        while (
            isset($this->seenIsoCodes[$isoCode]) ||
            Language::where('iso_code', $isoCode)->where('language', '!=', $language)->exists()
        ) {
            $isoCode = $originalIso.$counter;
            $counter++;
        }

        return $isoCode;
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
