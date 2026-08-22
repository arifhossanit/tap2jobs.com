<?php

namespace App\Imports;

use App\Models\EducationBoard;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EducationBoardsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:120|unique:education_boards,name',
            '*.sort_order' => 'nullable|integer|min:0',
            '*.is_active' => 'nullable|boolean',
        ];
    }

    public function model(array $row): EducationBoard
    {
        return new EducationBoard([
            'name' => trim((string) $row['name']),
            'sort_order' => filled($row['sort_order'] ?? null) ? (int) $row['sort_order'] : 0,
            'is_active' => array_key_exists('is_active', $row) && $row['is_active'] !== null ? (bool) $row['is_active'] : true,
        ]);
    }
}
