<?php

namespace App\Http\Requests\Concerns;

use App\Models\City;
use App\Models\JobType;
use App\Models\ProfileReferenceOption;
use App\Models\Skill;
use App\Models\State;
use App\Models\Tag;
use App\Models\Thana;
use Illuminate\Validation\Rule;

trait ValidatesJob
{
    protected function prepareJobForValidation(): void
    {
        $this->restoreLocationHierarchy();

        $employmentStatus = $this->input('employment_status');
        $usesEmploymentStatusForm = $this->routeIs('job.store', 'job.update', 'admin.job.store', 'admin.job.update');
        $workplace = $this->input('workplace');
        $selectedWorkplaces = collect($this->input('workplaces', []));
        if ($workplace) $selectedWorkplaces->push($workplace);
        if ($this->boolean('work_from_office')) $selectedWorkplaces->push('work_from_office');
        if ($this->boolean('work_from_home')) $selectedWorkplaces->push('work_from_home');
        if ($this->boolean('hybrid')) $selectedWorkplaces->push('hybrid');
        $selectedWorkplaces = $selectedWorkplaces->unique()->filter()->values()->toArray();
        $experienceUnit = $this->input('experience_unit');
        $experienceRequirement = trim((string) $this->input('experience_requirement'));
        $jobsSkill = collect($this->input('jobsSkill', []))
            ->map(fn ($skill) => trim((string) $skill))
            ->filter()
            ->unique(fn ($skill) => mb_strtolower($skill))
            ->values()
            ->toArray();
        $jobTag = collect($this->input('jobTag', []))
            ->map(fn ($tag) => trim((string) $tag))
            ->filter()
            ->unique(fn ($tag) => mb_strtolower($tag))
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
            'is_freelance' => $usesEmploymentStatusForm
                ? ($employmentStatus === 'freelance' || $this->isFreelanceJobType())
                : $this->boolean('is_freelance'),
            'work_from_office' => in_array('work_from_office', $selectedWorkplaces),
            'work_from_home' => in_array('work_from_home', $selectedWorkplaces),
            'hybrid' => in_array('hybrid', $selectedWorkplaces),
            'experience_unit' => $experienceUnit,
            'experience_requirement' => $experienceRequirement,
            'freshers_encouraged' => $this->boolean('freshers_encouraged'),
            'experience' => $this->minimumExperienceYears($experienceUnit, $experienceRequirement),
            'jobsSkill' => $jobsSkill,
            'jobTag' => $jobTag,
            'workplaces' => $selectedWorkplaces,
        ]);
    }

    private function restoreLocationHierarchy(): void
    {
        $location = [];
        $stateId = $this->input('state_id');
        $cityId = $this->input('city_id');

        if (! $cityId && $this->filled('thana_id')) {
            $cityId = Thana::whereKey($this->input('thana_id'))->value('city_id');

            if ($cityId) {
                $location['city_id'] = $cityId;
            }
        }

        if (! $stateId && $cityId) {
            $stateId = City::whereKey($cityId)->value('state_id');

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
        $usesEmploymentStatusForm = $this->routeIs('job.store', 'job.update', 'admin.job.store', 'admin.job.update');
        $hideSalary = $this->boolean('hide_salary');

        return [
            'company_id' => ['sometimes', 'required', 'integer', Rule::exists('companies', 'id')],
            'job_title' => ['required', 'string', 'max:180'],
            'description' => ['required', 'string'],
            'key_responsibilities' => ['required', 'string'],
            'compensation_and_other_benefits' => ['nullable', 'string'],
            'job_category_id' => ['required', 'integer', Rule::exists('job_categories', 'id')],
            'city_village_name' => ['nullable', 'string', 'max:255'],
            'currency_id' => ['required', 'integer', Rule::exists('salary_currencies', 'id')],
            'salary_period_id' => ['required', 'integer', Rule::exists('salary_periods', 'id')],
            'job_type_id' => ['required', 'integer', Rule::exists('job_types', 'id')],
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
            'thana_id' => [
                'nullable',
                'integer',
                Rule::exists('thanas', 'id')->where('city_id', $this->input('city_id')),
            ],
            'address' => ['nullable', 'string'],
            'salary_from' => $hideSalary
                ? ['nullable', 'numeric', 'min:0', 'max:9999999999']
                : ['required', 'numeric', 'min:0', 'max:9999999999'],
            'salary_to' => $hideSalary
                ? ['nullable', 'numeric', 'min:0', 'max:9999999999', 'gte:salary_from']
                : ['required', 'numeric', 'min:0', 'max:9999999999', 'gte:salary_from'],
            'job_expiry_date' => ['required', 'date', 'after_or_equal:today'],
            'employment_status' => [
                $usesEmploymentStatusForm ? 'required' : 'nullable',
                Rule::in($this->employmentStatusValues()),
            ],
            'workplace' => ['nullable', Rule::in(['work_from_office', 'work_from_home', 'hybrid'])],
            'workplaces' => ['nullable', 'array'],
            'workplaces.*' => ['string', 'max:150'],
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
            'jobTag.*' => [
                'nullable',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $value = trim((string) $value);

                    if ($value === '') {
                        return;
                    }

                    if (mb_strlen($value) > 160) {
                        $fail(__('validation.max.string', ['attribute' => $attribute, 'max' => 160]));

                        return;
                    }

                    if (is_numeric($value) && ! Tag::whereKey((int) $value)->exists()) {
                        $fail(__('validation.exists', ['attribute' => $attribute]));
                    }
                },
            ],
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

        return array_values(array_unique(array_merge(
            $values,
            array_keys(\App\Models\Job::JOB_NATURES),
            array_keys(\App\Models\Job::EMPLOYMENT_STATUSES)
        )));
    }

    private function isFreelanceJobType(): bool
    {
        $jobTypeId = $this->input('job_type_id');

        if (! $jobTypeId || ! is_numeric($jobTypeId)) {
            return false;
        }

        $jobTypeName = JobType::whereKey((int) $jobTypeId)->value('name');

        return $jobTypeName && mb_strtolower(trim($jobTypeName)) === 'freelance';
    }
}

