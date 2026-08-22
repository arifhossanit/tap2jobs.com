<?php

namespace App\Http\Requests\Concerns;

use App\Models\City;
use App\Models\ProfileReferenceOption;
use App\Models\Skill;
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
        $jobsSkill = collect($this->input('jobsSkill', []))
            ->map(fn ($skill) => trim((string) $skill))
            ->filter()
            ->unique(fn ($skill) => mb_strtolower($skill))
            ->values()
            ->toArray();

        $hideSalary = $this->boolean('hide_salary');
        $rawSalaryFrom = $this->input('salary_from');
        $rawSalaryTo = $this->input('salary_to');

        $salaryFrom = ($rawSalaryFrom !== null && $rawSalaryFrom !== '')
            ? removeCommaFromNumbers($rawSalaryFrom)
            : ($hideSalary ? 0 : null);

        $salaryTo = ($rawSalaryTo !== null && $rawSalaryTo !== '')
            ? removeCommaFromNumbers($rawSalaryTo)
            : ($hideSalary ? 0 : null);

        $this->merge([
            'salary_from' => $salaryFrom,
            'salary_to' => $salaryTo,
            'hide_salary' => $hideSalary,
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
            'jobsSkill' => $jobsSkill,
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
        $hideSalary = $this->boolean('hide_salary');

        return [
            'company_id' => ['sometimes', 'required', 'integer', Rule::exists('companies', 'id')],
            'job_title' => ['required', 'string', 'max:180'],
            'description' => ['required', 'string'],
            'key_responsibilities' => ['required', 'string'],
            'currency_id' => ['required', 'integer', Rule::exists('salary_currencies', 'id')],
            'salary_period_id' => ['required', 'integer', Rule::exists('salary_periods', 'id')],
            'job_type_id' => ['required', 'integer', Rule::exists('job_types', 'id')],
            'job_category_id' => ['required', 'integer', Rule::exists('job_categories', 'id')],
            'functional_area_id' => [
                'required',
                'string',
                'max:150',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $value = trim((string) $value);

                    if (is_numeric($value) && ! \App\Models\FunctionalArea::whereKey((int) $value)->exists()) {
                        $fail(__('validation.exists', ['attribute' => $attribute]));
                    }
                },
            ],
            'career_level_id' => ['nullable', 'integer', Rule::exists('career_levels', 'id')],
            'job_shift_id' => ['nullable', 'integer', Rule::exists('job_shifts', 'id')],
            'degree_level_id' => ['nullable', 'integer', Rule::exists('education_degree_levels', 'id')],
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
            'salary_from' => $hideSalary
                ? ['nullable', 'numeric', 'min:0', 'max:999999999']
                : ['required', 'numeric', 'min:0', 'max:999999999'],
            'salary_to' => $hideSalary
                ? ['nullable', 'numeric', 'min:0', 'max:999999999', 'gte:salary_from']
                : ['required', 'numeric', 'min:0', 'max:999999999', 'gte:salary_from'],
            'job_expiry_date' => ['required', 'date', 'after_or_equal:today'],
            'employment_status' => [
                $isEmployerJobForm ? 'required' : 'nullable',
                Rule::in($this->employmentStatusValues()),
            ],
            'work_from_office' => ['required', 'boolean'],
            'work_from_home' => ['required', 'boolean'],
            'hybrid' => ['required', 'boolean'],
            'hide_salary' => ['required', 'boolean'],
            'is_freelance' => ['required', 'boolean'],
            'jobsSkill' => ['required', 'array', 'min:1'],
            'jobsSkill.*' => [
                'required',
                'string',
                'max:150',
                'distinct:ignore_case',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $value = trim((string) $value);

                    if (is_numeric($value) && ! Skill::whereKey((int) $value)->exists()) {
                        $fail(__('validation.exists', ['attribute' => $attribute]));
                    }
                },
            ],
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

    private function employmentStatusValues(): array
    {
        $values = ProfileReferenceOption::values(
            ProfileReferenceOption::TYPE_JOB_EMPLOYMENT_STATUS,
            [ProfileReferenceOption::SCOPE_EMPLOYER]
        );

        return $values ?: array_keys(\App\Models\Job::EMPLOYMENT_STATUSES);
    }
}
