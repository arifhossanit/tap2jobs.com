<?php

namespace App\Imports;

use App\Models\State;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;

class StatesImport implements ToModel, SkipsOnFailure
{
    use SkipsFailures;

    private $countryId;

    private int $importedCount = 0;

    private int $skippedCount = 0;

    private array $seenNames = [];

    public function __construct($countryId)
    {
        $this->countryId = $countryId;
    }

    public function model(array $row): ?State
    {
        $name = $this->extractName($row);

        if (empty($name)) {
            $this->skippedCount++;

            return null;
        }

        $normalizedName = Str::lower($name);
        $attributes = [
            'country_id' => (int) $this->countryId,
            'name' => $name,
        ];

        if (isset($this->seenNames[$normalizedName]) || State::where($attributes)->exists()) {
            $this->skippedCount++;

            return null;
        }

        $this->seenNames[$normalizedName] = true;
        $this->importedCount++;

        return new State($attributes);
    }

    private function extractName(array $row): string
    {
        $row = array_map(fn ($value) => is_string($value) ? trim($value) : $value, $row);

        $name = $row['name'] ?? $row['division'] ?? $row['district'] ?? null;

        if ($name === null) {
            $values = array_values($row);
            $first = $values[0] ?? null;
            $second = $values[1] ?? null;

            $name = is_numeric($first) && filled($second) ? $second : $first;
        }

        $name = trim((string) $name);

        if (in_array(Str::lower($name), ['name', 'division', 'district'], true)) {
            return '';
        }

        return Str::limit($name, 180, '');
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
