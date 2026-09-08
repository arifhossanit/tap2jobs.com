@extends('candidate.profile.index')

@section('section')
    @php
        $candidate = $user->candidate;
        $candidate->unsetRelation('media');
        $candidateResumes = $candidate->getMedia(\App\Models\Candidate::RESUME_PATH);
        $resume = $candidateResumes->first(
            fn ($item) => (bool) $item->getCustomProperty('is_default', false)
        ) ?? $candidateResumes->first(
            fn ($item) => (bool) $item->getCustomProperty(
                \App\Services\ApplicationCvService::APPLICATION_CV_PROPERTY,
                false
            )
        );
    @endphp

    @if($resume)
        <div class="candidate-resume-inline-preview">
            <iframe src="{{ route('candidate.resumes.preview', $resume->id) }}"
                    title="{{ __('messages.candidate_profile.resume') }}"></iframe>
        </div>
    @endif
@endsection
