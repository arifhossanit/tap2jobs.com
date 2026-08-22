<?php

namespace App\Imports;

use App\Models\ProfileReferenceOption;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProfileReferenceOptionsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function __construct(private readonly string $scope, private readonly string $type)
    {
    }

    public function rules(): array
    {
        return [
            '*.label' => 'required|string|max:150',
            '*.value' => [
                'nullable',
                'string',
                'max:150',
                Rule::unique(ProfileReferenceOption::tableFor($this->type), 'value')
                    ->where(fn ($query) => $query->where('scope', $this->scope)),
            ],
            '*.sort_order' => 'nullable|integer|min:0',
            '*.is_active' => 'nullable|boolean',
        ];
    }

    public function prepareForValidation(array $data, int $index): array
    {
        if (! filled(Arr::get($data, 'value')) && filled(Arr::get($data, 'label'))) {
            $data['value'] = trim((string) $data['label']);
        }

        return $data;
    }

    public function model(array $row): ProfileReferenceOption
    {
        $label = trim((string) $row['label']);
        $value = filled(Arr::get($row, 'value')) ? trim((string) $row['value']) : $label;

        $record = (new ProfileReferenceOption())->setTable(ProfileReferenceOption::tableFor($this->type));
        $record->fill([
            'scope' => $this->scope,
            'label' => $label,
            'value' => $value,
            'sort_order' => filled(Arr::get($row, 'sort_order')) ? (int) $row['sort_order'] : 0,
            'is_active' => ! array_key_exists('is_active', $row) || filter_var(Arr::get($row, 'is_active'), FILTER_VALIDATE_BOOLEAN),
        ]);

        return $record;
    }
}
