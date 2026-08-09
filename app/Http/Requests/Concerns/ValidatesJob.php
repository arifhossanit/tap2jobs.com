<?php

namespace App\Http\Requests\Concerns;

use App\Models\City;
use App\Models\State;
use Illuminate\Validation\Rule;

trait ValidatesJob
{
    protected function prepareJobForValidation(): void
    {
        $this->restoreLocationHierarchy();

        $employmentStatus = $this->input('employment_status');
        $isEmployerJobForm = $this->routeIs('job.store', 'job.update');
        $experienceUnit = $this->input('experience_unit');
        $experienceRequirement = trim((string) $this->input('experience_requirement'));

        $this->merge([
            'salary_from' => removeCommaFromNumbers($this->input('salary_from')),
            'salary_to' => removeCommaFromNumbers($this->input('salary_to')),
            'hide_salary' => $this->boolean('hide_salary'),
            'is_freelance' => $isEmployerJobForm
                ? $employmentStatus === 'freelance'
                : $this->boolean('is_freelance'),
            'work_from_office' => $this->boolean('work_from_office'),
            'work_from_home' => $this->boolean('work_from_home'),
            'hybrid' => $this->boolean('hybrid'),
            'experience_unit' => $experienceUnit,
            'experience_requirement' => $experienceRequirement,
            'freshers_encouraged' => $this->boolean('freshers_encouraged'),
            'experience' => $this->minimumExperienceYears($experienceUnit, $experienceRequirement),
        ]);
    }

    private function restoreLocationHierarchy(): void
    {
        $location = [];
        $stateId = $this->input('state_id');

        if (! $stateId && $this->filled('city_id')) {
            $stateId = City::whereKey($this->input('city_id'))->value('state_id');

            if ($stateId) {
                $location['state_id'] = $stateId;
            }
        }

        if (! $this->filled('country_id') && $stateId) {
            $countryId = State::whereKey($stateId)->value('country_id');

            if ($countryId) {
                $location['country_id'] = $countryId;
            }
        }

        if ($location !== []) {
            $this->merge($location);
        }
    }

    protected function jobRules(): array
    {
        $isEmployerJobForm = $this->routeIs('job.store', 'job.update');

        return [
            'company_id' => ['sometimes', 'required', 'integer', Rule::exists('companies', 'id')],
            'job_title' => ['required', 'string', 'max:180'],
            'description' => ['required', 'string'],
            'key_responsibilities' => ['required', 'string'],
            'currency_id' => ['required', 'integer', Rule::exists('salary_currencies', 'id')],
            'salary_period_id' => ['required', 'integer', Rule::exists('salary_periods', 'id')],
            'job_type_id' => ['required', 'integer', Rule::exists('job_types', 'id')],
            'job_category_id' => ['required', 'integer', Rule::exists('job_categories', 'id')],
            'functional_area_id' => ['required', 'integer', Rule::exists('functional_areas', 'id')],
            'career_level_id' => ['nullable', 'integer', Rule::exists('career_levels', 'id')],
            'job_shift_id' => ['nullable', 'integer', Rule::exists('job_shifts', 'id')],
            'degree_level_id' => ['nullable', 'integer', Rule::exists('required_degree_levels', 'id')],
            'no_preference' => ['nullable', 'integer', Rule::in([0, 1, 2])],
            'experience' => ['required', 'integer', 'min:0', 'max:60'],
            'experience_unit' => ['required', Rule::in(['month', 'year', 'month_year'])],
            'experience_requirement' => ['required', 'string', 'max:100', 'regex:/\d/'],
            'freshers_encouraged' => ['required', 'boolean'],
            'vacancy' => ['required', 'integer', 'min:1', 'max:4294967295'],
            'country_id' => ['required', 'integer', Rule::exists('countries', 'id')],
            'state_id' => [
                'required',
                'integer',
                Rule::exists('states', 'id')->where('country_id', $this->input('country_id')),
            ],
            'city_id' => [
                'required',
                'integer',
                Rule::exists('cities', 'id')->where('state_id', $this->input('state_id')),
            ],
            'salary_from' => ['required', 'numeric', 'min:0', 'max:999999999'],
            'salary_to' => ['required', 'numeric', 'min:0', 'max:999999999', 'gte:salary_from'],
            'job_expiry_date' => ['required', 'date', 'after_or_equal:today'],
            'employment_status' => [
                $isEmployerJobForm ? 'required' : 'nullable',
                Rule::in(['full_time', 'part_time', 'contractual', 'internship', 'freelance']),
            ],
            'work_from_office' => ['required', 'boolean'],
            'work_from_home' => ['required', 'boolean'],
            'hybrid' => ['required', 'boolean'],
            'hide_salary' => ['required', 'boolean'],
            'is_freelance' => ['required', 'boolean'],
            'jobsSkill' => ['required', 'array', 'min:1'],
            'jobsSkill.*' => ['required', 'integer', 'distinct', Rule::exists('skills', 'id')],
            'jobTag' => ['nullable', 'array'],
            'jobTag.*' => ['integer', 'distinct', Rule::exists('tags', 'id')],
        ];
    }

    private function minimumExperienceYears(?string $unit, string $requirement): int
    {
        preg_match('/\d+/', $requirement, $matches);
        $minimum = isset($matches[0]) ? (int) $matches[0] : 0;

        if ($unit === 'month') {
            return min(60, (int) floor($minimum / 12));
        }

        if ($unit === 'month_year' && preg_match('/^\s*\d+\s*months?/i', $requirement)) {
            return min(60, (int) floor($minimum / 12));
        }

        return min(60, $minimum);
    }
}
