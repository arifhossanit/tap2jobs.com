<?php

namespace App\Imports;

use App\Models\EducationDegreeTitle;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EducationDegreeTitlesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    private $degreeLevelId;

    public function __construct($degreeLevelId)
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

    public function model(array $row): ?EducationDegreeTitle
    {
        $name = trim((string) ($row['name'] ?? $row['title'] ?? ''));

        if (empty($name)) {
            return null;
        }

        $attributes = [
            'required_degree_level_id' => (int) $this->degreeLevelId,
            'name' => $name,
        ];

        if (EducationDegreeTitle::where($attributes)->exists()) {
            return null;
        }

        return new EducationDegreeTitle($attributes + [
            'sort_order' => filled($row['sort_order'] ?? null) ? (int) $row['sort_order'] : 0,
            'is_active' => array_key_exists('is_active', $row) && $row['is_active'] !== null ? (bool) $row['is_active'] : true,
        ]);
    }
}
