<?php

namespace App\Imports;

use App\Models\JobShift;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class JobShiftsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function rules(): array
    {
        return [
            '*.shift' => 'required|string|max:170',
            '*.description' => 'nullable|string',
        ];
    }

    public function model(array $row): ?JobShift
    {
        $shift = trim((string) ($row['shift'] ?? $row['name'] ?? ''));

        if (empty($shift)) {
            return null;
        }

        if (JobShift::where('shift', $shift)->exists()) {
            return null;
        }

        return new JobShift([
            'shift' => $shift,
            'description' => trim((string) ($row['description'] ?? '')),
        ]);
    }
}
