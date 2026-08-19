@extends('candidate.profile.index')

@section('section')
    <div class="mb-xl-8 candidate-resume-page">
        <section class="candidate-profile-section">
            <div class="candidate-profile-section__header">
                <span>{{ __('messages.candidate_profile.resume') }}</span>
            </div>
            <div class="candidate-profile-section__body">
                <p class="text-muted mb-4">{{ __('messages.candidate_profile.application_cv_help') }}</p>
                <form method="POST" action="{{ route('candidate.resumes.privacy') }}" class="card border p-4 mb-5">
                    @csrf
                    @method('PUT')
                    <div class="form-check form-switch mb-2">
                        <input type="hidden" name="include_sensitive_personal_data_in_cv" value="0">
                        <input class="form-check-input" type="checkbox" role="switch"
                            id="includeSensitivePersonalDataInCv"
                            name="include_sensitive_personal_data_in_cv" value="1"
                            @checked((bool) $user->candidate->include_sensitive_personal_data_in_cv)>
                        <label class="form-check-label" for="includeSensitivePersonalDataInCv">
                            {{ __('messages.candidate_profile.include_sensitive_personal_data_in_cv') }}
                        </label>
                    </div>
                    <p class="text-muted small mb-3">{{ __('messages.candidate_profile.cv_sensitive_data_help') }}</p>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            {{ __('messages.common.save') }}
                        </button>
                    </div>
                </form>
                <livewire:resume-table lazy />
            </div>
        </section>
    </div>

    @include('candidate.profile.modals.upload_resume_modal')
    @include('candidate.profile.modals.resume_preview_modal')
@endsection
