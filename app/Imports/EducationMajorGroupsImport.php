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

    private $degreeLevelId;

    public function __construct($degreeLevelId = null)
    {
        $this->degreeLevelId = $degreeLevelId;
    }

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:170',
            '*.sort_order' => 'nullable|integer|min:0',
            '*.is_active' => 'nullable|boolean',
        ];
    }

    public function model(array $row): ?EducationMajorGroup
    {
        $name = trim((string) ($row['name'] ?? $row['major'] ?? ''));

        if (empty($name)) {
            return null;
        }

        $levelId = $this->degreeLevelId !== null && $this->degreeLevelId !== '' 
            ? (int) $this->degreeLevelId 
            : (filled($row['required_degree_level_id'] ?? null) ? (int) $row['required_degree_level_id'] : null);

        $attributes = [
            'required_degree_level_id' => $levelId,
            'name' => $name,
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
