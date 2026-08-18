@extends('candidate.profile.index')
@push('css')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}">
    <style>
        .candidate-education-form-field[data-education-major-field] {
            position: relative;
        }

        .candidate-education-major-menu {
            background: #fff;
            border: 1px solid #d0d5dd;
            border-radius: 4px;
            box-shadow: 0 2px 6px rgba(15, 27, 61, .16);
            left: 0;
            max-height: 180px;
            overflow-y: auto;
            position: absolute;
            right: 0;
            top: calc(100% + 8px);
            z-index: 30;
        }

        .candidate-education-major-option {
            background: transparent;
            border: 0;
            color: #0f1b3d;
            display: block;
            font-size: 14px;
            padding: 12px 16px;
            text-align: left;
            width: 100%;
        }

        .candidate-education-major-option:hover,
        .candidate-education-major-option:focus {
            background: #f5f7fb;
            outline: 0;
        }
    </style>
@endpush
@section('section')
    @php
        $defaultEducationCountryId = collect($data['countries'] ?? [])->search('Bangladesh');
        if ($defaultEducationCountryId === false) {
            $defaultEducationCountryId = collect($data['countries'] ?? [])->keys()->first();
        }

        $candidateProfileNumber = function ($number) {
            $number = (string) $number;

            if (app()->getLocale() !== 'bn') {
                return $number;
            }

            return strtr($number, [
                '0' => '০',
                '1' => '১',
                '2' => '২',
                '3' => '৩',
                '4' => '৪',
                '5' => '৫',
                '6' => '৬',
                '7' => '৭',
                '8' => '৮',
                '9' => '৯',
            ]);
        };

        $educationBoardOptions = ['' => 'Select your Board'];
        foreach (($data['educationBoardOptions'] ?? collect())->toArray() as $boardName) {
            $educationBoardOptions[$boardName] = $boardName;
        }
        $educationExamTitleOptions = ($data['educationDegreeTitleOptions'] ?? collect())->toArray();
        $educationMajorGroupOptions = ($data['educationMajorGroupOptions'] ?? collect())->toArray();
        $educationLevelMeta = ($data['educationLevelMeta'] ?? collect())->toArray();

        $candidateTrainings = $data['candidateTrainings'] ?? collect();

        $candidateCertificationItems = [
            [
                'certification' => 'Web Application Development with Laravel, React, Vue.js & WordPress',
                'institute' => 'IsDB-BISEW Scholarship',
                'location' => 'Agargaon, Dhaka',
                'duration' => '31 Dec 2024 to 30 Oct 2025',
            ],
            [
                'certification' => 'Shorthand and Computer Basic',
                'institute' => 'Joyti Commercial',
                'location' => 'Malibag, Dhaka',
                'duration' => '1 Jan 2024 to 28 Aug 2024',
            ],
        ];
    @endphp
    <script>
        window.candidateEducationExamTitleOptions = @json($educationExamTitleOptions);
        window.candidateEducationMajorGroupOptions = @json($educationMajorGroupOptions);
        window.candidateEducationLevelMeta = @json($educationLevelMeta);
        window.candidateProfileUseBanglaNumber = @json(app()->getLocale() === 'bn');
        window.candidateProfileEducationLabel = @json(__('messages.candidate_profile.education'));
        window.candidateProfileTrainingLabel = @json(__('messages.candidate_profile.training'));
        window.candidateProfileCertificationLabel = @json(__('messages.candidate_profile.professional_certification'));
    </script>
    <div class="mb-xl-8 candidate-career-info-page candidate-profile-accordion" id="candidateEducationAccordion">
        <div class="border-0 d-none">
            <div class="d-md-flex align-items-center justify-content-between mb-5 mx-3">
                <h1 class="mb-0">{{ __('messages.candidate_profile.experience') }}</h1>
                <div class="text-end mt-4 mt-md-0">
                    <a class="btn btn-primary form-btn addExperienceModal" data-bs-toggle="modal"
                        data-bs-target="#addExperienceModal">{{ __('messages.candidate_profile.add_experience') }} </a>
                </div>
            </div>

            <div class="pt-0 fs-6 py-8 px-3 text-gray-700">
                {{ Form::hidden(null, __('messages.candidate_profile.present'), ['id' => 'candidatePresentMsg']) }}
                <div class="row">
                    <div class="candidate-experience-container">
                        <div class="col-12 {{ $data['candidateExperiences']->count() ? 'd-none' : '' }}"
                            id="notfoundExperience">
                            <h5 class="product-item pb-5 d-flex justify-content-center text-gray-600">
                                {{ __('messages.candidate.experience_not_found') }}
                            </h5>
                        </div>
                        @php
                            /** @var \App\Models\CandidateExperience $candidateExperience */
                        @endphp
                        @foreach ($data['candidateExperiences'] as $candidateExperience)
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 candidate-experience rounded shadow p-5 mb-5 card"
                                data-experience-id="{{ $loop->index }}" data-id="{{ $candidateExperience->id }}">
                                <article class="article article-style-b">
                                    <div class="article-details">
                                        <div class="d-flex justify-content-between">
                                            <div class="article-title">
                                                <h4 class="text-primary">{{ $candidateExperience->experience_title }}</h4>
                                                <h6 class="text-muted">{{ $candidateExperience->company }}</h6>
                                            </div>
                                            <div class="article-cta candidate-experience-edit-delete">
                                                <a href="javascript:void(0)"
                                                    class="edit-candidate-experience btn px-2 text-primary fs-3 {{ checkLanguageSession() == 'ar' ? 'pe-0' : 'ps-0' }}"
                                                    title="{{ __('messages.common.edit') }}" data-bs-toggle="tooltip"
                                                    data-id="{{ $candidateExperience->id }}"><i
                                                        class="fa-solid fa-pen-to-square"></i></a>
                                                <a href="javascript:void(0)"
                                                    class="delete-experience btn px-2 text-danger fs-3 {{ checkLanguageSession() == 'ar' ? 'ps-0' : 'pe-0' }}"
                                                    title="{{ __('messages.common.delete') }}" data-bs-toggle="tooltip"
                                                    data-id="{{ $candidateExperience->id }}"><i
                                                        class="fa-solid fa-trash"></i></a>
                                            </div>
                                        </div>
                                        <span
                                            class="text-muted">{{ \Carbon\Carbon::parse($candidateExperience->start_date)->translatedFormat('jS M, Y') }}
                                            - </span>

                                        @if ($candidateExperience->currently_working)
                                            <span class="text-muted">{{ __('messages.candidate_profile.present') }}</span>
                                        @else
                                            <span class="text-muted">
                                                {{ \Carbon\Carbon::parse($candidateExperience->end_date)->translatedFormat('jS M, Y') }}
                                            </span>
                                        @endif
                                        <span class="text-muted"> | {{ $candidateExperience->country }}</span>
                                        @if (!empty($candidateExperience->description))
                                            <p class="mb-0 pb-md-0 pb-4">
                                                {{ Str::limit($candidateExperience->description, 225, '...') }}</p>
                                        @endif
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="candidate-education-panel candidate-profile-section" id="candidateEducationDetails">
            <div class="candidate-profile-section__header" data-education-section-header>
                <span>{{ __('messages.candidate_profile.education') }}</span>
                <span class="candidate-profile-section__header-actions">
                    <a href="javascript:void(0)" class="candidate-education-add" data-inline-education-add data-panel-add-action>
                        <i class="fa-solid fa-plus"></i>
                        <span>{{ __('messages.candidate_profile.add_education') }}</span>
                    </a>
                    <button type="button" class="candidate-profile-section__toggle" data-bs-toggle="collapse"
                        data-bs-target="#candidateEducationPanelBody" aria-expanded="true"
                        aria-controls="candidateEducationPanelBody" data-education-panel-toggle
                        data-collapse-label="{{ __('messages.candidate_profile.collapse') }}"
                        data-expand-label="{{ __('messages.candidate_profile.expand') }}">
                        <span>{{ __('messages.candidate_profile.collapse') }}</span>
                        <i class="fa-solid fa-chevron-up"></i>
                    </button>
                </span>
            </div>

            <div id="candidateEducationPanelBody" class="collapse show candidate-profile-section__collapse"
                 data-bs-parent="#candidateEducationAccordion">
                <div class="candidate-profile-section__body candidate-education-panel__body">
                <div class="candidate-education-inline-form d-none" data-education-add-form>
                    <h2 data-education-form-title>{{ __('messages.candidate_profile.education') }} {{ $candidateProfileNumber($data['candidateEducations']->count() + 1) }}</h2>
                    {{ Form::open(['id' => 'addNewEducationForm']) }}
                    {{ Form::hidden('country_id', $defaultEducationCountryId) }}
                    <div class="candidate-education-form-grid">
                        <div class="candidate-education-form-field">
                            {{ Form::label('degree_level_id', __('messages.candidate_profile.level_of_education'), ['class' => 'form-label required']) }}
                            {{ Form::select('degree_level_id', $data['degreeLevels'], null, ['class' => 'form-select', 'required', 'id' => 'degreeLevelId', 'placeholder' => 'Select your Level of Education']) }}
                        </div>
                        <div class="candidate-education-form-field">
                            {{ Form::label('degree_title', __('messages.candidate_profile.exam_degree_title'), ['class' => 'form-label required']) }}
                            {{ Form::select('degree_title', ['' => 'Select your Exam/Degree Title'], null, ['class' => 'form-select', 'required', 'data-education-title-select' => true, 'data-placeholder' => 'Select your Exam/Degree Title']) }}
                        </div>
                        <label class="candidate-education-check candidate-education-form-field--full" data-education-summary-row>
                            {{ Form::checkbox('show_summary', 1, false, ['class' => 'form-check-input']) }}
                            <span>{{ __('messages.candidate_profile.show_summary') }}</span>
                        </label>
                        <div class="candidate-education-form-field" data-education-major-field>
                            {{ Form::label('major', __('messages.candidate_profile.concentration_major_group'), ['class' => 'form-label required']) }}
                            {{ Form::text('major', null, ['class' => 'form-control', 'placeholder' => 'Enter your Concentration/ Major/Group', 'data-education-major-input' => true]) }}
                            <div class="candidate-education-major-menu d-none" data-education-major-menu></div>
                            <div class="input-group d-none" data-education-major-select-row>
                                {{ Form::select('major', ['' => __('messages.candidate_profile.concentration_major_group')], null, ['class' => 'form-select', 'data-education-major-select' => true]) }}
                                <button type="button" class="btn btn-outline-secondary" data-education-major-add>
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="candidate-education-form-field" data-education-board-field>
                            {{ Form::label('board', __('messages.candidate_profile.board'), ['class' => 'form-label required']) }}
                            {{ Form::select('board', $educationBoardOptions, null, ['class' => 'form-select']) }}
                        </div>
                        <div class="candidate-education-form-field candidate-education-form-field--full">
                            {{ Form::label('institute', __('messages.candidate_profile.institute_name'), ['class' => 'form-label required']) }}
                            {{ Form::text('institute', null, ['class' => 'form-control', 'required', 'placeholder' => 'Enter your Institute Name']) }}
                        </div>
                        <label class="candidate-education-check candidate-education-form-field--full">
                            {{ Form::checkbox('foreign_institute', 1, false, ['class' => 'form-check-input']) }}
                            <span>{{ __('messages.candidate_profile.foreign_institute') }}</span>
                        </label>
                        <div class="candidate-education-form-field candidate-education-form-field--full" data-education-foreign-country-field>
                            {{ Form::label('foreign_university_country', 'Country of Foreign University', ['class' => 'form-label required']) }}
                            {{ Form::text('foreign_university_country', null, ['class' => 'form-control', 'placeholder' => 'Enter country name']) }}
                        </div>
                        <div class="candidate-education-form-field" data-education-result-field>
                            {{ Form::label('result', __('messages.candidate_profile.result'), ['class' => 'form-label required']) }}
                            {{ Form::select('result', ['First Division/Class' => 'First Division/Class', 'Second Division/Class' => 'Second Division/Class', 'Third Division/Class' => 'Third Division/Class', 'Grade' => 'Grade', 'Appeared' => 'Appeared', 'Enrolled' => 'Enrolled', 'Awarded' => 'Awarded', 'Do not mention' => 'Do not mention', 'Pass' => 'Pass'], null, ['class' => 'form-select', 'required', 'placeholder' => 'Enter your Result', 'data-education-result-select' => true]) }}
                        </div>
                        <div class="candidate-education-form-field" data-education-marks-field>
                            {{ Form::label('marks_percentage', 'Marks(%)', ['class' => 'form-label required']) }}
                            {{ Form::text('marks_percentage', null, ['class' => 'form-control', 'placeholder' => 'Enter your Marks(%)', 'inputmode' => 'decimal', 'data-education-decimal-input' => true]) }}
                        </div>
                        <div class="candidate-education-form-field" data-education-cgpa-field>
                            {{ Form::label('cgpa', __('messages.candidate_profile.cgpa'), ['class' => 'form-label required']) }}
                            {{ Form::text('cgpa', null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_cgpa'), 'inputmode' => 'decimal', 'data-education-decimal-input' => true]) }}
                        </div>
                        <div class="candidate-education-form-field" data-education-scale-field>
                            {{ Form::label('scale', __('messages.candidate_profile.scale'), ['class' => 'form-label required']) }}
                            {{ Form::text('scale', null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.scale'), 'inputmode' => 'numeric', 'data-education-integer-input' => true]) }}
                        </div>
                        <div class="candidate-education-form-field" data-education-year-field>
                            {{ Form::label('year', __('messages.candidate_profile.year_of_passing'), ['class' => 'form-label required', 'data-education-year-label' => true]) }}
                            {{ Form::selectRange('year', date('Y') + 10, 1970, null, ['id' => 'educationYearId', 'class' => 'form-select', 'required', 'placeholder' => 'Enter your Year of Passing']) }}
                        </div>
                        <div class="candidate-education-form-field candidate-education-form-field--full" data-education-duration-field>
                            {{ Form::label('duration', 'Duration (Years)', ['class' => 'form-label']) }}
                            {{ Form::text('duration', null, ['class' => 'form-control', 'placeholder' => 'Enter your Duration (Years)']) }}
                        </div>
                        <div class="candidate-education-form-field candidate-education-form-field--full" data-education-achievement-field>
                            {{ Form::label('achievement', __('messages.candidate_profile.achievement'), ['class' => 'form-label']) }}
                            <div class="candidate-education-editor">
                                {{ Form::textarea('achievement', null, ['class' => 'd-none', 'data-quill-input' => true]) }}
                                <div class="candidate-education-quill" data-quill-editor
                                    data-placeholder="{{ __('messages.candidate_profile.enter_writing_texts') }}"></div>
                            </div>
                        </div>
                    </div>
                    <div class="candidate-profile-section-actions">
                        {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary', 'id' => 'btnEducationSave']) }}
                        <button type="button" class="btn btn-outline-secondary" data-education-add-close>{{ __('messages.common.close') }}</button>
                    </div>
                    {{ Form::close() }}
                </div>

                <div class="candidate-education-inline-form d-none" data-education-edit-form>
                    <h2 data-education-form-title>{{ __('messages.candidate_profile.education') }} {{ $candidateProfileNumber(1) }}</h2>
                    {{ Form::open(['id' => 'editCareerEducationForm']) }}
                    {{ Form::hidden('educationId', null, ['id' => 'educationId']) }}
                    {{ Form::hidden('country_id', $defaultEducationCountryId, ['id' => 'editEducationCountry']) }}
                    <div class="candidate-education-form-grid">
                        <div class="candidate-education-form-field">
                            {{ Form::label('degree_level_id', __('messages.candidate_profile.level_of_education'), ['class' => 'form-label required']) }}
                            {{ Form::select('degree_level_id', $data['degreeLevels'], null, ['class' => 'form-select', 'required', 'id' => 'editDegreeLevel', 'placeholder' => 'Select your Level of Education']) }}
                        </div>
                        <div class="candidate-education-form-field">
                            {{ Form::label('degree_title', __('messages.candidate_profile.exam_degree_title'), ['class' => 'form-label required']) }}
                            {{ Form::select('degree_title', ['' => 'Select your Exam/Degree Title'], null, ['class' => 'form-select', 'required', 'id' => 'editDegreeTitle', 'data-education-title-select' => true, 'data-placeholder' => 'Select your Exam/Degree Title']) }}
                        </div>
                        <label class="candidate-education-check candidate-education-form-field--full" data-education-summary-row>
                            {{ Form::checkbox('show_summary', 1, false, ['class' => 'form-check-input']) }}
                            <span>{{ __('messages.candidate_profile.show_summary') }}</span>
                        </label>
                        <div class="candidate-education-form-field" data-education-major-field>
                            {{ Form::label('major', __('messages.candidate_profile.concentration_major_group'), ['class' => 'form-label required']) }}
                            {{ Form::text('major', null, ['class' => 'form-control', 'placeholder' => 'Enter your Concentration/ Major/Group', 'data-education-major-input' => true]) }}
                            <div class="candidate-education-major-menu d-none" data-education-major-menu></div>
                            <div class="input-group d-none" data-education-major-select-row>
                                {{ Form::select('major', ['' => __('messages.candidate_profile.concentration_major_group')], null, ['class' => 'form-select', 'data-education-major-select' => true]) }}
                                <button type="button" class="btn btn-outline-secondary" data-education-major-add>
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="candidate-education-form-field" data-education-board-field>
                            {{ Form::label('board', __('messages.candidate_profile.board'), ['class' => 'form-label required']) }}
                            {{ Form::select('board', $educationBoardOptions, null, ['class' => 'form-select']) }}
                        </div>
                        <div class="candidate-education-form-field candidate-education-form-field--full">
                            {{ Form::label('institute', __('messages.candidate_profile.institute_name'), ['class' => 'form-label required']) }}
                            {{ Form::text('institute', null, ['class' => 'form-control', 'required', 'id' => 'editInstitute', 'placeholder' => 'Enter your Institute Name']) }}
                        </div>
                        <label class="candidate-education-check candidate-education-form-field--full">
                            {{ Form::checkbox('foreign_institute', 1, false, ['class' => 'form-check-input']) }}
                            <span>{{ __('messages.candidate_profile.foreign_institute') }}</span>
                        </label>
                        <div class="candidate-education-form-field candidate-education-form-field--full" data-education-foreign-country-field>
                            {{ Form::label('foreign_university_country', 'Country of Foreign University', ['class' => 'form-label required']) }}
                            {{ Form::text('foreign_university_country', null, ['class' => 'form-control', 'placeholder' => 'Enter country name']) }}
                        </div>
                        <div class="candidate-education-form-field" data-education-result-field>
                            {{ Form::label('result', __('messages.candidate_profile.result'), ['class' => 'form-label required']) }}
                            {{ Form::select('result', ['First Division/Class' => 'First Division/Class', 'Second Division/Class' => 'Second Division/Class', 'Third Division/Class' => 'Third Division/Class', 'Grade' => 'Grade', 'Appeared' => 'Appeared', 'Enrolled' => 'Enrolled', 'Awarded' => 'Awarded', 'Do not mention' => 'Do not mention', 'Pass' => 'Pass'], null, ['class' => 'form-select', 'required', 'id' => 'editResult', 'placeholder' => 'Enter your Result', 'data-education-result-select' => true]) }}
                        </div>
                        <div class="candidate-education-form-field" data-education-marks-field>
                            {{ Form::label('marks_percentage', 'Marks(%)', ['class' => 'form-label required']) }}
                            {{ Form::text('marks_percentage', null, ['class' => 'form-control', 'placeholder' => 'Enter your Marks(%)', 'inputmode' => 'decimal', 'data-education-decimal-input' => true]) }}
                        </div>
                        <div class="candidate-education-form-field" data-education-cgpa-field>
                            {{ Form::label('cgpa', __('messages.candidate_profile.cgpa'), ['class' => 'form-label required']) }}
                            {{ Form::text('cgpa', null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_cgpa'), 'inputmode' => 'decimal', 'data-education-decimal-input' => true]) }}
                        </div>
                        <div class="candidate-education-form-field" data-education-scale-field>
                            {{ Form::label('scale', __('messages.candidate_profile.scale'), ['class' => 'form-label required']) }}
                            {{ Form::text('scale', null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.scale'), 'inputmode' => 'numeric', 'data-education-integer-input' => true]) }}
                        </div>
                        <div class="candidate-education-form-field" data-education-year-field>
                            {{ Form::label('year', __('messages.candidate_profile.year_of_passing'), ['class' => 'form-label required', 'data-education-year-label' => true]) }}
                            {{ Form::selectRange('year', date('Y') + 10, 1970, null, ['class' => 'form-select', 'required', 'placeholder' => 'Enter your Year of Passing', 'id' => 'editYear']) }}
                        </div>
                        <div class="candidate-education-form-field candidate-education-form-field--full" data-education-duration-field>
                            {{ Form::label('duration', 'Duration (Years)', ['class' => 'form-label']) }}
                            {{ Form::text('duration', null, ['class' => 'form-control', 'placeholder' => 'Enter your Duration (Years)']) }}
                        </div>
                        <div class="candidate-education-form-field candidate-education-form-field--full" data-education-achievement-field>
                            {{ Form::label('achievement', __('messages.candidate_profile.achievement'), ['class' => 'form-label']) }}
                            <div class="candidate-education-editor">
                                {{ Form::textarea('achievement', null, ['class' => 'd-none', 'data-quill-input' => true]) }}
                                <div class="candidate-education-quill" data-quill-editor
                                    data-placeholder="{{ __('messages.candidate_profile.enter_writing_texts') }}"></div>
                            </div>
                        </div>
                    </div>
                    <div class="candidate-profile-section-actions">
                        {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary', 'id' => 'editEducationSave']) }}
                        <button type="button" class="btn btn-outline-secondary" data-education-edit-close>{{ __('messages.common.close') }}</button>
                    </div>
                    {{ Form::close() }}
                </div>

                <div class="candidate-education-container">
                    <div class="{{ $data['candidateEducations']->count() ? 'd-none' : '' }}" id="notfoundEducation">
                        <h5 class="candidate-education-empty">
                            {{ __('messages.candidate.education_not_found') }}
                        </h5>
                    </div>
                    @php
                        /** @var \App\Models\CandidateEducation $candidateEducation */
                    @endphp
                    @foreach ($data['candidateEducations'] as $candidateEducation)
                        <div class="candidate-education candidate-education-list-item" data-education-id="{{ $loop->index }}"
                            data-id="{{ $candidateEducation->id }}">
                            <div class="candidate-education-item__head">
                                <h2>{{ __('messages.candidate_profile.education') }} {{ $candidateProfileNumber($loop->iteration) }}</h2>
                                <div class="candidate-education-item__actions candidate-education-edit-delete">
                                    <a href="javascript:void(0)" class="candidate-education-action candidate-education-action--edit edit-candidate-education"
                                        title="{{ __('messages.common.edit') }}" data-bs-toggle="tooltip"
                                        data-id="{{ $candidateEducation->id }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        <span>{{ __('messages.common.edit') }}</span>
                                    </a>
                                    <a href="javascript:void(0)" class="candidate-education-action candidate-education-action--delete delete-education"
                                        title="{{ __('messages.common.delete') }}" data-bs-toggle="tooltip"
                                        data-id="{{ $candidateEducation->id }}">
                                        <i class="fa-solid fa-trash-can"></i>
                                        <span>{{ __('messages.common.delete') }}</span>
                                    </a>
                                </div>
                            </div>

                            <div class="candidate-education-detail-grid">
                                <div class="candidate-education-detail-column">
                                    @if(filled($candidateEducation->degreeLevel?->name))
                                        <div class="candidate-education-detail">
                                            <span>{{ __('messages.candidate_profile.level_of_education') }}</span>
                                            <strong class="education-degree-level">{{ $candidateEducation->degreeLevel->name }}</strong>
                                        </div>
                                    @endif
                                    @if(filled($candidateEducation->major))
                                        <div class="candidate-education-detail">
                                            <span>{{ __('messages.candidate_profile.concentration_major_group') }}</span>
                                            <strong>{{ $candidateEducation->major }}</strong>
                                        </div>
                                    @endif
                                    @if(filled($candidateEducation->board))
                                        <div class="candidate-education-detail">
                                            <span>{{ __('messages.candidate_profile.board') }}</span>
                                            <strong>{{ $candidateEducation->board }}</strong>
                                        </div>
                                    @endif
                                    @if(filled($candidateEducation->result))
                                        <div class="candidate-education-detail">
                                            <span>{{ __('messages.candidate_profile.result') }}</span>
                                            <strong>{{ $candidateEducation->result }}</strong>
                                        </div>
                                    @endif
                                    @if(filled($candidateEducation->marks_percentage))
                                        <div class="candidate-education-detail">
                                            <span>Marks(%)</span>
                                            <strong>{{ $candidateEducation->marks_percentage }}</strong>
                                        </div>
                                    @endif
                                    @if(filled($candidateEducation->scale))
                                        <div class="candidate-education-detail">
                                            <span>{{ __('messages.candidate_profile.scale') }}</span>
                                            <strong>{{ $candidateEducation->scale }}</strong>
                                        </div>
                                    @endif
                                    @if(filled($candidateEducation->duration))
                                        <div class="candidate-education-detail">
                                            <span>{{ __('messages.candidate_profile.duration_years') }}</span>
                                            <strong>{{ $candidateEducation->duration }}</strong>
                                        </div>
                                    @endif
                                </div>

                                <div class="candidate-education-detail-column">
                                    @if(filled($candidateEducation->degree_title))
                                        <div class="candidate-education-detail">
                                            <span>{{ __('messages.candidate_profile.exam_degree_title') }}</span>
                                            <strong>{{ $candidateEducation->degree_title }}</strong>
                                        </div>
                                    @endif
                                    @if(filled($candidateEducation->institute))
                                        <div class="candidate-education-detail">
                                            <span>{{ __('messages.candidate_profile.institute_name') }}</span>
                                            <strong>{{ $candidateEducation->institute }}</strong>
                                        </div>
                                    @endif
                                    @if(filled($candidateEducation->foreign_university_country))
                                        <div class="candidate-education-detail">
                                            <span>Country of Foreign University</span>
                                            <strong>{{ $candidateEducation->foreign_university_country }}</strong>
                                        </div>
                                    @endif
                                    @if(filled($candidateEducation->cgpa))
                                        <div class="candidate-education-detail">
                                            <span>{{ __('messages.candidate_profile.cgpa') }}</span>
                                            <strong>{{ $candidateEducation->cgpa }}</strong>
                                        </div>
                                    @endif
                                    @if(filled($candidateEducation->year))
                                        <div class="candidate-education-detail">
                                            <span>{{ __('messages.candidate_profile.year_of_passing') }}</span>
                                            <strong>{{ $candidateEducation->year }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="candidate-education-detail candidate-education-detail--full pt-5">
                                <span>{{ __('messages.candidate_profile.achievement') }}</span>
                                <strong>{!! filled($candidateEducation->achievement) ? $candidateEducation->achievement : '---' !!}</strong>
                            </div>
                        </div>
                    @endforeach
                </div>
                </div>
            </div>
        </div>

        <div class="candidate-education-panel candidate-profile-section" id="candidateTrainingDetails">
            <div class="candidate-profile-section__header collapsed">
                <span>{{ __('messages.candidate_profile.training') }}</span>
                <span class="candidate-profile-section__header-actions">
                    <a href="javascript:void(0)" class="candidate-education-add d-none" data-panel-add-action>
                        <i class="fa-solid fa-plus"></i>
                        <span>{{ __('messages.candidate_profile.add_training') }}</span>
                    </a>
                    <button type="button" class="candidate-profile-section__toggle" data-bs-toggle="collapse"
                        data-bs-target="#candidateTrainingPanelBody" aria-expanded="false"
                        aria-controls="candidateTrainingPanelBody"
                        data-collapse-label="{{ __('messages.candidate_profile.collapse') }}"
                        data-expand-label="{{ __('messages.candidate_profile.expand') }}">
                        <span>{{ __('messages.candidate_profile.expand') }}</span>
                        <i class="fa-solid fa-chevron-up"></i>
                    </button>
                </span>
            </div>
            <div id="candidateTrainingPanelBody" class="collapse candidate-profile-section__collapse"
                 data-bs-parent="#candidateEducationAccordion">
                <div class="candidate-profile-section__body candidate-education-panel__body">
                    <div class="candidate-training-container">
                        <div class="{{ $candidateTrainings->count() ? 'd-none' : '' }}" id="notfoundTraining">
                            <h5 class="candidate-education-empty">
                                {{ __('messages.candidate_profile.training_not_found') }}
                            </h5>
                        </div>
                        @foreach ($candidateTrainings as $candidateTraining)
                            <div class="candidate-education candidate-education-list-item" data-training-item
                                data-id="{{ $candidateTraining->id }}"
                                data-training-index="{{ $loop->iteration }}"
                                data-training-title="{{ $candidateTraining->title }}"
                                data-training-topics="{{ $candidateTraining->topics }}"
                                data-training-institute="{{ $candidateTraining->institute }}"
                                data-training-location="{{ $candidateTraining->location }}"
                                data-training-country="{{ $candidateTraining->country }}"
                                data-training-year="{{ $candidateTraining->year }}"
                                data-training-duration="{{ $candidateTraining->duration }}">
                                <div class="candidate-education-item__head">
                                    <h2>{{ __('messages.candidate_profile.training') }} {{ $candidateProfileNumber($loop->iteration) }}</h2>
                                    <div class="candidate-education-item__actions candidate-training-edit-delete">
                                        <a href="javascript:void(0)"
                                            class="candidate-education-action candidate-education-action--edit"
                                            data-training-edit>
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span>{{ __('messages.common.edit') }}</span>
                                        </a>
                                        <a href="javascript:void(0)"
                                            class="candidate-education-action candidate-education-action--delete"
                                            data-training-delete
                                            data-id="{{ $candidateTraining->id }}">
                                            <i class="fa-solid fa-trash-can"></i>
                                            <span>{{ __('messages.common.delete') }}</span>
                                        </a>
                                    </div>
                                </div>

                                <div class="candidate-education-detail-grid">
                                    <div class="candidate-education-detail-column">
                                        <div class="candidate-education-detail">
                                            <span>{{ __('messages.candidate_profile.training') }}</span>
                                            <strong data-training-value="title">{{ $candidateTraining->title }}</strong>
                                        </div>
                                        <div class="candidate-education-detail">
                                            <span>{{ __('messages.candidate_profile.topics_covered') }}</span>
                                            <strong data-training-value="topics">{{ $candidateTraining->topics ?: '---' }}</strong>
                                        </div>
                                        <div class="candidate-education-detail">
                                            <span>{{ __('messages.candidate_profile.institute') }}</span>
                                            <strong data-training-value="institute">{{ $candidateTraining->institute }}</strong>
                                        </div>
                                        <div class="candidate-education-detail">
                                            <span>{{ __('messages.candidate_profile.location') }}</span>
                                            <strong data-training-value="location">{{ $candidateTraining->location ?: '---' }}</strong>
                                        </div>
                                    </div>

                                    <div class="candidate-education-detail-column">
                                        <div class="candidate-education-detail">
                                            <span>{{ __('messages.company.country') }}</span>
                                            <strong data-training-value="country">{{ $candidateTraining->country }}</strong>
                                        </div>
                                        <div class="candidate-education-detail">
                                            <span>{{ __('messages.candidate_profile.training_year') }}</span>
                                            <strong data-training-value="year">{{ $candidateTraining->year }}</strong>
                                        </div>
                                        <div class="candidate-education-detail">
                                            <span>{{ __('messages.candidate_profile.duration') }}</span>
                                            <strong data-training-value="duration">{{ $candidateTraining->duration }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="candidate-education-inline-form d-none" data-training-form>
                        <h2 data-training-form-title>{{ __('messages.candidate_profile.training') }} {{ $candidateProfileNumber($candidateTrainings->count() + 1) }}</h2>
                        {{ Form::open(['id' => 'candidateTrainingForm']) }}
                        {{ Form::hidden('training_id', null, ['data-training-field' => 'id']) }}
                        {{ Form::hidden('training_index', null, ['data-training-field' => 'index']) }}
                        <div class="candidate-education-form-grid">
                            <div class="candidate-education-form-field">
                                {{ Form::label('title', __('messages.candidate_profile.training_title'), ['class' => 'form-label required']) }}
                                {{ Form::text('title', null, ['class' => 'form-control', 'required', 'placeholder' => __('messages.candidate_profile.enter_training_title'), 'data-training-field' => 'title']) }}
                            </div>
                            <div class="candidate-education-form-field">
                                {{ Form::label('country', __('messages.company.country'), ['class' => 'form-label required']) }}
                                {{ Form::text('country', null, ['class' => 'form-control', 'required', 'placeholder' => __('messages.candidate_profile.enter_country'), 'data-training-field' => 'country']) }}
                            </div>
                            <div class="candidate-education-form-field">
                                {{ Form::label('topics', __('messages.candidate_profile.topics_covered'), ['class' => 'form-label']) }}
                                {{ Form::text('topics', null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_topics_covered'), 'data-training-field' => 'topics']) }}
                            </div>
                            <div class="candidate-education-form-field">
                                {{ Form::label('year', __('messages.candidate_profile.training_year'), ['class' => 'form-label required']) }}
                                {{ Form::selectRange('year', date('Y'), 2000, null, ['class' => 'form-select', 'required', 'placeholder' => __('messages.candidate_profile.enter_training_year'), 'data-training-field' => 'year']) }}
                            </div>
                            <div class="candidate-education-form-field">
                                {{ Form::label('institute', __('messages.candidate_profile.institute'), ['class' => 'form-label required']) }}
                                {{ Form::text('institute', null, ['class' => 'form-control', 'required', 'placeholder' => __('messages.candidate_profile.enter_institute_name'), 'data-training-field' => 'institute']) }}
                            </div>
                            <div class="candidate-education-form-field">
                                {{ Form::label('duration', __('messages.candidate_profile.duration'), ['class' => 'form-label required']) }}
                                {{ Form::text('duration', null, ['class' => 'form-control', 'required', 'placeholder' => __('messages.candidate_profile.enter_duration'), 'data-training-field' => 'duration']) }}
                            </div>
                            <div class="candidate-education-form-field candidate-education-form-field--full">
                                {{ Form::label('location', __('messages.candidate_profile.location'), ['class' => 'form-label']) }}
                                {{ Form::text('location', null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_location'), 'data-training-field' => 'location']) }}
                            </div>
                        </div>
                        <div class="candidate-profile-section-actions">
                            {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary', 'id' => 'candidateTrainingSave']) }}
                            <button type="button" class="btn btn-outline-secondary" data-training-close>{{ __('messages.common.close') }}</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="candidate-education-panel candidate-profile-section" id="candidateProfessionalCertification">
            <div class="candidate-profile-section__header collapsed">
                <span>{{ __('messages.candidate_profile.professional_certification') }}</span>
                <span class="candidate-profile-section__header-actions">
                    <a href="javascript:void(0)" class="candidate-education-add d-none" data-certification-add-action>
                        <i class="fa-solid fa-plus"></i>
                        <span>{{ __('messages.candidate_profile.add_professional_certification') }}</span>
                    </a>
                    <button type="button" class="candidate-profile-section__toggle" data-bs-toggle="collapse"
                        data-bs-target="#candidateProfessionalCertificationPanelBody" aria-expanded="false"
                        aria-controls="candidateProfessionalCertificationPanelBody"
                        data-collapse-label="{{ __('messages.candidate_profile.collapse') }}"
                        data-expand-label="{{ __('messages.candidate_profile.expand') }}">
                        <span>{{ __('messages.candidate_profile.expand') }}</span>
                        <i class="fa-solid fa-chevron-up"></i>
                    </button>
                </span>
            </div>
            <div id="candidateProfessionalCertificationPanelBody" class="collapse candidate-profile-section__collapse"
                 data-bs-parent="#candidateEducationAccordion">
                <div class="candidate-profile-section__body candidate-education-panel__body">
                    <div class="candidate-certification-container">
                        @foreach ($candidateCertificationItems as $candidateCertification)
                            <div class="candidate-education candidate-education-list-item" data-certification-item
                                data-certification-index="{{ $loop->iteration }}"
                                data-certification-certification="{{ $candidateCertification['certification'] }}"
                                data-certification-institute="{{ $candidateCertification['institute'] }}"
                                data-certification-location="{{ $candidateCertification['location'] }}"
                                data-certification-duration="{{ $candidateCertification['duration'] }}">
                                <div class="candidate-education-item__head">
                                    <h2>{{ __('messages.candidate_profile.professional_certification') }} {{ $candidateProfileNumber($loop->iteration) }}</h2>
                                    <div class="candidate-education-item__actions candidate-certification-edit-delete">
                                        <a href="javascript:void(0)"
                                            class="candidate-education-action candidate-education-action--edit"
                                            data-certification-edit>
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span>{{ __('messages.common.edit') }}</span>
                                        </a>
                                        <a href="javascript:void(0)"
                                            class="candidate-education-action candidate-education-action--delete"
                                            data-certification-delete>
                                            <i class="fa-solid fa-trash-can"></i>
                                            <span>{{ __('messages.common.delete') }}</span>
                                        </a>
                                    </div>
                                </div>

                                <div class="candidate-education-detail-grid">
                                    <div class="candidate-education-detail-column">
                                        <div class="candidate-education-detail">
                                            <span>{{ __('messages.candidate_profile.certification') }}</span>
                                            <strong data-certification-value="certification">{{ $candidateCertification['certification'] }}</strong>
                                        </div>
                                        <div class="candidate-education-detail">
                                            <span>{{ __('messages.candidate_profile.location') }}</span>
                                            <strong data-certification-value="location">{{ $candidateCertification['location'] }}</strong>
                                        </div>
                                    </div>

                                    <div class="candidate-education-detail-column">
                                        <div class="candidate-education-detail">
                                            <span>{{ __('messages.candidate_profile.institute') }}</span>
                                            <strong data-certification-value="institute">{{ $candidateCertification['institute'] }}</strong>
                                        </div>
                                        <div class="candidate-education-detail">
                                            <span>{{ __('messages.candidate_profile.duration') }}</span>
                                            <strong data-certification-value="duration">{{ $candidateCertification['duration'] }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="candidate-education-inline-form d-none" data-certification-form>
                        <h2 data-certification-form-title>{{ __('messages.candidate_profile.professional_certification') }} {{ $candidateProfileNumber(count($candidateCertificationItems) + 1) }}</h2>
                        {{ Form::open(['id' => 'candidateCertificationForm']) }}
                        {{ Form::hidden('certification_index', null, ['data-certification-field' => 'index']) }}
                        <div class="candidate-education-form-grid">
                            <div class="candidate-education-form-field">
                                {{ Form::label('certification_name', __('messages.candidate_profile.certification'), ['class' => 'form-label required']) }}
                                {{ Form::text('certification_name', null, ['class' => 'form-control', 'required', 'placeholder' => __('messages.candidate_profile.enter_certification'), 'data-certification-field' => 'certification']) }}
                            </div>
                            <div class="candidate-education-form-field">
                                {{ Form::label('certification_institute', __('messages.candidate_profile.institute'), ['class' => 'form-label required']) }}
                                {{ Form::text('certification_institute', null, ['class' => 'form-control', 'required', 'placeholder' => __('messages.candidate_profile.enter_institute_name'), 'data-certification-field' => 'institute']) }}
                            </div>
                            <div class="candidate-education-form-field">
                                {{ Form::label('certification_location', __('messages.candidate_profile.location'), ['class' => 'form-label']) }}
                                {{ Form::text('certification_location', null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_location'), 'data-certification-field' => 'location']) }}
                            </div>
                            <div class="candidate-education-form-field">
                                {{ Form::label('certification_duration', __('messages.candidate_profile.duration'), ['class' => 'form-label required candidate-certification-duration-label']) }}
                                <div class="input-group candidate-certification-duration-input">
                                    <span class="input-group-text candidate-certification-duration-icon">
                                        <i class="fa-regular fa-calendar"></i>
                                    </span>
                                    {{ Form::text('certification_duration', null, ['class' => 'form-control candidate-certification-duration-control', 'required', 'placeholder' => __('messages.candidate_profile.enter_certification_duration'), 'data-certification-field' => 'duration']) }}
                                </div>
                            </div>
                        </div>
                        <div class="candidate-profile-section-actions">
                            {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
                            <button type="button" class="btn btn-outline-secondary" data-certification-close>{{ __('messages.common.close') }}</button>
                        </div>
                        {{ Form::close() }}
                    </div>

                    {{-- <div class="candidate-certification-footer-action">
                        <a href="javascript:void(0)" class="candidate-certification-add-outline" data-certification-add-action>
                            <i class="fa-solid fa-plus"></i>
                            <span>Add Professional Certification</span>
                        </a>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    {{--                                @if($candidateExperience->currently_working)--}}
    {{--                                    <span class="text-muted">{{ __('messages.candidate_profile.present') }}</span>--}}
    {{--                                @else--}}
    {{--                                    <span class="text-muted"> {{\Carbon\Carbon::parse($candidateExperience->end_date)->format('jS M, Y')}} </span>--}}
    {{--                                @endif--}}
    {{--                                <span> | {{ $candidateExperience->country }}</span>--}}
    {{--                                @if(!empty($candidateExperience->description))--}}
    {{--                                    <p class="mb-0 pb-md-0 pb-4">{{ Str::limit($candidateExperience->description,225,'...') }}</p>--}}
    {{--                                @endif--}}

    {{--                                <div class="article-cta candidate-experience-edit-delete">--}}
    {{--                                    <a href="javascript:void(0)" class="btn btn-warning action-btn edit-experience" title="Edit"--}}
    {{--                                       data-id="{{ $candidateExperience->id }}"><i class="fa fa-edit p-1"></i></a>--}}
    {{--                                    <a href="javascript:void(0)" class="btn btn-danger action-btn delete-experience" title="Delete"--}}
    {{--                                       data-id="{{ $candidateExperience->id }}"><i class="fa fa-trash p-1"></i></a>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </article>--}}
    {{--                    </div>--}}
    {{--                @endforeach--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </section>--}}
    {{--    <br>--}}
    {{--    <section class="section">--}}
    {{--        <div class="section-header candidate-experience-header">--}}
    {{--            <h1>{{ __('messages.candidate_profile.education') }}</h1>--}}
    {{--            <div class="section-header-breadcrumb justify-content-end">--}}
    {{--                <a--}}
    {{--                   class="btn btn-primary form-btn addEducationModal" data-bs-toggle="modal"--}}
    {{--                   data-bs-target="#addEducationModal">{{ __('messages.candidate_profile.add_education') }}--}}
    {{--                    <i class="fas fa-plus"></i></a>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <div class="section-body">--}}
    {{--            <div class="row candidate-education-container">--}}
    {{--                <div class="col-12 {{ ($data['candidateEducations']->count()) ? 'd-none' : '' }}" id="notfoundEducation">--}}
    {{--                    <h4 class="product-item pb-5 d-flex justify-content-center">--}}
    {{--                        {{ __('messages.candidate.education_not_found') }}--}}
    {{--                    </h4>--}}
    {{--                </div>--}}
    {{--                @php--}}
    {{--                    /** @var \App\Models\CandidateEducation $candidateEducation */--}}
    {{--                @endphp--}}
    {{--                @foreach($data['candidateEducations'] as $candidateEducation)--}}
    {{--                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 candidate-education"--}}
    {{--                         data-education-id="{{ $loop->index }}" data-id="{{ $candidateEducation->id }}">--}}
    {{--                        <article class="article article-style-b">--}}
    {{--                            <div class="article-details">--}}
    {{--                                <div class="article-title">--}}
    {{--                                    <h4 class="text-primary education-degree-level">{{ $candidateEducation->degreeLevel->name }}</h4>--}}
    {{--                                    <h6 class="text-muted">{{ $candidateEducation->degree_title }}</h6>--}}
    {{--                                </div>--}}
    {{--                                <span class="text-muted">{{ $candidateEducation->year }} | {{ $candidateEducation->country }}</span>--}}
    {{--                                <p class="mb-0 pb-md-0 pb-4">{{ $candidateEducation->institute }}</p>--}}
    {{--                                <div class="article-cta candidate-education-edit-delete">--}}
    {{--                                    <a href="javascript:void(0)" class="btn btn-warning action-btn edit-education" title="Edit"--}}
    {{--                                       data-id="{{ $candidateEducation->id }}"><i class="fa fa-edit p-1"></i></a>--}}
    {{--                                    <a href="javascript:void(0)" class="btn btn-danger action-btn delete-education" title="Delete"--}}
    {{--                                       data-id="{{ $candidateEducation->id }}"><i class="fa fa-trash p-1"></i></a>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </article>--}}
    {{--                    </div>--}}
    {{--                @endforeach--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </section>--}}
    @include('candidate.profile.modals.add_experience_modal')
    @include('candidate.profile.modals.edit_experience_modal')
    @include('candidate.profile.templates.templates')
    {{ Form::hidden('indexCareerInfoData', true, ['id' => 'indexCareerInfoData']) }}
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const formatCandidateProfileNumber = function (number) {
                const value = String(number);

                if (!window.candidateProfileUseBanglaNumber) {
                    return value;
                }

                const banglaNumbers = {
                    0: '০',
                    1: '১',
                    2: '২',
                    3: '৩',
                    4: '৪',
                    5: '৫',
                    6: '৬',
                    7: '৭',
                    8: '৮',
                    9: '৯',
                };

                return value.replace(/[0-9]/g, function (digit) {
                    return banglaNumbers[digit];
                });
            };

            const getNumberedSectionTitle = function (label, number) {
                return label + ' ' + formatCandidateProfileNumber(number);
            };

            const trainingPanel = document.getElementById('candidateTrainingDetails');
            const trainingFormWrap = trainingPanel ? trainingPanel.querySelector('[data-training-form]') : null;
            const trainingList = trainingPanel ? trainingPanel.querySelector('.candidate-training-container') : null;
            const trainingAdd = trainingPanel ? trainingPanel.querySelector('[data-panel-add-action]') : null;
            const trainingForm = document.getElementById('candidateTrainingForm');

            if (trainingPanel && trainingFormWrap && trainingList && trainingForm) {
                const trainingTitle = trainingFormWrap.querySelector('[data-training-form-title]');
                let activeTrainingItem = null;
                const fields = {
                    id: trainingForm.querySelector('[data-training-field="id"]'),
                    index: trainingForm.querySelector('[data-training-field="index"]'),
                    title: trainingForm.querySelector('[data-training-field="title"]'),
                    country: trainingForm.querySelector('[data-training-field="country"]'),
                    topics: trainingForm.querySelector('[data-training-field="topics"]'),
                    year: trainingForm.querySelector('[data-training-field="year"]'),
                    institute: trainingForm.querySelector('[data-training-field="institute"]'),
                    duration: trainingForm.querySelector('[data-training-field="duration"]'),
                    location: trainingForm.querySelector('[data-training-field="location"]'),
                };

                const getFormValue = function (value) {
                    return value && value !== '---' ? value : '';
                };

                const closeTrainingForm = function () {
                    if (activeTrainingItem) {
                        activeTrainingItem.querySelector('.candidate-education-detail-grid').classList.remove('d-none');
                        activeTrainingItem.querySelector('.candidate-education-item__actions').classList.remove('d-none');
                        activeTrainingItem = null;
                    }

                    trainingList.classList.remove('d-none');
                    trainingFormWrap.classList.add('d-none');
                    trainingFormWrap.classList.remove('candidate-training-form--add', 'candidate-training-form--edit');
                    trainingForm.reset();
                    trainingList.insertAdjacentElement('afterend', trainingFormWrap);
                };

                const scrollToTrainingForm = function () {
                    window.setTimeout(function () {
                        const stickyOffset = 150;
                        const top = window.scrollY + trainingFormWrap.getBoundingClientRect().top - stickyOffset;

                        window.scrollTo({
                            top: Math.max(0, top),
                            behavior: 'smooth',
                        });
                    }, 100);
                };

                const setTrainingFormMode = function (item, shouldScroll) {
                    closeTrainingForm();

                    const index = item
                        ? item.dataset.trainingIndex
                        : String(trainingList.querySelectorAll('[data-training-item]').length + 1);

                    trainingTitle.textContent = getNumberedSectionTitle(window.candidateProfileTrainingLabel, index);
                    fields.id.value = item ? item.dataset.id : '';
                    fields.index.value = index;
                    fields.title.value = item ? getFormValue(item.dataset.trainingTitle) : '';
                    fields.country.value = item ? getFormValue(item.dataset.trainingCountry) : '';
                    fields.topics.value = item ? getFormValue(item.dataset.trainingTopics) : '';
                    fields.year.value = item ? getFormValue(item.dataset.trainingYear) : '';
                    fields.institute.value = item ? getFormValue(item.dataset.trainingInstitute) : '';
                    fields.duration.value = item ? getFormValue(item.dataset.trainingDuration) : '';
                    fields.location.value = item ? getFormValue(item.dataset.trainingLocation) : '';

                    if (item) {
                        activeTrainingItem = item;
                        trainingTitle.classList.add('d-none');
                        trainingFormWrap.classList.add('candidate-training-form--edit');
                        item.querySelector('.candidate-education-detail-grid').classList.add('d-none');
                        item.querySelector('.candidate-education-item__actions').classList.add('d-none');
                        item.querySelector('.candidate-education-item__head').insertAdjacentElement('afterend', trainingFormWrap);
                    } else {
                        trainingTitle.classList.remove('d-none');
                        trainingFormWrap.classList.add('candidate-training-form--add');
                        trainingList.insertAdjacentElement('afterend', trainingFormWrap);
                    }

                    trainingFormWrap.classList.remove('d-none');
                    if (shouldScroll) {
                        scrollToTrainingForm();
                    }
                };

                trainingAdd.addEventListener('click', function (event) {
                    event.preventDefault();
                    trainingForm.reset();
                    setTrainingFormMode(null, true);
                });

                trainingPanel.addEventListener('click', function (event) {
                    const editButton = event.target.closest('[data-training-edit]');
                    const deleteButton = event.target.closest('[data-training-delete]');

                    if (editButton) {
                        event.preventDefault();
                        const item = editButton.closest('[data-training-item]');
                        if (item) {
                            setTrainingFormMode(item, false);
                        }
                        return;
                    }

                    if (deleteButton) {
                        event.preventDefault();
                        const trainingId = deleteButton.dataset.id;

                        swal({
                            title: Lang.get('js.delete') + ' !',
                            text: Lang.get('js.are_you_sure') + ' "' + window.candidateProfileTrainingLabel + '" ?',
                            buttons: {
                                confirm: Lang.get('js.yes_delete'),
                                cancel: Lang.get('js.no_cancel'),
                            },
                            reverseButtons: true,
                            icon: 'warning',
                        }).then(function (willDelete) {
                            if (!willDelete) {
                                return;
                            }

                            $.ajax({
                                url: route('training.destroy', trainingId),
                                type: 'DELETE',
                                success: function (result) {
                                    displaySuccessMessage(result.message);
                                    window.location.reload();
                                },
                                error: function (result) {
                                    displayErrorMessage(result.responseJSON.message);
                                },
                            });
                        });
                    }
                });

                trainingPanel.querySelector('[data-training-close]').addEventListener('click', function () {
                    closeTrainingForm();
                });

                trainingForm.addEventListener('submit', function (event) {
                    event.preventDefault();
                    const trainingId = fields.id.value;
                    const isUpdate = !!trainingId;
                    const saveButton = $('#candidateTrainingSave');

                    saveButton.prop('disabled', true);
                    $.ajax({
                        url: isUpdate ? route('candidate.update-training', trainingId) : route('candidate.create-training'),
                        type: isUpdate ? 'PUT' : 'POST',
                        data: $(trainingForm).serialize(),
                        success: function (result) {
                            displaySuccessMessage(result.message);
                            window.location.reload();
                        },
                        error: function (result) {
                            displayErrorMessage(result.responseJSON.message);
                        },
                        complete: function () {
                            saveButton.prop('disabled', false);
                        },
                    });
                });
            }

            const certificationPanel = document.getElementById('candidateProfessionalCertification');
            const certificationFormWrap = certificationPanel ? certificationPanel.querySelector('[data-certification-form]') : null;
            const certificationList = certificationPanel ? certificationPanel.querySelector('.candidate-certification-container') : null;
            const certificationForm = document.getElementById('candidateCertificationForm');
                const certificationFooterAction = certificationPanel ? certificationPanel.querySelector('.candidate-certification-footer-action') : null;

                if (certificationPanel && certificationFormWrap && certificationList && certificationForm) {
                    const certificationTitle = certificationFormWrap.querySelector('[data-certification-form-title]');
                    let activeCertificationItem = null;
                    let certificationDurationPicker = null;
                    const fields = {
                        index: certificationForm.querySelector('[data-certification-field="index"]'),
                        certification: certificationForm.querySelector('[data-certification-field="certification"]'),
                        institute: certificationForm.querySelector('[data-certification-field="institute"]'),
                        location: certificationForm.querySelector('[data-certification-field="location"]'),
                    duration: certificationForm.querySelector('[data-certification-field="duration"]'),
                };

                    const getCertificationFormValue = function (value) {
                        return value && value !== '---' ? value : '';
                    };

                    const parseCertificationDuration = function (duration) {
                        if (!window.moment || !duration) {
                            return null;
                        }

                        const dates = duration.split(/\s+(?:to|-)\s+/i);
                        if (dates.length !== 2) {
                            return null;
                        }

                        const startDate = moment(dates[0], 'DD MMM YYYY', true);
                        const endDate = moment(dates[1], 'DD MMM YYYY', true);

                        return startDate.isValid() && endDate.isValid()
                            ? { startDate, endDate }
                            : null;
                    };

                    const closeCertificationForm = function () {
                        if (activeCertificationItem) {
                            activeCertificationItem.querySelector('.candidate-education-detail-grid').classList.remove('d-none');
                            activeCertificationItem.querySelector('.candidate-education-item__actions').classList.remove('d-none');
                            activeCertificationItem = null;
                    }

                    certificationList.classList.remove('d-none');
                    certificationFormWrap.classList.add('d-none');
                    certificationFormWrap.classList.remove('candidate-training-form--add', 'candidate-training-form--edit');
                    if (certificationFooterAction) {
                        certificationFooterAction.classList.remove('d-none');
                    }
                    certificationForm.reset();
                    certificationList.insertAdjacentElement('afterend', certificationFormWrap);
                };

                const scrollToCertificationForm = function () {
                    window.setTimeout(function () {
                        certificationFormWrap.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start',
                        });
                    }, 50);
                };

                const setCertificationFormMode = function (item, shouldScroll) {
                    closeCertificationForm();

                    const index = item
                        ? item.dataset.certificationIndex
                        : String(certificationList.querySelectorAll('[data-certification-item]').length + 1);

                    certificationTitle.textContent = getNumberedSectionTitle(window.candidateProfileCertificationLabel, index);
                    fields.index.value = index;
                    fields.certification.value = item ? getCertificationFormValue(item.dataset.certificationCertification) : '';
                    fields.institute.value = item ? getCertificationFormValue(item.dataset.certificationInstitute) : '';
                    fields.location.value = item ? getCertificationFormValue(item.dataset.certificationLocation) : '';
                    fields.duration.value = item ? getCertificationFormValue(item.dataset.certificationDuration) : '';
                    fields.duration.value = fields.duration.value.replace(/\s+to\s+/i, ' - ');
                    const certificationDurationDates = parseCertificationDuration(fields.duration.value);

                    if (certificationDurationPicker && certificationDurationDates) {
                        certificationDurationPicker.setStartDate(certificationDurationDates.startDate);
                        certificationDurationPicker.setEndDate(certificationDurationDates.endDate);
                    }

                    if (item) {
                        activeCertificationItem = item;
                        certificationTitle.classList.add('d-none');
                        certificationFormWrap.classList.add('candidate-training-form--edit');
                        item.querySelector('.candidate-education-detail-grid').classList.add('d-none');
                        item.querySelector('.candidate-education-item__actions').classList.add('d-none');
                        item.querySelector('.candidate-education-item__head').insertAdjacentElement('afterend', certificationFormWrap);
                    } else {
                        certificationTitle.classList.remove('d-none');
                        certificationFormWrap.classList.add('candidate-training-form--add');
                        certificationList.insertAdjacentElement('afterend', certificationFormWrap);
                    }

                    if (certificationFooterAction) {
                        certificationFooterAction.classList.add('d-none');
                    }
                    certificationFormWrap.classList.remove('d-none');
                    if (shouldScroll) {
                        scrollToCertificationForm();
                    }
                };

                const reindexCertificationItems = function () {
                    const items = certificationList.querySelectorAll('[data-certification-item]');
                    items.forEach(function (item, idx) {
                        const newIndex = idx + 1;
                        item.dataset.certificationIndex = String(newIndex);
                        const heading = item.querySelector('.candidate-education-item__head h2');
                        if (heading) {
                            heading.textContent = getNumberedSectionTitle(window.candidateProfileCertificationLabel, newIndex);
                        }
                    });
                };

                certificationPanel.addEventListener('click', function (event) {
                    const addButton = event.target.closest('[data-certification-add-action]');
                    const editButton = event.target.closest('[data-certification-edit]');
                    const deleteButton = event.target.closest('[data-certification-delete]');

                    if (addButton) {
                        event.preventDefault();
                        certificationForm.reset();
                        setCertificationFormMode(null, true);
                        return;
                    }

                    if (editButton) {
                        event.preventDefault();
                        const item = editButton.closest('[data-certification-item]');
                        if (item) {
                            setCertificationFormMode(item, false);
                        }
                        return;
                    }

                    if (deleteButton) {
                        event.preventDefault();
                        const item = deleteButton.closest('[data-certification-item]');
                        if (!item) return;

                        const certName = item.dataset.certificationCertification || '';

                        swal({
                            title: Lang.get('js.delete') || 'Delete',
                            text: (Lang.get('js.are_you_sure') || 'Are you sure you want to delete') + (certName ? ' "' + certName + '"' : '') + '?',
                            buttons: {
                                confirm: Lang.get('js.yes_delete') || 'Yes, Delete',
                                cancel: Lang.get('js.no_cancel') || 'No, Cancel',
                            },
                            reverseButtons: true,
                            icon: 'warning',
                        }).then(function (willDelete) {
                            if (!willDelete) {
                                return;
                            }

                            if (activeCertificationItem === item) {
                                closeCertificationForm();
                            }

                            item.remove();
                            reindexCertificationItems();
                            if (typeof displaySuccessMessage === 'function') {
                                displaySuccessMessage('Professional Certification deleted successfully.');
                            }
                        });
                    }
                });

                certificationPanel.querySelector('[data-certification-close]').addEventListener('click', function () {
                    closeCertificationForm();
                });

                certificationForm.addEventListener('submit', function (event) {
                    event.preventDefault();
                    const index = fields.index.value;
                    let item = certificationList.querySelector('[data-certification-index="' + index + '"]');

                    if (!item) {
                        const sourceItem = certificationList.querySelector('[data-certification-item]');
                        if (!sourceItem) {
                            return;
                        }

                        item = sourceItem.cloneNode(true);
                        item.dataset.certificationIndex = index;
                        item.querySelector('.candidate-education-item__head h2').textContent =
                            getNumberedSectionTitle(window.candidateProfileCertificationLabel, index);
                        certificationList.appendChild(item);
                    } else {
                        item.querySelector('.candidate-education-detail-grid').classList.remove('d-none');
                        item.querySelector('.candidate-education-item__actions').classList.remove('d-none');
                    }

                    ['certification', 'institute', 'location', 'duration'].forEach(function (field) {
                        const fieldValue = fields[field].value || '---';
                        const value = field === 'duration'
                            ? fieldValue.replace(/\s+-\s+/i, ' to ')
                            : fieldValue;
                        item.dataset['certification' + field.charAt(0).toUpperCase() + field.slice(1)] = value;
                        item.querySelector('[data-certification-value="' + field + '"]').textContent = value;
                    });

                    closeCertificationForm();
                });

                if (window.jQuery && typeof jQuery.fn.daterangepicker === 'function' && window.moment) {
                    const durationInput = jQuery(fields.duration);
                    durationInput.daterangepicker({
                        autoUpdateInput: false,
                        drops: 'down',
                        opens: 'center',
                        parentEl: 'body',
                        startDate: moment('2024-12-31'),
                        endDate: moment('2025-10-30'),
                        locale: {
                            format: 'DD MMM YYYY',
                            separator: ' - ',
                            applyLabel: 'Apply',
                            cancelLabel: 'Cancel',
                        },
                    });
                    certificationDurationPicker = durationInput.data('daterangepicker');
                    certificationDurationPicker.container.addClass('candidate-certification-daterangepicker');

                    durationInput.on('apply.daterangepicker', function (event, picker) {
                        fields.duration.value = picker.startDate.format('DD MMM YYYY') + ' - ' + picker.endDate.format('DD MMM YYYY');
                    });

                    durationInput.on('cancel.daterangepicker', function () {
                        fields.duration.value = '';
                    });
                }
            document.addEventListener('click', function (event) {
                const deleteExpBtn = event.target.closest('.delete-experience');
                if (!deleteExpBtn) {
                    return;
                }

                event.preventDefault();
                const experienceId = deleteExpBtn.dataset.id;
                if (!experienceId) {
                    return;
                }

                const titleText = (typeof Lang !== 'undefined' && Lang.get('js.delete')) ? Lang.get('js.delete') : 'Delete';
                const textMsg = (typeof Lang !== 'undefined' && Lang.get('js.are_you_sure')) ? Lang.get('js.are_you_sure') + ' "' + (Lang.get('js.experience') || 'Experience') + '"?' : 'Are you sure you want to delete this experience?';
                const confirmBtnText = (typeof Lang !== 'undefined' && Lang.get('js.yes_delete')) ? Lang.get('js.yes_delete') : 'Yes, Delete';
                const cancelBtnText = (typeof Lang !== 'undefined' && Lang.get('js.no_cancel')) ? Lang.get('js.no_cancel') : 'No, Cancel';

                const executeDelete = function () {
                    const token = document.querySelector('meta[name="csrf-token"]');
                    fetch(route('experience.destroy', experienceId), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token ? token.content : '',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ _method: 'DELETE' }),
                    })
                    .then(function (response) {
                        if (!response.ok) {
                            return response.json().then(function (data) {
                                throw new Error(data.message || 'Unable to delete experience.');
                            });
                        }
                        return response.json();
                    })
                    .then(function (result) {
                        if (typeof displaySuccessMessage === 'function') {
                            displaySuccessMessage(result.message || 'Experience deleted successfully');
                        }
                        window.location.reload();
                    })
                    .catch(function (error) {
                        if (typeof displayErrorMessage === 'function') {
                            displayErrorMessage(error.message);
                        } else {
                            alert(error.message);
                        }
                    });
                };

                if (typeof swal === 'function') {
                    swal({
                        title: titleText,
                        text: textMsg,
                        buttons: {
                            confirm: confirmBtnText,
                            cancel: cancelBtnText,
                        },
                        reverseButtons: true,
                        icon: 'warning',
                    }).then(function (willDelete) {
                        if (willDelete) {
                            executeDelete();
                        }
                    });
                } else if (typeof Swal === 'function') {
                    Swal.fire({
                        title: titleText,
                        text: textMsg,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: confirmBtnText,
                        cancelButtonText: cancelBtnText,
                        reverseButtons: true,
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            executeDelete();
                        }
                    });
                } else {
                    if (confirm(textMsg)) {
                        executeDelete();
                    }
                }
            });
        });

        {{-- let addExperienceUrl = "{{ route('candidate.create-experience') }}"; --}}
        {{-- let experienceUrl = "{{ url('candidate/candidate-experience') }}/"; --}}
        {{-- let addEducationUrl = "{{ route('candidate.create-education') }}"; --}}
        {{-- let candidateUrl = "{{ url('candidate') }}/"; --}}
        {{-- let educationUrl = "{{ url('candidate/candidate-education') }}/"; --}}
        {{-- let present = "{{ __('messages.candidate_profile.present') }}"; --}}
        // let isEdit = false;
    </script>
    {{--    <script src="{{ asset('assets/js/moment.min.js') }}"></script> --}}
    {{--    <script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script> --}}
    {{--    <script src="{{mix('assets/js/candidate-profile/candidate_career_informations.js')}}"></script> --}}
@endpush
