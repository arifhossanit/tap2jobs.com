@extends('front_web.layouts.app')

@section('title')
    {{ __('web.job_details.apply_for_job') }}
@endsection

@section('content')
    @php
        $resumeDetails = collect($resumeDetails ?? []);
        $selectedResumeId = null;

        if (!$isApplied) {
            $selectedResumeId = $default_resume ?? null;
        }

        $selectedResume = $resumeDetails->get($selectedResumeId);
        $companyName = optional($job->company)->company_name
            ?: optional(optional($job->company)->user)->full_name;
    @endphp

    <main class="apply-job-page">
        <section class="apply-job-hero">
            <div class="container">
                <div class="apply-job-hero__content">
                    <h1>{{ __('web.job_details.apply_for_job') }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('front.home') }}">{{ __('web.home') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('web.job_details.apply_for_job') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </section>

        <section class="apply-job-section">
            <div class="container">
                <div class="apply-job-shell">
                    <article class="apply-job-summary-card">
                        <div class="apply-job-company-logo">
                            <img src="{{ $job->company->company_url }}" alt="{{ $companyName }}">
                        </div>
                        <div class="apply-job-summary-card__content">                            
                            <h2>{{ $job->job_title }}</h2>
                            @if($companyName)<p><i class="fa-regular fa-building"></i>{{ $companyName }}</p>@endif
                        </div>                        
                    </article>

                    <form id="applyJobForm" class="apply-job-form-card">
                        @csrf
                        @include('front_web.layouts.errors')
                        @include('flash::message')
                        <input type="hidden" value="{{ $job->id }}" name="job_id">

                        <header class="apply-job-form-card__header">
                            <span class="apply-job-form-card__icon"><i class="fa-regular fa-file-lines"></i></span>
                            <div>
                                <h3>{{ __('messages.apply_job.application_details') }}</h3>                                
                            </div>
                        </header>

                        <div class="response"></div>

                        @if(!$isApplied)
                            <div class="row g-4">
                                <div class="col-lg-7">
                                    <label for="resumeId" class="apply-job-label">
                                        {{ __('messages.apply_job.resume') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="hidden" name="resume_id" id="resumeId" value="{{ $selectedResumeId }}">

                                    <div class="apply-job-selected-cv {{ $selectedResume ? '' : 'd-none' }}" id="selectedCvCard">
                                        <span class="apply-job-selected-cv__file"><i class="fa-regular fa-file-pdf"></i></span>
                                        <div class="apply-job-selected-cv__content">
                                            {{-- <span>{{ __('messages.apply_job.selected_cv') }}</span> --}}
                                            <strong id="selectedCvName">{{ data_get($selectedResume, 'title') }}</strong>
                                            {{-- <small>{{ __('messages.apply_job.selected_for_application') }}</small> --}}
                                        </div>                                        
                                        <a class="apply-job-selected-cv__preview {{ $selectedResume ? '' : 'd-none' }}"
                                           id="selectedCvPreview"
                                           href="{{ $selectedResume ? route('candidate.resumes.preview', $selectedResumeId) : '#' }}"
                                           target="_blank" rel="noopener">
                                            <i class="fa-regular fa-eye"></i><span>{{ __('messages.apply_job.preview_cv') }}</span>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-lg-5">
                                    <label for="expected_salary" class="apply-job-label">
                                        {{ __('messages.candidate.expected_salary') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control apply-job-control" id="expected_salary"
                                           name="expected_salary" inputmode="decimal" min="0" max="9999999999"
                                           value="{{ $isJobDrafted ? $draftJobDetails->expected_salary : '' }}" required>
                                    <small class="apply-job-field-help">{{ __('messages.apply_job.expected_salary_hint') }}</small>
                                </div>

                                <div class="col-12">
                                    <label for="notes" class="apply-job-label">{{ __('messages.apply_job.notes') }}</label>
                                    <textarea class="form-control apply-job-control apply-job-notes" rows="5" id="notes"
                                              name="notes" placeholder="{{ __('messages.apply_job.notes_placeholder') }}">{{ $isJobDrafted ? $draftJobDetails->notes : '' }}</textarea>
                                </div>

                                @if(getSettingValue('enable_google_recaptcha'))
                                    <div class="col-12 text-center">
                                        <div class="g-recaptcha d-flex justify-content-center"
                                             data-sitekey="{{ config('app.google_recaptcha_site_key') }}"
                                             name="g-recaptcha" id="g-recaptcha" required></div>
                                        <div id="g-recaptcha-error"></div>
                                    </div>
                                @endif
                            </div>

                            <footer class="apply-job-form-card__actions">
                                @if(!$isJobDrafted)
                                    <button type="button" class="btn apply-job-draft-button save-draft"
                                            data-loading-text="<span class='spinner-border spinner-border-sm'></span> {{ __('messages.common.process') }}"
                                            id="draftJobSave">{{ __('web.common.save_as_draft') }}</button>
                                @endif
                                @if($isActive && !$job->is_suspended)
                                    <button type="button" class="btn btn-primary apply-job-submit-button apply-job"
                                            data-loading-text="<span class='spinner-border spinner-border-sm'></span> {{ __('messages.common.process') }}"
                                            id="applyJobSave">
                                        {{ __('web.common.apply') }} <i class="fa-solid fa-arrow-right"></i>
                                    </button>
                                @endif
                            </footer>
                        @else
                            <div class="apply-job-already-applied">
                                <i class="fa-solid fa-circle-check"></i>
                                <h4>{{ __('web.apply_for_job.already_applied') }}</h4>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </section>
    </main>
@endsection
