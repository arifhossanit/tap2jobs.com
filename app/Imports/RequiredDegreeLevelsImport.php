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

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:160|unique:education_degree_levels,name',
            '*.code' => 'nullable|string|max:60|unique:education_degree_levels,code',
            '*.show_board' => 'nullable|boolean',
            '*.show_major' => 'nullable|boolean',
            '*.show_summary_checkbox' => 'nullable|boolean',
            '*.sort_order' => 'nullable|integer|min:0',
            '*.is_default' => 'nullable|boolean',
        ];
    }

    public function model(array $row): RequiredDegreeLevel
    {
        return new RequiredDegreeLevel([
            'name' => trim((string) $row['name']),
            'code' => filled(Arr::get($row, 'code')) ? trim((string) $row['code']) : null,
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
}
