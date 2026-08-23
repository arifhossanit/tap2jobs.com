<?php

namespace App\Imports;

use App\Models\City;
use App\Models\State;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;

class CitiesImport implements ToModel, SkipsOnFailure
{
    use SkipsFailures;

    private $stateId;

    private int $importedCount = 0;

    private int $skippedCount = 0;

    private array $seenDistricts = [];

    private array $divisionMap = [
        1 => 'Chattagram',
        2 => 'Rajshahi',
        3 => 'Khulna',
        4 => 'Barisal',
        5 => 'Sylhet',
        6 => 'Dhaka',
        7 => 'Rangpur',
        8 => 'Mymensingh',
    ];

    public function __construct($stateId)
    {
        $this->stateId = $stateId;
    }

    public function model(array $row): ?City
    {
        [$name, $stateId] = $this->extractDistrict($row);

        if (empty($name) || empty($stateId)) {
            $this->skippedCount++;
            return null;
        }

        $attributes = [
            'state_id' => (int) $stateId,
            'name' => $name,
        ];

        $dedupeKey = $attributes['state_id'].'|'.Str::lower($name);
        if (isset($this->seenDistricts[$dedupeKey]) || City::where($attributes)->exists()) {
            $this->skippedCount++;
            return null;
        }

        $this->seenDistricts[$dedupeKey] = true;
        $this->importedCount++;

        return new City($attributes);
    }

    private function extractDistrict(array $row): array
    {
        $row = array_map(fn ($value) => is_string($value) ? trim($value) : $value, $row);

        $name = $row['name'] ?? $row['district'] ?? $row['city'] ?? null;
        $stateId = $row['state_id'] ?? $row['division_id'] ?? null;

        if ($name === null) {
            $values = array_values($row);
            $stateId = $stateId ?? ($values[1] ?? null);
            $name = $values[2] ?? $values[1] ?? $values[0] ?? null;
        }

        $name = trim((string) $name);
        if (in_array(Str::lower($name), ['name', 'district', 'city'], true)) {
            return ['', null];
        }

        return [
            Str::limit($name, 180, ''),
            $this->resolveStateId($stateId),
        ];
    }

    private function resolveStateId($stateId): ?int
    {
        if (filled($stateId) && State::whereKey((int) $stateId)->exists()) {
            return (int) $stateId;
        }

        if (filled($stateId) && isset($this->divisionMap[(int) $stateId])) {
            $matchedStateId = State::where('name', $this->divisionMap[(int) $stateId])->value('id');
            if ($matchedStateId) {
                return (int) $matchedStateId;
            }
        }

        return filled($this->stateId) ? (int) $this->stateId : null;
    }

    public function importedCount(): int
    {
        return $this->importedCount;
    }

    public function skippedCount(): int
    {
        return $this->skippedCount;
    }
}
