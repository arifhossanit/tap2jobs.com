@extends('candidate.profile.index')

@section('section')
    <div class="mb-xl-8 candidate-resume-page">
        <section class="candidate-profile-section">
            <div class="candidate-profile-section__header">
                <span>{{ __('messages.candidate_profile.resume') }}</span>
            </div>
            <div class="candidate-profile-section__body">
                <p class="text-muted mb-4">{{ __('messages.candidate_profile.application_cv_help') }}</p>
                <form id="candidateCvPrivacyForm" method="POST" action="{{ route('candidate.resumes.privacy') }}" class="cv-privacy-card border rounded-3 p-4 p-md-4 mb-5 bg-white">
                    @csrf
                    @method('PUT')
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 gap-md-4">
                        <div class="d-flex align-items-start gap-3 me-md-3">
                            <div class="flex-grow-1">
                                <div class="form-check form-switch custom-toggle-switch p-0 m-0 d-flex align-items-center gap-3">
                                    <input type="hidden" name="include_sensitive_personal_data_in_cv" value="0">
                                    <input class="form-check-input custom-switch-input m-0 flex-shrink-0" type="checkbox" role="switch"
                                        id="includeSensitivePersonalDataInCv"
                                        name="include_sensitive_personal_data_in_cv" value="1"
                                        @checked((bool) ($user->candidate?->include_sensitive_personal_data_in_cv))>
                                    <label class="form-check-label fw-bold text-dark fs-6 cursor-pointer mb-0" for="includeSensitivePersonalDataInCv">
                                        {{ __('messages.candidate_profile.include_sensitive_personal_data_in_cv') }}
                                    </label>
                                </div>
                                <p class="text-muted fs-7 mb-0 mt-2">
                                    {{ __('messages.candidate_profile.cv_sensitive_data_help') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex-shrink-0 align-self-stretch align-self-md-center pt-2 pt-md-0 d-flex align-items-center justify-content-end">
                            <button type="submit" id="saveCvPrivacyBtn" class="btn btn-primary px-4 py-2 rounded-pill d-inline-flex align-items-center justify-content-center gap-2 fw-medium shadow-sm">
                                <span class="btn-spinner spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                <i class="fa-solid fa-check fs-6 btn-icon"></i>
                                <span>{{ __('messages.common.save') }}</span>
                            </button>
                        </div>
                    </div>
                </form>

                <livewire:resume-table lazy />
            </div>
        </section>
    </div>

    {{-- @include('candidate.profile.modals.upload_resume_modal') --}}
    @include('candidate.profile.modals.resume_preview_modal')
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = $('#candidateCvPrivacyForm');
            if (!form.length) return;

            form.off('submit').on('submit', function (e) {
                e.preventDefault();
                const btn = form.find('#saveCvPrivacyBtn');
                btn.prop('disabled', true);
                btn.find('.btn-spinner').removeClass('d-none');
                btn.find('.btn-icon').addClass('d-none');

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    success: function (result) {
                        if (result.success) {
                            if (typeof displaySuccessMessage === 'function') {
                                displaySuccessMessage(result.message);
                            } else if (typeof toastr !== 'undefined') {
                                toastr.success(result.message);
                            }
                        } else {
                            let msg = result.message || 'Error occurred';
                            if (typeof displayErrorMessage === 'function') {
                                displayErrorMessage(msg);
                            } else if (typeof toastr !== 'undefined') {
                                toastr.error(msg);
                            }
                        }
                    },
                    error: function (xhr) {
                        let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error updating privacy settings';
                        if (typeof displayErrorMessage === 'function') {
                            displayErrorMessage(msg);
                        } else if (typeof toastr !== 'undefined') {
                            toastr.error(msg);
                        }
                    },
                    complete: function () {
                        btn.prop('disabled', false);
                        btn.find('.btn-spinner').addClass('d-none');
                        btn.find('.btn-icon').removeClass('d-none');
                    }
                });
            });
        });
    </script>
@endpush
