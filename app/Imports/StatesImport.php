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

    private $countryId;

    public function __construct($countryId)
    {
        $this->countryId = $countryId;
    }

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:180',
        ];
    }

    public function model(array $row): ?State
    {
        $name = trim((string) ($row['name'] ?? $row['district'] ?? ''));

        if (empty($name)) {
            return null;
        }

        $attributes = [
            'country_id' => (int) $this->countryId,
            'name' => $name,
        ];

        if (State::where($attributes)->exists()) {
            return null;
        }

        return new State($attributes);
    }
}
