<?php

namespace App\Imports;

use App\Models\Thana;
use App\Models\City;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;

class ThanasImport implements ToModel, SkipsOnFailure
{
    use SkipsFailures;

    private $cityId;

    private int $importedCount = 0;

    private int $skippedCount = 0;

    private array $seenNames = [];

    public function __construct($cityId)
    {
        $this->cityId = $cityId;
    }

    public function model(array $row): ?Thana
    {
        [$name, $cityId] = $this->extractThana($row);

        if (empty($name) || empty($cityId)) {
            $this->skippedCount++;

            return null;
        }

        $normalizedName = (int) $cityId.'|'.Str::lower($name);
        $attributes = [
            'city_id' => (int) $cityId,
            'name' => $name,
        ];

        if (isset($this->seenNames[$normalizedName]) || Thana::where($attributes)->exists()) {
            $this->skippedCount++;

            return null;
        }

        $this->seenNames[$normalizedName] = true;
        $this->importedCount++;

        return new Thana($attributes);
    }

    private function extractThana(array $row): array
    {
        $row = array_map(fn ($value) => is_string($value) ? trim($value) : $value, $row);

        $name = $row['name'] ?? $row['thana'] ?? $row['upazila'] ?? null;
        $cityId = $row['city_id'] ?? $row['district_id'] ?? null;
        $districtName = $row['district_name'] ?? $row['district'] ?? $row['city_name'] ?? $row['city'] ?? null;

        if ($name === null) {
            $values = array_values($row);
            if (count($values) >= 3) {
                $cityId = $cityId ?? ($values[1] ?? null);
                $name = $values[2] ?? null;
            } elseif (count($values) >= 2) {
                $cityId = $cityId ?? ($values[0] ?? null);
                $name = $values[1] ?? null;
            } else {
                $name = $values[0] ?? null;
            }
        }

        $name = trim((string) $name);
        if (in_array(Str::lower($name), ['name', 'thana', 'upazila'], true)) {
            return ['', null];
        }

        return [
            Str::limit($name, 180, ''),
            $this->resolveCityId($cityId, $districtName),
        ];
    }

    private function resolveCityId($cityId, $districtName = null): ?int
    {
        if (filled($cityId) && is_numeric($cityId) && City::whereKey((int) $cityId)->exists()) {
            return (int) $cityId;
        }

        $districtName = $districtName ?: (! is_numeric($cityId) ? $cityId : null);
        if (filled($districtName)) {
            $city = City::where('name', trim((string) $districtName))->first();
            if ($city) {
                return (int) $city->id;
            }

            $normalizedDistrictName = $this->normalizeLocationName($districtName);
            $city = City::get(['id', 'name'])->first(function (City $city) use ($normalizedDistrictName) {
                return $this->normalizeLocationName($city->name) === $normalizedDistrictName;
            });

            if ($city) {
                return (int) $city->id;
            }
        }

        return filled($this->cityId) ? (int) $this->cityId : null;
    }

    private function normalizeLocationName($name): string
    {
        $normalized = Str::lower(preg_replace('/[^a-z0-9]+/i', '', (string) $name));
        $aliases = [
            'comilla' => 'cumilla',
            'chittagong' => 'chattogram',
            'jessore' => 'jashore',
            'bogra' => 'bogura',
            'barisal' => 'barishal',
        ];

        return $aliases[$normalized] ?? $normalized;
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
