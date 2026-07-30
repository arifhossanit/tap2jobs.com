@extends('candidate.profile.index')
@push('css')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}">
@endpush
@section('section')
    @php
        $defaultEducationCountryId = collect($data['countries'] ?? [])->search('Bangladesh');
        if ($defaultEducationCountryId === false) {
            $defaultEducationCountryId = collect($data['countries'] ?? [])->keys()->first();
        }
    @endphp
    <div class="mb-xl-8 candidate-career-info-page">
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

        <div class="candidate-education-panel" id="candidateEducationDetails">
            <div class="candidate-education-panel__header collapsed" data-education-section-header>
                <h1>{{ __('messages.candidate_profile.education') }}</h1>
                <div class="candidate-education-panel__actions">
                    <a href="javascript:void(0)" class="candidate-education-add d-none" data-inline-education-add>
                        <i class="fa-solid fa-plus"></i>
                        <span>{{ __('messages.candidate_profile.add_education') }}</span>
                    </a>
                    <button type="button" class="candidate-education-collapse" data-bs-toggle="collapse"
                        data-bs-target="#candidateEducationPanelBody" aria-expanded="false"
                        aria-controls="candidateEducationPanelBody" data-education-panel-toggle
                        data-collapse-label="{{ __('messages.candidate_profile.collapse') }}"
                        data-expand-label="{{ __('messages.candidate_profile.expand') }}">
                        <span>{{ __('messages.candidate_profile.expand') }}</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </div>
            </div>

            <div id="candidateEducationPanelBody" class="collapse">
                <div class="candidate-education-inline-form d-none" data-education-add-form>
                    <h2>{{ __('messages.candidate_profile.education') }} 1</h2>
                    {{ Form::open(['id' => 'addNewEducationForm']) }}
                    {{ Form::hidden('country_id', $defaultEducationCountryId) }}
                    <div class="candidate-education-form-grid">
                        <div class="candidate-education-form-field">
                            {{ Form::label('degree_level_id', __('messages.candidate_profile.level_of_education'), ['class' => 'form-label required']) }}
                            {{ Form::select('degree_level_id', $data['degreeLevels'], null, ['class' => 'form-select', 'required', 'id' => 'degreeLevelId', 'placeholder' => __('messages.company.select_degree_level')]) }}
                        </div>
                        <div class="candidate-education-form-field">
                            {{ Form::label('degree_title', __('messages.candidate_profile.exam_degree_title'), ['class' => 'form-label required']) }}
                            {{ Form::text('degree_title', null, ['class' => 'form-control', 'required', 'placeholder' => __('messages.candidate_profile.exam_degree_title')]) }}
                        </div>
                        <label class="candidate-education-check">
                            {{ Form::checkbox('show_summary', 1, false, ['class' => 'form-check-input']) }}
                            <span>Show this degree in summary view at employer's end</span>
                        </label>
                        <div></div>
                        <div class="candidate-education-form-field">
                            {{ Form::label('major', __('messages.candidate_profile.concentration_major_group'), ['class' => 'form-label required']) }}
                            {{ Form::text('major', null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.concentration_major_group')]) }}
                        </div>
                        <div></div>
                        <div class="candidate-education-form-field candidate-education-form-field--full">
                            {{ Form::label('institute', __('messages.candidate_profile.institute_name'), ['class' => 'form-label required']) }}
                            {{ Form::text('institute', null, ['class' => 'form-control', 'required', 'placeholder' => __('messages.candidate_profile.institute_name')]) }}
                        </div>
                        <label class="candidate-education-check candidate-education-form-field--full">
                            {{ Form::checkbox('foreign_institute', 1, false, ['class' => 'form-check-input']) }}
                            <span>This is a foreign institute</span>
                        </label>
                        <div class="candidate-education-form-field">
                            {{ Form::label('result', __('messages.candidate_profile.result'), ['class' => 'form-label required']) }}
                            {{ Form::select('result', ['Grade' => 'Grade', 'First Division/Class' => 'First Division/Class', 'Second Division/Class' => 'Second Division/Class'], null, ['class' => 'form-select', 'required', 'placeholder' => __('messages.candidate_profile.result')]) }}
                        </div>
                        <div class="candidate-education-form-field">
                            {{ Form::label('cgpa', __('messages.candidate_profile.cgpa'), ['class' => 'form-label required']) }}
                            {{ Form::text('cgpa', null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_cgpa')]) }}
                        </div>
                        <div class="candidate-education-form-field">
                            {{ Form::label('scale', __('messages.candidate_profile.scale'), ['class' => 'form-label required']) }}
                            {{ Form::text('scale', null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.scale')]) }}
                        </div>
                        <div class="candidate-education-form-field">
                            {{ Form::label('year', __('messages.candidate_profile.year_of_passing'), ['class' => 'form-label required']) }}
                            {{ Form::selectRange('year', date('Y'), 2000, null, ['id' => 'educationYearId', 'class' => 'form-select', 'required', 'placeholder' => __('messages.candidate_profile.enter_year_of_passing')]) }}
                        </div>
                        <div class="candidate-education-form-field candidate-education-form-field--full">
                            {{ Form::label('duration', __('messages.candidate_profile.duration_years'), ['class' => 'form-label']) }}
                            {{ Form::text('duration', null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.duration_years')]) }}
                        </div>
                        <div class="candidate-education-form-field candidate-education-form-field--full">
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
                    <h2>{{ __('messages.candidate_profile.education') }} 1</h2>
                    {{ Form::open(['id' => 'editCareerEducationForm']) }}
                    {{ Form::hidden('educationId', null, ['id' => 'educationId']) }}
                    {{ Form::hidden('country_id', $defaultEducationCountryId, ['id' => 'editEducationCountry']) }}
                    <div class="candidate-education-form-grid">
                        <div class="candidate-education-form-field">
                            {{ Form::label('degree_level_id', __('messages.candidate_profile.level_of_education'), ['class' => 'form-label required']) }}
                            {{ Form::select('degree_level_id', $data['degreeLevels'], null, ['class' => 'form-select', 'required', 'id' => 'editDegreeLevel']) }}
                        </div>
                        <div class="candidate-education-form-field">
                            {{ Form::label('degree_title', __('messages.candidate_profile.exam_degree_title'), ['class' => 'form-label required']) }}
                            {{ Form::text('degree_title', null, ['class' => 'form-control', 'required', 'id' => 'editDegreeTitle', 'placeholder' => __('messages.candidate_profile.exam_degree_title')]) }}
                        </div>
                        <label class="candidate-education-check">
                            {{ Form::checkbox('show_summary', 1, false, ['class' => 'form-check-input']) }}
                            <span>Show this degree in summary view at employer's end</span>
                        </label>
                        <div></div>
                        <div class="candidate-education-form-field">
                            {{ Form::label('major', __('messages.candidate_profile.concentration_major_group'), ['class' => 'form-label required']) }}
                            {{ Form::text('major', null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.concentration_major_group')]) }}
                        </div>
                        <div></div>
                        <div class="candidate-education-form-field candidate-education-form-field--full">
                            {{ Form::label('institute', __('messages.candidate_profile.institute_name'), ['class' => 'form-label required']) }}
                            {{ Form::text('institute', null, ['class' => 'form-control', 'required', 'id' => 'editInstitute', 'placeholder' => __('messages.candidate_profile.institute_name')]) }}
                        </div>
                        <label class="candidate-education-check candidate-education-form-field--full">
                            {{ Form::checkbox('foreign_institute', 1, false, ['class' => 'form-check-input']) }}
                            <span>This is a foreign institute</span>
                        </label>
                        <div class="candidate-education-form-field">
                            {{ Form::label('result', __('messages.candidate_profile.result'), ['class' => 'form-label required']) }}
                            {{ Form::select('result', ['Grade' => 'Grade', 'First Division/Class' => 'First Division/Class', 'Second Division/Class' => 'Second Division/Class'], null, ['class' => 'form-select', 'required', 'id' => 'editResult']) }}
                        </div>
                        <div class="candidate-education-form-field">
                            {{ Form::label('cgpa', __('messages.candidate_profile.cgpa'), ['class' => 'form-label required']) }}
                            {{ Form::text('cgpa', null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_cgpa')]) }}
                        </div>
                        <div class="candidate-education-form-field">
                            {{ Form::label('scale', __('messages.candidate_profile.scale'), ['class' => 'form-label required']) }}
                            {{ Form::text('scale', null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.scale')]) }}
                        </div>
                        <div class="candidate-education-form-field">
                            {{ Form::label('year', __('messages.candidate_profile.year_of_passing'), ['class' => 'form-label required']) }}
                            {{ Form::selectRange('year', date('Y'), 2000, null, ['class' => 'form-select', 'required', 'placeholder' => __('messages.candidate_profile.enter_year_of_passing'), 'id' => 'editYear']) }}
                        </div>
                        <div class="candidate-education-form-field candidate-education-form-field--full">
                            {{ Form::label('duration', __('messages.candidate_profile.duration_years'), ['class' => 'form-label']) }}
                            {{ Form::text('duration', null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.duration_years')]) }}
                        </div>
                        <div class="candidate-education-form-field candidate-education-form-field--full">
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
                                <h2>{{ __('messages.candidate_profile.education') }} {{ $loop->iteration }}</h2>
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
                                    <div class="candidate-education-detail">
                                        <span>{{ __('messages.candidate_profile.level_of_education') }}</span>
                                        <strong class="education-degree-level">{{ $candidateEducation->degreeLevel->name ?? '---' }}</strong>
                                    </div>
                                    <div class="candidate-education-detail">
                                        <span>{{ __('messages.candidate_profile.concentration_major_group') }}</span>
                                        <strong>---</strong>
                                    </div>
                                    <div class="candidate-education-detail">
                                        <span>{{ __('messages.candidate_profile.result') }}</span>
                                        <strong>{{ $candidateEducation->result ?: '---' }}</strong>
                                    </div>
                                    <div class="candidate-education-detail">
                                        <span>{{ __('messages.candidate_profile.scale') }}</span>
                                        <strong>---</strong>
                                    </div>
                                    <div class="candidate-education-detail">
                                        <span>{{ __('messages.candidate_profile.duration_years') }}</span>
                                        <strong>---</strong>
                                    </div>
                                    <div class="candidate-education-detail">
                                        <span>{{ __('messages.candidate_profile.achievement') }}</span>
                                        <strong>---</strong>
                                    </div>
                                </div>

                                <div class="candidate-education-detail-column">
                                    <div class="candidate-education-detail">
                                        <span>{{ __('messages.candidate_profile.exam_degree_title') }}</span>
                                        <strong>{{ $candidateEducation->degree_title ?: '---' }}</strong>
                                    </div>
                                    <div class="candidate-education-detail">
                                        <span>{{ __('messages.candidate_profile.institute_name') }}</span>
                                        <strong>{{ $candidateEducation->institute ?: '---' }}</strong>
                                    </div>
                                    <div class="candidate-education-detail">
                                        <span>{{ __('messages.candidate_profile.cgpa') }}</span>
                                        <strong>{{ is_numeric($candidateEducation->result) ? $candidateEducation->result : '---' }}</strong>
                                    </div>
                                    <div class="candidate-education-detail">
                                        <span>{{ __('messages.candidate_profile.year_of_passing') }}</span>
                                        <strong>{{ $candidateEducation->year ?: '---' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="candidate-education-panel candidate-education-panel--placeholder" id="candidateTrainingDetails">
            <div class="candidate-education-panel__header collapsed">
                <h1>{{ __('messages.candidate_profile.training') }}</h1>
                <div class="candidate-education-panel__actions">
                    <button type="button" class="candidate-education-collapse" data-bs-toggle="collapse"
                        data-bs-target="#candidateTrainingPanelBody" aria-expanded="false"
                        aria-controls="candidateTrainingPanelBody">
                        <span>{{ __('messages.candidate_profile.expand') }}</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </div>
            </div>
            <div id="candidateTrainingPanelBody" class="collapse"></div>
        </div>

        <div class="candidate-education-panel candidate-education-panel--placeholder" id="candidateProfessionalCertification">
            <div class="candidate-education-panel__header collapsed">
                <h1>{{ __('messages.candidate_profile.professional_certification') }}</h1>
                <div class="candidate-education-panel__actions">
                    <button type="button" class="candidate-education-collapse" data-bs-toggle="collapse"
                        data-bs-target="#candidateProfessionalCertificationPanelBody" aria-expanded="false"
                        aria-controls="candidateProfessionalCertificationPanelBody">
                        <span>{{ __('messages.candidate_profile.expand') }}</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </div>
            </div>
            <div id="candidateProfessionalCertificationPanelBody" class="collapse"></div>
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
            const educationBody = document.getElementById('candidateEducationPanelBody');
            const educationToggle = document.querySelector('[data-education-panel-toggle]');

            if (educationBody && educationToggle) {
                const label = educationToggle.querySelector('span');
                const icon = educationToggle.querySelector('i');
                const header = educationToggle.closest('.candidate-education-panel__header');
                const addAction = document.querySelector('[data-inline-education-add]');
                const setEducationToggleState = (isOpen) => {
                    educationToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                    label.textContent = isOpen
                        ? educationToggle.dataset.collapseLabel
                        : educationToggle.dataset.expandLabel;
                    icon.classList.toggle('fa-chevron-up', isOpen);
                    icon.classList.toggle('fa-chevron-down', !isOpen);
                    if (addAction) {
                        addAction.classList.toggle('d-none', !isOpen);
                    }
                    if (header) {
                        header.classList.toggle('collapsed', !isOpen);
                    }
                };

                educationBody.addEventListener('shown.bs.collapse', () => setEducationToggleState(true));
                educationBody.addEventListener('hidden.bs.collapse', () => setEducationToggleState(false));

                if (header) {
                    header.addEventListener('click', function (event) {
                        if (event.target.closest('button, a, input, select, textarea, label, .ql-toolbar, .ql-container')) {
                            return;
                        }

                        educationToggle.click();
                    });
                }
            }

            document.querySelectorAll('.candidate-education-panel--placeholder .collapse').forEach(function (section) {
                const toggle = document.querySelector('[data-bs-target="#' + section.id + '"]');
                if (!toggle) {
                    return;
                }

                const icon = toggle.querySelector('i');
                const header = toggle.closest('.candidate-education-panel__header');
                section.addEventListener('shown.bs.collapse', function () {
                    toggle.setAttribute('aria-expanded', 'true');
                    toggle.querySelector('span').textContent = '{{ __('messages.candidate_profile.collapse') }}';
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                    if (header) {
                        header.classList.remove('collapsed');
                    }
                });
                section.addEventListener('hidden.bs.collapse', function () {
                    toggle.setAttribute('aria-expanded', 'false');
                    toggle.querySelector('span').textContent = '{{ __('messages.candidate_profile.expand') }}';
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                    if (header) {
                        header.classList.add('collapsed');
                    }
                });
                if (header) {
                    header.addEventListener('click', function (event) {
                        if (event.target.closest('button, a')) {
                            return;
                        }

                        toggle.click();
                    });
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
