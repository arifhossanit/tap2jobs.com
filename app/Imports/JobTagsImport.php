<?php

namespace App\Imports;

use App\Models\Tag;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class JobTagsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:170',
            '*.description' => 'nullable|string',
        ];
    }

    public function model(array $row): ?Tag
    {
        $name = trim((string) ($row['name'] ?? $row['tag'] ?? ''));

        if (empty($name)) {
            return null;
        }

        if (Tag::where('name', $name)->exists()) {
            return null;
        }

        return new Tag([
            'name' => $name,
            'description' => trim((string) ($row['description'] ?? '')),
        ]);
    }
}
