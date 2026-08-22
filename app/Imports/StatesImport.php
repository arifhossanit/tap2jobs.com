<?php

namespace App\Imports;

use App\Models\State;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StatesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function rules(): array
    {
        return [
            '*.country_id' => 'required|integer|exists:countries,id',
            '*.name' => 'required|string|max:180',
        ];
    }

    public function model(array $row): ?State
    {
        $attributes = [
            'country_id' => (int) $row['country_id'],
            'name' => trim((string) $row['name']),
        ];

        if (State::where($attributes)->exists()) {
            return null;
        }

        return new State($attributes);
    }
}
