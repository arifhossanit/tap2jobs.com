@extends('candidate.profile.index')

@section('section')
    <div class="mb-xl-8 candidate-resume-page">
        <section class="candidate-profile-section">
            <div class="candidate-profile-section__header">
                <span>{{ __('messages.candidate_profile.resume') }}</span>
            </div>
            <div class="candidate-profile-section__body">
                {{-- <p class="text-muted mb-5">{{ __('messages.candidate_profile.application_cv_help') }}</p> --}}
                <livewire:resume-table lazy />
            </div>
        </section>
    </div>

    @include('candidate.profile.modals.upload_resume_modal')
    @include('candidate.profile.modals.resume_preview_modal')
@endsection
