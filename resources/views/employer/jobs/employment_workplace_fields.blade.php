<div class="col-md-7 col-sm-12 mb-5 job-employment-choice">
    @php
        $employmentStatus = old(
            'employment_status',
            isset($job) && $job->employment_status
                ? $job->employment_status
                : \App\Models\Job::EMPLOYMENT_STATUS_INTERNSHIP
        );
    @endphp
    <span class="form-label d-block">
        {{ __('messages.job.employment_status') }}<span class="required"></span>
    </span>
    <div class="job-choice-list" role="radiogroup" aria-label="{{ __('messages.job.employment_status') }}">
        @foreach (\App\Models\Job::EMPLOYMENT_STATUSES as $value => $label)
            @php
                $employmentStatusId = 'employmentStatus'.str_replace(' ', '', ucwords(str_replace('_', ' ', $value)));
            @endphp
            <input class="job-choice-input" type="radio" name="employment_status"
                   id="{{ $employmentStatusId }}" value="{{ $value }}"
                   {{ $employmentStatus === $value ? 'checked' : '' }} required>
            <label class="job-choice-label" for="{{ $employmentStatusId }}">{{ __($label) }}</label>
        @endforeach
    </div>
</div>

<div class="col-md-5 col-sm-12 mb-5 job-workplace-choice">
    <span class="form-label d-block">{{ __('messages.job.workplace') }}</span>
    <div class="job-choice-list">
        <input class="job-choice-input" type="checkbox" name="work_from_office"
               id="workFromOffice" value="1"
               {{ old('work_from_office', isset($job) ? $job->work_from_office : false) ? 'checked' : '' }}>
        <label class="job-choice-label" for="workFromOffice">{{ __('messages.job.work_from_office') }}</label>

        <input class="job-choice-input" type="checkbox" name="work_from_home"
               id="workFromHome" value="1"
               {{ old('work_from_home', isset($job) ? $job->work_from_home : false) ? 'checked' : '' }}>
        <label class="job-choice-label" for="workFromHome">{{ __('messages.job.work_from_home') }}</label>

        <input class="job-choice-input" type="checkbox" name="hybrid"
               id="hybrid" value="1"
               {{ old('hybrid', isset($job) ? $job->hybrid : false) ? 'checked' : '' }}>
        <label class="job-choice-label" for="hybrid">{{ __('messages.job.hybrid') }}</label>
    </div>
</div>
