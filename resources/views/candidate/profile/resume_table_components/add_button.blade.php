@php
    $candidate = \Illuminate\Support\Facades\Auth::user()->candidate;
    $candidate->unsetRelation('media');
    $candidateResumes = $candidate->getMedia(\App\Models\Candidate::RESUME_PATH)
        ->sortByDesc(fn ($resume) => (bool) $resume->getCustomProperty(
            \App\Services\ApplicationCvService::APPLICATION_CV_PROPERTY,
            false
        ))
        ->values();
    $applicationCv = $candidateResumes->first(
        fn ($resume) => (bool) $resume->getCustomProperty(
            \App\Services\ApplicationCvService::APPLICATION_CV_PROPERTY,
            false
        )
    );
    $selectedResumeId = optional($candidateResumes->first(
        fn ($resume) => (bool) $resume->getCustomProperty('is_default', false)
    ))->id ?? optional($applicationCv)->id;
    $hasUploadedResume = $candidateResumes->contains(
        fn ($resume) => ! $resume->getCustomProperty(\App\Services\ApplicationCvService::APPLICATION_CV_PROPERTY, false)
    );
@endphp

<div class="candidate-resume-toolbar">
    <select class="form-select candidate-default-resume-select"
        aria-label="{{ __('messages.candidate_profile.select_default_cv') }}"
        data-url="{{ route('candidate.resumes.default') }}"
        data-current-value="{{ $selectedResumeId }}">
        @foreach($candidateResumes as $candidateResume)
            @php
                $isApplicationCv = (bool) $candidateResume->getCustomProperty(
                    \App\Services\ApplicationCvService::APPLICATION_CV_PROPERTY,
                    false
                );
            @endphp
            <option value="{{ $candidateResume->id }}" {{ $candidateResume->id === $selectedResumeId ? 'selected' : '' }}>
                {{ $isApplicationCv
                    ? \App\Services\ApplicationCvService::TITLE
                    : $candidateResume->getCustomProperty('title', $candidateResume->name) }}
            </option>
        @endforeach
    </select>

    <button type="button"
        class="btn btn-primary candidate-resume-upload-button {{ $hasUploadedResume ? '' : 'uploadResumeModal' }}"
        aria-label="{{ __('messages.candidate_profile.upload_resume') }}"
        title="{{ $hasUploadedResume
            ? __('messages.candidate_profile.resume_upload_limit')
            : __('messages.candidate_profile.upload_resume') }}"
        @disabled($hasUploadedResume)>
        <i class="fa-solid fa-cloud-arrow-up" aria-hidden="true"></i>
    </button>
</div>
