@php
    $candidate = \Illuminate\Support\Facades\Auth::user()->candidate;
    $candidate->unsetRelation('media');
    $candidateResumes = $candidate->getMedia(\App\Models\Candidate::RESUME_PATH);
    $selectedResumeId = optional($candidateResumes->first(
        fn ($resume) => (bool) $resume->getCustomProperty('is_default', false)
    ))->id;
    $hasUploadedResume = $candidateResumes->contains(
        fn ($resume) => ! $resume->getCustomProperty(\App\Services\ApplicationCvService::APPLICATION_CV_PROPERTY, false)
    );
@endphp

<div class="d-flex align-items-center gap-3 flex-wrap candidate-resume-toolbar">
    <select class="form-select candidate-default-resume-select"
        aria-label="{{ __('messages.candidate_profile.select_default_cv') }}"
        data-url="{{ route('candidate.resumes.default') }}"
        data-current-value="{{ $selectedResumeId }}">
        @foreach($candidateResumes as $candidateResume)
            <option value="{{ $candidateResume->id }}" {{ $candidateResume->id === $selectedResumeId ? 'selected' : '' }}>
                {{ $candidateResume->getCustomProperty('title', $candidateResume->name) }}
            </option>
        @endforeach
    </select>

    <a type="button"
        class="btn btn-primary {{ $hasUploadedResume ? 'disabled' : 'uploadResumeModal' }}"
        @if($hasUploadedResume) aria-disabled="true" title="{{ __('messages.candidate_profile.resume_upload_limit') }}" @endif>
        {{ __('messages.candidate_profile.upload_resume') }}
    </a>
</div>
