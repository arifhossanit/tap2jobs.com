<?php

namespace App\Imports;

use App\Models\Skill;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SkillsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:150|unique:skills,name',
            '*.description' => 'nullable|string',
            '*.is_default' => 'nullable|boolean',
        ];
    }

    public function model(array $row): Skill
    {
        return new Skill([
            'name' => trim((string) $row['name']),
            'description' => filled(Arr::get($row, 'description')) ? trim((string) $row['description']) : null,
            'is_default' => filter_var(Arr::get($row, 'is_default'), FILTER_VALIDATE_BOOLEAN),
        ]);
    }
}
