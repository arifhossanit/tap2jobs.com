<?php

namespace App\Imports;

use App\Models\City;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CitiesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    private $stateId;

    public function __construct($stateId)
    {
        $this->stateId = $stateId;
    }

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:180',
        ];
    }

    public function model(array $row): ?City
    {
        $name = trim((string) ($row['name'] ?? $row['city'] ?? ''));

        if (empty($name)) {
            return null;
        }

        $attributes = [
            'state_id' => (int) $this->stateId,
            'name' => $name,
        ];

        if (City::where($attributes)->exists()) {
            return null;
        }

        return new City($attributes);
    }
}
