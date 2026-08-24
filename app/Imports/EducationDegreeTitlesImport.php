<?php

namespace App\Imports;

use App\Models\EducationDegreeTitle;
use App\Models\RequiredDegreeLevel;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EducationDegreeTitlesImport implements ToModel, WithHeadingRow, SkipsOnFailure
{
    use SkipsFailures;

    private $degreeLevelId;

    private int $importedCount = 0;

    private int $skippedCount = 0;

    private int $skippedDuplicateCount = 0;

    private int $skippedInvalidCount = 0;

    private array $seenTitles = [];

    public function __construct($degreeLevelId)
    {
        $this->degreeLevelId = $degreeLevelId;
    }

    public function model(array $row): ?EducationDegreeTitle
    {
        $name = trim((string) ($row['name'] ?? $row['title'] ?? $row['degree_title'] ?? $row['education_degree_title'] ?? ''));
        $degreeLevelId = $this->resolveDegreeLevelId($row, $name);

        if (empty($name) || empty($degreeLevelId)) {
            $this->skippedCount++;
            $this->skippedInvalidCount++;
            return null;
        }

        $attributes = [
            'required_degree_level_id' => (int) $degreeLevelId,
            'name' => $name,
        ];

        $dedupeKey = (int) $degreeLevelId.'|'.strtolower($name);
        if (isset($this->seenTitles[$dedupeKey]) || EducationDegreeTitle::where($attributes)->exists()) {
            $this->skippedCount++;
            $this->skippedDuplicateCount++;
            return null;
        }

        $this->seenTitles[$dedupeKey] = true;
        $this->importedCount++;

        return new EducationDegreeTitle($attributes + [
            'sort_order' => filled($row['sort_order'] ?? null) ? (int) $row['sort_order'] : 0,
            'is_active' => array_key_exists('is_active', $row) && $row['is_active'] !== null
                ? filter_var($row['is_active'], FILTER_VALIDATE_BOOLEAN)
                : true,
        ]);
    }

    private function resolveDegreeLevelId(array $row, string $title = ''): ?int
    {
        $degreeLevelId = $row['required_degree_level_id'] ?? $row['degree_level_id'] ?? null;

        if (filled($degreeLevelId) && is_numeric($degreeLevelId) && RequiredDegreeLevel::whereKey((int) $degreeLevelId)->exists()) {
            return (int) $degreeLevelId;
        }

        $degreeLevel = $row['degree_level'] ?? $row['level'] ?? $row['degree_level_name'] ?? null;
        $degreeLevel = $degreeLevel ?: (! is_numeric($degreeLevelId) ? $degreeLevelId : null);

        if (filled($degreeLevel)) {
            $degreeLevelName = trim((string) $degreeLevel);
            $degreeLevelRecord = RequiredDegreeLevel::where('name', $degreeLevelName)
                ->orWhere('code', $degreeLevelName)
                ->first();

            if ($degreeLevelRecord) {
                return (int) $degreeLevelRecord->id;
            }

            $degreeLevelRecord = $this->findDegreeLevelByNormalizedValue($degreeLevelName);
            if ($degreeLevelRecord) {
                return (int) $degreeLevelRecord->id;
            }

            $degreeLevelCode = $this->resolveDegreeLevelCode($degreeLevelName);
            if ($degreeLevelCode) {
                return (int) $this->findOrCreateDegreeLevelByCode($degreeLevelCode)->id;
            }
        }

        $degreeLevelCode = $this->inferDegreeLevelCodeFromTitle($title);
        if ($degreeLevelCode) {
            return (int) $this->findOrCreateDegreeLevelByCode($degreeLevelCode)->id;
        }

        return filled($this->degreeLevelId) ? (int) $this->degreeLevelId : null;
    }

    private function findOrCreateDegreeLevelByCode(string $code): RequiredDegreeLevel
    {
        $degreeLevelRecord = RequiredDegreeLevel::where('code', $code)->first()
            ?: $this->findDegreeLevelByNormalizedValue($code)
            ?: $this->findDegreeLevelByCodeAlias($code);

        if ($degreeLevelRecord) {
            if (blank($degreeLevelRecord->code)) {
                $degreeLevelRecord->forceFill(['code' => $code])->save();
            }

            return $degreeLevelRecord;
        }

        return RequiredDegreeLevel::create($this->degreeLevelDefaults($code));
    }

    private function findDegreeLevelByNormalizedValue(string $value): ?RequiredDegreeLevel
    {
        $normalizedValue = $this->normalizeDegreeLevel($value);

        return RequiredDegreeLevel::all()->first(function (RequiredDegreeLevel $degreeLevel) use ($normalizedValue) {
            return $normalizedValue === $this->normalizeDegreeLevel((string) $degreeLevel->name)
                || $normalizedValue === $this->normalizeDegreeLevel((string) $degreeLevel->code);
        });
    }

    private function findDegreeLevelByCodeAlias(string $code): ?RequiredDegreeLevel
    {
        $codeAliases = [
            'psc' => ['psc', 'psc 5 pass', '5 pass'],
            'jsc' => ['jsc', 'jsc jdc 8 pass', '8 pass'],
            'secondary' => ['secondary', 'ssc'],
            'higher_secondary' => ['higher secondary', 'hsc'],
            'diploma' => ['diploma'],
            'bachelor' => ['bachelor', 'bachelor honors', 'bachelor honours'],
            'masters' => ['masters', 'master'],
            'phd' => ['phd', 'phd doctor of philosophy', 'doctor of philosophy'],
        ];

        $aliases = $codeAliases[$code] ?? [$code];

        return RequiredDegreeLevel::all()->first(function (RequiredDegreeLevel $degreeLevel) use ($aliases) {
            $name = $this->normalizeDegreeLevel((string) $degreeLevel->name);
            $code = $this->normalizeDegreeLevel((string) $degreeLevel->code);

            foreach ($aliases as $alias) {
                $normalizedAlias = $this->normalizeDegreeLevel($alias);

                if ($name === $normalizedAlias || $code === $normalizedAlias || str_starts_with($name, $normalizedAlias.' ')) {
                    return true;
                }
            }

            return false;
        });
    }

    private function resolveDegreeLevelCode(string $degreeLevel): ?string
    {
        $normalized = $this->normalizeDegreeLevel($degreeLevel);

        $aliases = [
            'psc' => ['psc', '5 pass', 'class 5', 'ebtedayee'],
            'jsc' => ['jsc', 'jdc', '8 pass', 'class 8'],
            'secondary' => ['ssc', 'secondary', 'dakhil', 'o level', 'olevel', 'ssc vocational', 'vocational ssc'],
            'higher_secondary' => ['hsc', 'higher secondary', 'alim', 'a level', 'alevel', 'hsc vocational', 'business management and technology'],
            'diploma' => ['diploma', 'polytechnic'],
            'bachelor' => ['bachelor', 'bachelors', 'honors', 'honours', 'bachelor honors', 'bachelor honours', 'undergraduate', 'fazil', 'mbbs', 'bds', 'dvm', 'llb'],
            'masters' => ['masters', 'master', 'post graduate', 'postgraduate', 'mba', 'emba', 'kamil', 'md', 'ms', 'fcps', 'mcom', 'ma', 'msc', 'mss', 'llm', 'mph'],
            'phd' => ['phd', 'doctor of philosophy', 'mphil', 'm phil', 'dba', 'post doctoral fellowship'],
        ];

        foreach ($aliases as $code => $values) {
            if (in_array($normalized, $values, true)) {
                return $code;
            }
        }

        return null;
    }

    private function inferDegreeLevelCodeFromTitle(string $title): ?string
    {
        $normalized = $this->normalizeDegreeLevel($title);

        $patterns = [
            'psc' => ['psc', 'ebtedayee', 'class 5', '5 pass'],
            'jsc' => ['jsc', 'jdc', 'class 8', '8 pass'],
            'secondary' => ['ssc', 'secondary school certificate', 'dakhil', 'o level', 'olevel'],
            'higher_secondary' => ['hsc', 'higher secondary certificate', 'alim', 'a level', 'alevel'],
            'diploma' => ['diploma'],
            'bachelor' => ['bachelor', 'b a', 'b s s', 'b sc', 'b com', 'bba', 'mbbs', 'bds', 'dvm', 'llb', 'fazil'],
            'masters' => ['master', 'm a', 'm s s', 'm sc', 'm com', 'mba', 'emba', 'llm', 'md', 'fcps', 'kamil'],
            'phd' => ['phd', 'doctor of philosophy', 'mphil', 'm phil', 'dba', 'post doctoral'],
        ];

        foreach ($patterns as $code => $values) {
            foreach ($values as $value) {
                if (str_contains($normalized, $this->normalizeDegreeLevel($value))) {
                    return $code;
                }
            }
        }

        return null;
    }

    private function degreeLevelDefaults(string $code): array
    {
        $defaults = [
            'psc' => ['name' => 'PSC/5 pass', 'code' => 'psc', 'show_board' => true, 'show_major' => false, 'show_summary_checkbox' => false, 'sort_order' => 1, 'is_default' => true],
            'jsc' => ['name' => 'JSC/JDC/8 pass', 'code' => 'jsc', 'show_board' => true, 'show_major' => false, 'show_summary_checkbox' => false, 'sort_order' => 2, 'is_default' => true],
            'secondary' => ['name' => 'Secondary', 'code' => 'secondary', 'show_board' => true, 'show_major' => true, 'show_summary_checkbox' => false, 'sort_order' => 3, 'is_default' => true],
            'higher_secondary' => ['name' => 'Higher Secondary', 'code' => 'higher_secondary', 'show_board' => true, 'show_major' => true, 'show_summary_checkbox' => false, 'sort_order' => 4, 'is_default' => true],
            'diploma' => ['name' => 'Diploma', 'code' => 'diploma', 'show_board' => false, 'show_major' => true, 'show_summary_checkbox' => true, 'sort_order' => 5, 'is_default' => true],
            'bachelor' => ['name' => 'Bachelor/Honors', 'code' => 'bachelor', 'show_board' => false, 'show_major' => true, 'show_summary_checkbox' => true, 'sort_order' => 6, 'is_default' => true],
            'masters' => ['name' => 'Masters', 'code' => 'masters', 'show_board' => false, 'show_major' => true, 'show_summary_checkbox' => true, 'sort_order' => 7, 'is_default' => true],
            'phd' => ['name' => 'PhD (Doctor of Philosophy)', 'code' => 'phd', 'show_board' => false, 'show_major' => true, 'show_summary_checkbox' => false, 'sort_order' => 8, 'is_default' => true],
        ];

        return $defaults[$code];
    }

    private function normalizeDegreeLevel(string $value): string
    {
        $value = strtolower(trim($value));
        $value = str_replace(['.', '_', '-', '/', '(', ')'], ' ', $value);

        return trim(preg_replace('/\s+/', ' ', $value));
    }

    public function importedCount(): int
    {
        return $this->importedCount;
    }

    public function skippedCount(): int
    {
        return $this->skippedCount;
    }

    public function skippedDuplicateCount(): int
    {
        return $this->skippedDuplicateCount;
    }

    public function skippedInvalidCount(): int
    {
        return $this->skippedInvalidCount;
    }
}
