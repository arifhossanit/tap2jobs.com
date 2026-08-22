<?php

namespace App\Imports;

use App\Models\EducationMajorGroup;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EducationMajorGroupsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function rules(): array
    {
        return [
            '*.required_degree_level_id' => 'nullable|integer|exists:education_degree_levels,id',
            '*.name' => 'required|string|max:170',
            '*.sort_order' => 'nullable|integer|min:0',
            '*.is_active' => 'nullable|boolean',
        ];
    }

    public function model(array $row): ?EducationMajorGroup
    {
        $attributes = [
            'required_degree_level_id' => filled($row['required_degree_level_id'] ?? null) ? (int) $row['required_degree_level_id'] : null,
            'name' => trim((string) $row['name']),
        ];

        if (EducationMajorGroup::where($attributes)->exists()) {
            return null;
        }

        return new EducationMajorGroup($attributes + [
            'is_custom' => false,
            'sort_order' => filled($row['sort_order'] ?? null) ? (int) $row['sort_order'] : 0,
            'is_active' => array_key_exists('is_active', $row) && $row['is_active'] !== null ? (bool) $row['is_active'] : true,
        ]);
    }
}
