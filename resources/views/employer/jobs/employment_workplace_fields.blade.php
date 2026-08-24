<div class="col-md-12 col-sm-12 mb-5 job-employment-choice">
    @php
        $employmentStatuses = $data['employmentStatus'] ?? collect(\App\Models\Job::JOB_NATURES)
            ->mapWithKeys(fn ($label, $value) => [$value => __($label)])
            ->toArray();
        $defaultEmploymentStatus = array_key_exists(\App\Models\Job::JOB_NATURE_PERMANENT, $employmentStatuses)
            ? \App\Models\Job::JOB_NATURE_PERMANENT
            : array_key_first($employmentStatuses);
        $employmentStatus = old(
            'employment_status',
            isset($job) && $job->employment_status
                ? $job->employment_status
                : $defaultEmploymentStatus
        );
        if ($employmentStatus && ! array_key_exists($employmentStatus, $employmentStatuses)) {
            $legacyEmploymentStatuses = collect(\App\Models\Job::EMPLOYMENT_STATUSES)
                ->mapWithKeys(fn ($label, $value) => [$value => __($label)])
                ->toArray();
            if (array_key_exists($employmentStatus, $legacyEmploymentStatuses)) {
                $employmentStatuses[$employmentStatus] = $legacyEmploymentStatuses[$employmentStatus];
            }
        }
        $workplaceOptions = $data['workplaceOptions'] ?? \App\Models\ProfileReferenceOption::options(
            \App\Models\ProfileReferenceOption::TYPE_JOB_WORKPLACE,
            [\App\Models\ProfileReferenceOption::SCOPE_EMPLOYER]
        );
        $workplaceOptions = array_intersect_key($workplaceOptions, array_flip([
            'work_from_office',
            'work_from_home',
            'hybrid',
        ]));
    @endphp
    <span class="form-label d-block">
        {{ __('messages.job.employment_status') }}<span class="required"></span>
    </span>
    <div class="job-choice-list" role="radiogroup" aria-label="{{ __('messages.job.employment_status') }}">
        @foreach ($employmentStatuses as $value => $label)
            @php
                $employmentStatusId = 'employmentStatus'.str_replace(' ', '', ucwords(str_replace('_', ' ', $value)));
            @endphp
            <input class="job-choice-input" type="radio" name="employment_status"
                   id="{{ $employmentStatusId }}" value="{{ $value }}"
                   {{ $employmentStatus === $value ? 'checked' : '' }} required>
            <label class="job-choice-label" for="{{ $employmentStatusId }}">{{ $label }}</label>
        @endforeach
    </div>
</div>

<div class="col-md-12 col-sm-12 mb-5 job-workplace-choice">
    <span class="form-label d-block">{{ __('messages.job.workplace') }}</span>
    <div class="job-choice-list">
        @foreach ($workplaceOptions as $value => $label)
            @php
                $workplaceId = 'workplace'.str_replace(' ', '', ucwords(str_replace('_', ' ', $value)));
            @endphp
            <input class="job-choice-input" type="checkbox" name="{{ $value }}"
                   id="{{ $workplaceId }}" value="1"
                   {{ old($value, isset($job) ? $job->{$value} : false) ? 'checked' : '' }}>
            <label class="job-choice-label" for="{{ $workplaceId }}">{{ $label }}</label>
        @endforeach
    </div>
</div>
