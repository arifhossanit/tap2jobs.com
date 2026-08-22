<?php

namespace App\Imports;

use App\Models\SalaryPeriod;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SalaryPeriodsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function rules(): array
    {
        return [
            '*.period' => 'required|string|max:170',
            '*.description' => 'nullable|string',
        ];
    }

    public function model(array $row): ?SalaryPeriod
    {
        $period = trim((string) ($row['period'] ?? $row['name'] ?? ''));

        if (empty($period)) {
            return null;
        }

        if (SalaryPeriod::where('period', $period)->exists()) {
            return null;
        }

        return new SalaryPeriod([
            'period' => $period,
            'description' => trim((string) ($row['description'] ?? '')),
        ]);
    }
}
