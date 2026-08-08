@php
    $experienceUnit = old('experience_unit', isset($job) ? $job->experience_unit : \App\Models\Job::EXPERIENCE_UNIT_YEAR);
    $experienceRequirement = old('experience_requirement', isset($job) ? $job->experience_requirement : '');
    $freshersEncouraged = (bool) old('freshers_encouraged', isset($job) ? $job->freshers_encouraged : false);
@endphp

<div class="col-xl-6 col-md-6 col-sm-12 mb-5">
    <label class="form-label" for="experienceRequirement">
        {{ __('messages.job_experience.job_experience') }}<span class="required"></span>
    </label>

    <div class="input-group">
        <select name="experience_unit" id="experienceUnit" class="form-select flex-grow-0" style="width: 155px" required>
            @foreach (\App\Models\Job::EXPERIENCE_UNITS as $value => $label)
                <option value="{{ $value }}" {{ $experienceUnit === $value ? 'selected' : '' }}>{{ __($label) }}</option>
            @endforeach
        </select>
        <input type="text" name="experience_requirement" id="experienceRequirement"
               class="form-control" value="{{ $experienceRequirement }}" maxlength="100"
               placeholder="{{ __('messages.job.experience_range_placeholder') }}" required>
    </div>

    <label class="form-check mt-3 mb-0">
        <input type="checkbox" name="freshers_encouraged" class="form-check-input" value="1"
               {{ $freshersEncouraged ? 'checked' : '' }}>
        <span class="form-check-label">{{ __('messages.job.freshers_encouraged') }}</span>
    </label>
</div>
