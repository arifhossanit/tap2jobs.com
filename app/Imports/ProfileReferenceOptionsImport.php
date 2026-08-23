<?php

namespace App\Imports;

use App\Models\ProfileReferenceOption;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProfileReferenceOptionsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    private int $importedCount = 0;

    private int $skippedCount = 0;

    private array $seenValues = [];

    public function __construct(private readonly string $scope, private readonly string $type)
    {
    }

    public function rules(): array
    {
        return [
            '*.label' => 'required|string|max:150',
            '*.value' => 'nullable|max:150',
            '*.sort_order' => 'nullable|integer|min:0',
            '*.is_active' => 'nullable|boolean',
        ];
    }

    public function prepareForValidation(array $data, int $index): array
    {
        if (! filled(Arr::get($data, 'value')) && filled(Arr::get($data, 'label'))) {
            $data['value'] = trim((string) $data['label']);
        }

        return $data;
    }

    public function model(array $row): ?ProfileReferenceOption
    {
        $label = trim((string) $row['label']);
        $value = filled(Arr::get($row, 'value')) ? trim((string) $row['value']) : $label;
        $normalizedValue = strtolower($value);
        $table = ProfileReferenceOption::tableFor($this->type);

        if (isset($this->seenValues[$normalizedValue]) ||
            (new ProfileReferenceOption())->setTable($table)->newQuery()
                ->where('scope', $this->scope)
                ->where('value', (string) $value)
                ->exists()) {
            $this->skippedCount++;

            return null;
        }

        $this->seenValues[$normalizedValue] = true;
        $this->importedCount++;

        $record = (new ProfileReferenceOption())->setTable($table);
        $record->fill([
            'scope' => $this->scope,
            'label' => $label,
            'value' => $value,
            'sort_order' => filled(Arr::get($row, 'sort_order')) ? (int) $row['sort_order'] : 0,
            'is_active' => ! array_key_exists('is_active', $row) || filter_var(Arr::get($row, 'is_active'), FILTER_VALIDATE_BOOLEAN),
        ]);

        return $record;
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
