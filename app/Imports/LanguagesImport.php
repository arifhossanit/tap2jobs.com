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

    public function rules(): array
    {
        return [
            '*.language' => 'required|string|max:150',
            '*.iso_code' => 'nullable|string|max:150',
        ];
    }

    public function model(array $row): ?Language
    {
        $name = trim((string) ($row['language'] ?? $row['name'] ?? ''));

        if (empty($name)) {
            return null;
        }

        if (Language::where('language', $name)->exists()) {
            return null;
        }

        $givenIso = trim((string) ($row['iso_code'] ?? ''));

        if (! empty($givenIso)) {
            $isoCode = strtolower($givenIso);
        } else {
            $cleanName = preg_replace('/[^a-zA-Z]/', '', $name);
            $isoCode = strtolower(substr($cleanName, 0, 2));
            if (strlen($isoCode) < 2) {
                $isoCode = strtolower(substr(md5($name), 0, 2));
            }

            $originalIso = $isoCode;
            $counter = 1;
            while (Language::where('iso_code', $isoCode)->exists()) {
                $isoCode = $originalIso.$counter;
                $counter++;
            }
        }

        $path = base_path('lang/').$isoCode;
        if (! \File::exists($path)) {
            \File::makeDirectory($path, 0755, true, true);
        }

        return new Language([
            'language' => $name,
            'iso_code' => $isoCode,
        ]);
    }
}
