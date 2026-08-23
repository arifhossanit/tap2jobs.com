<?php

namespace App\Imports;

use App\Models\RequiredDegreeLevel;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class RequiredDegreeLevelsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    private int $importedCount = 0;

    private int $skippedCount = 0;

    private array $seenNames = [];

    private array $seenCodes = [];

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:160',
            '*.code' => 'nullable|string|max:60',
            '*.show_board' => 'nullable|boolean',
            '*.show_major' => 'nullable|boolean',
            '*.show_summary_checkbox' => 'nullable|boolean',
            '*.sort_order' => 'nullable|integer|min:0',
            '*.is_default' => 'nullable|boolean',
        ];
    }

    public function model(array $row): ?RequiredDegreeLevel
    {
        $name = trim((string) $row['name']);
        $code = filled(Arr::get($row, 'code')) ? trim((string) $row['code']) : null;
        $normalizedName = strtolower($name);
        $normalizedCode = filled($code) ? strtolower((string) $code) : null;

        if (isset($this->seenNames[$normalizedName]) ||
            RequiredDegreeLevel::where('name', $name)->exists() ||
            (filled($normalizedCode) && (
                isset($this->seenCodes[$normalizedCode]) ||
                RequiredDegreeLevel::where('code', $code)->exists()
            ))) {
            $this->skippedCount++;

            return null;
        }

        $this->seenNames[$normalizedName] = true;
        if (filled($normalizedCode)) {
            $this->seenCodes[$normalizedCode] = true;
        }
        $this->importedCount++;

        return new RequiredDegreeLevel([
            'name' => $name,
            'code' => $code,
            'show_board' => $this->toBool(Arr::get($row, 'show_board')),
            'show_major' => $this->toBool(Arr::get($row, 'show_major')),
            'show_summary_checkbox' => $this->toBool(Arr::get($row, 'show_summary_checkbox')),
            'sort_order' => filled(Arr::get($row, 'sort_order')) ? (int) $row['sort_order'] : 0,
            'is_default' => $this->toBool(Arr::get($row, 'is_default')),
        ]);
    }

    private function toBool(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
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
