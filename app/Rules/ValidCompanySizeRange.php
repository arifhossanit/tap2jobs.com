<?php

namespace App\Rules;

use App\Models\CompanySize;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCompanySizeRange implements ValidationRule
{
    protected ?int $ignoreId;

    public function __construct(?int $ignoreId = null)
    {
        $this->ignoreId = $ignoreId;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $parsed = CompanySize::parseRange((string) $value);
        if (! $parsed) {
            $fail(__('The size range format is invalid. Allowed formats: 1-50, 51-100, 500+'));
            return;
        }

        [$newMin, $newMax] = $parsed;

        if ($newMin > $newMax) {
            $fail(__('The start number cannot be greater than the end number.'));
            return;
        }

        $existing = CompanySize::query()
            ->when($this->ignoreId, function ($q) {
                $q->where('id', '!=', $this->ignoreId);
            })
            ->get();

        foreach ($existing as $item) {
            $itemParsed = CompanySize::parseRange($item->size);
            if (! $itemParsed) {
                continue;
            }

            [$existMin, $existMax] = $itemParsed;

            // Overlap check formula: max(min1, min2) <= min(max1, max2)
            if (max($newMin, $existMin) <= min($newMax, $existMax)) {
                $fail(__("The size range ':value' overlaps with an existing range ':existing'. Numbers inside existing ranges are not allowed.", [
                    'value' => $value,
                    'existing' => $item->size,
                ]));
                return;
            }
        }
    }
}
