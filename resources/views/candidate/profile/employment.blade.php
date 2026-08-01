@extends('candidate.profile.index')
@section('section')
    @php
        $candidateExperiences = $data['candidateExperiences'];
        $countryOptions = is_array($countries) ? $countries : $countries->toArray();
        $fallbackCountryId = $candidateExperiences->first()->country_id ?? $user->country_id ?? array_key_first($countryOptions);
        $formatExperienceDate = function ($date) {
            return ! empty($date) ? \Carbon\Carbon::parse($date)->format('d M Y') : '';
        };
        $locationText = function ($experience) {
            return collect([
                $experience->country ?? null,
            ])->filter()->implode(', ') ?: '---';
        };
    @endphp

    <div class="mb-xl-8 candidate-employment-page">
        <div class="candidate-education-panel" id="candidateExperienceDetails">
            <div class="candidate-education-panel__header">
                <h1>Job Experience</h1>
                <div class="candidate-education-panel__actions">
                    <a href="javascript:void(0)" class="candidate-education-add" data-employment-add-trigger data-employment-add-action>
                        <i class="fa-solid fa-plus"></i>
                        <span>{{ __('messages.candidate_profile.add_experience') }}</span>
                    </a>
                    <button type="button" class="candidate-education-collapse" data-bs-toggle="collapse"
                            data-bs-target="#candidateExperiencePanelBody" aria-expanded="true"
                            aria-controls="candidateExperiencePanelBody"
                            data-collapse-label="{{ __('messages.candidate_profile.collapse') }}"
                            data-expand-label="{{ __('messages.candidate_profile.expand') }}">
                        <span>{{ __('messages.candidate_profile.collapse') }}</span>
                        <i class="fa-solid fa-chevron-up"></i>
                    </button>
                </div>
            </div>

            <div id="candidateExperiencePanelBody" class="collapse show candidate-profile-section__collapse">
                <div class="candidate-profile-section__body candidate-education-panel__body">
                    {{ Form::hidden(null, __('messages.candidate_profile.present'), ['id' => 'candidatePresentMsg']) }}
                    <div class="candidate-employment-container">
                        <div class="{{ $candidateExperiences->count() ? 'd-none' : '' }}" id="notfoundExperience">
                            <h5 class="candidate-education-empty">
                                {{ __('messages.candidate.experience_not_found') }}
                            </h5>
                        </div>

                        @foreach ($candidateExperiences as $candidateExperience)
                            @php
                                $startDate = $formatExperienceDate($candidateExperience->start_date);
                                $endDate = $candidateExperience->currently_working ? __('messages.candidate_profile.present') : $formatExperienceDate($candidateExperience->end_date);
                            @endphp
                            <div class="candidate-education candidate-education-list-item candidate-employment-list-item"
                                 data-experience-id="{{ $loop->index }}" data-id="{{ $candidateExperience->id }}">
                                <div class="candidate-employment-summary" data-employment-summary>
                                    <div class="candidate-education-item__head">
                                        <h2>{{ __('messages.candidate_profile.experience') }} {{ $loop->iteration }}</h2>
                                        <div class="candidate-education-item__actions candidate-experience-edit-delete">
                                            <a href="javascript:void(0)"
                                               class="candidate-education-action candidate-education-action--edit candidate-employment-edit-trigger"
                                               data-id="{{ $candidateExperience->id }}">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                                <span>{{ __('messages.common.edit') }}</span>
                                            </a>
                                            <a href="javascript:void(0)"
                                               class="candidate-education-action candidate-education-action--delete delete-experience"
                                               data-id="{{ $candidateExperience->id }}">
                                                <i class="fa-solid fa-trash-can"></i>
                                                <span>{{ __('messages.common.delete') }}</span>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="candidate-education-detail-grid candidate-employment-detail-grid">
                                        <div class="candidate-education-detail-column">
                                            <div class="candidate-education-detail">
                                                <span>Company Name</span>
                                                <strong>{{ $candidateExperience->company ?: '---' }}</strong>
                                            </div>
                                            <div class="candidate-education-detail">
                                                <span>Designation</span>
                                                <strong>{{ $candidateExperience->experience_title ?: '---' }}</strong>
                                            </div>
                                            <div class="candidate-education-detail">
                                                <span>Employment Period</span>
                                                <strong>{{ $startDate ?: '---' }} - {{ $endDate ?: '---' }}</strong>
                                            </div>
                                            <div class="candidate-education-detail">
                                                <span>Responsibilities</span>
                                                <strong>{{ $candidateExperience->description ? Str::limit(strip_tags($candidateExperience->description), 225, '...') : '---' }}</strong>
                                            </div>
                                            <div class="candidate-education-detail">
                                                <span>Company Location</span>
                                                <strong>{{ $locationText($candidateExperience) }}</strong>
                                            </div>
                                            <div class="candidate-education-detail">
                                                <span>Area of Expertise</span>
                                                <strong>---</strong>
                                            </div>
                                        </div>
                                        <div class="candidate-education-detail-column">
                                            <div class="candidate-education-detail">
                                                <span>Company Business</span>
                                                <strong>---</strong>
                                            </div>
                                            <div class="candidate-education-detail">
                                                <span>Department</span>
                                                <strong>---</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{ Form::open(['class' => 'candidate-employment-form candidate-employment-edit-form d-none', 'data-employment-form' => true, 'data-method' => 'PUT', 'data-action' => route('candidate.update-experience', $candidateExperience->id)]) }}
                                    <h2>{{ __('messages.candidate_profile.experience') }} {{ $loop->iteration }}</h2>
                                    {{ Form::hidden('country_id', $candidateExperience->country_id ?? $fallbackCountryId) }}
                                    {{ Form::hidden('state_id', $candidateExperience->state_id) }}
                                    {{ Form::hidden('city_id', $candidateExperience->city_id) }}
                                    @include('candidate.profile.partials.employment_experience_form_fields', [
                                        'experience' => $candidateExperience,
                                        'startDate' => $startDate,
                                        'endDate' => $candidateExperience->currently_working ? '' : $formatExperienceDate($candidateExperience->end_date),
                                        'isWorking' => $candidateExperience->currently_working,
                                    ])
                                {{ Form::close() }}
                            </div>
                        @endforeach

                        <div class="candidate-education candidate-education-list-item candidate-employment-list-item candidate-employment-add-form-wrap d-none"
                             data-employment-add-form-wrap>
                            {{ Form::open(['class' => 'candidate-employment-form', 'id' => 'candidateEmploymentAddForm', 'data-employment-form' => true, 'data-method' => 'POST', 'data-action' => route('candidate.create-experience')]) }}
                                <h2>{{ __('messages.candidate_profile.experience') }} {{ $candidateExperiences->count() + 1 }}</h2>
                                {{ Form::hidden('country_id', $fallbackCountryId) }}
                                @include('candidate.profile.partials.employment_experience_form_fields', [
                                    'experience' => null,
                                    'startDate' => '',
                                    'endDate' => '',
                                    'isWorking' => false,
                                ])
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="candidate-education-panel" id="candidateRetiredArmyEmployment">
            <div class="candidate-education-panel__header collapsed">
                <h1>Retired Army Experience</h1>
                <div class="candidate-education-panel__actions">
                    <button type="button" class="candidate-education-collapse" data-bs-toggle="collapse"
                            data-bs-target="#candidateRetiredArmyEmploymentPanelBody" aria-expanded="false"
                            aria-controls="candidateRetiredArmyEmploymentPanelBody"
                            data-collapse-label="{{ __('messages.candidate_profile.collapse') }}"
                            data-expand-label="{{ __('messages.candidate_profile.expand') }}">
                        <span>{{ __('messages.candidate_profile.expand') }}</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </div>
            </div>
            <div id="candidateRetiredArmyEmploymentPanelBody" class="collapse candidate-profile-section__collapse">
                <div class="candidate-profile-section__body candidate-education-panel__body">
                    <div class="candidate-retired-army-summary" data-retired-army-summary>
                        <div class="candidate-education-item__head">
                            <h2>Information</h2>
                            <div class="candidate-education-item__actions">
                                <a href="javascript:void(0)" class="candidate-education-action candidate-education-action--edit"
                                   data-retired-army-edit>
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <span>{{ __('messages.common.edit') }}</span>
                                </a>
                                <a href="javascript:void(0)" class="candidate-education-action candidate-education-action--delete"
                                   data-retired-army-delete>
                                    <i class="fa-solid fa-trash-can"></i>
                                    <span>{{ __('messages.common.delete') }}</span>
                                </a>
                            </div>
                        </div>
                        <div class="candidate-education-detail-grid candidate-retired-army-detail-grid">
                            @foreach ([1, 2] as $retiredArmyItem)
                                <div class="candidate-education-detail-column">
                                    <div class="candidate-education-detail">
                                        <span>BA No</span>
                                        <strong data-retired-army-value="baNo">-</strong>
                                    </div>
                                    <div class="candidate-education-detail">
                                        <span>Type</span>
                                        <strong data-retired-army-value="type">{{ $retiredArmyItem === 1 ? '' : '---' }}</strong>
                                    </div>
                                    <div class="candidate-education-detail">
                                        <span>Trade</span>
                                        <strong data-retired-army-value="trade">---</strong>
                                    </div>
                                    <div class="candidate-education-detail">
                                        <span>Date of Commission</span>
                                        <strong data-retired-army-value="commissionDate">{{ $retiredArmyItem === 1 ? '' : '-' }}</strong>
                                    </div>
                                </div>
                                <div class="candidate-education-detail-column">
                                    <div class="candidate-education-detail">
                                        <span>Ranks</span>
                                        <strong data-retired-army-value="rank"></strong>
                                    </div>
                                    <div class="candidate-education-detail">
                                        <span>Arms</span>
                                        <strong data-retired-army-value="arms"></strong>
                                    </div>
                                    <div class="candidate-education-detail">
                                        <span>Course</span>
                                        <strong data-retired-army-value="course">---</strong>
                                    </div>
                                    <div class="candidate-education-detail">
                                        <span>Date of Retirement</span>
                                        <strong data-retired-army-value="retirementDate"></strong>
                                    </div>
                                </div>
	                            @endforeach
	                        </div>
                            <button type="button" class="candidate-retired-army-add" data-retired-army-edit>
                                <i class="fa-solid fa-plus"></i>
                                <span>Add Experience at Bangladesh Army</span>
                            </button>
	                    </div>

                    <div class="candidate-retired-army-form d-none" data-retired-army-form>
                        <h2>Information</h2>
                        <div class="candidate-retired-army-grid">
                            <div class="candidate-education-form-field">
                                {{ Form::label('ba_no_prefix', 'BA No', ['class' => 'form-label required']) }}
                                <div class="candidate-retired-army-ba-group">
                                    {{ Form::select('ba_no_prefix', ['' => 'Select your BA no', 'BA' => 'BA', 'BSS' => 'BSS', 'JC' => 'JC'], null, ['class' => 'form-select']) }}
                                    {{ Form::text('ba_no', null, ['class' => 'form-control', 'placeholder' => 'Enter your Ba No']) }}
                                </div>
                            </div>
                            <div class="candidate-education-form-field">
                                {{ Form::label('army_rank', 'Ranks', ['class' => 'form-label required']) }}
                                {{ Form::select('army_rank', ['' => 'Enter your Rank', 'Captain' => 'Captain', 'Major' => 'Major', 'Colonel' => 'Colonel'], null, ['class' => 'form-select']) }}
                            </div>
                            <div class="candidate-education-form-field">
                                {{ Form::label('army_type', 'Type', ['class' => 'form-label required']) }}
                                {{ Form::select('army_type', ['' => 'Enter your Type', 'Commissioned' => 'Commissioned', 'Non Commissioned' => 'Non Commissioned'], null, ['class' => 'form-select']) }}
                            </div>
                            <div class="candidate-education-form-field">
                                {{ Form::label('army_arms', 'Arms', ['class' => 'form-label required']) }}
                                {{ Form::select('army_arms', ['' => 'Enter your Type', 'Infantry' => 'Infantry', 'Artillery' => 'Artillery', 'Signals' => 'Signals'], null, ['class' => 'form-select']) }}
                            </div>
                            <div class="candidate-education-form-field">
                                {{ Form::label('army_trade', 'Trade', ['class' => 'form-label']) }}
                                {{ Form::text('army_trade', null, ['class' => 'form-control', 'placeholder' => 'Enter your Type']) }}
                            </div>
                            <div class="candidate-education-form-field">
                                {{ Form::label('army_course', 'Course', ['class' => 'form-label']) }}
                                {{ Form::text('army_course', null, ['class' => 'form-control', 'placeholder' => 'Enter your Type']) }}
                            </div>
                            <div class="candidate-education-form-field">
                                {{ Form::label('date_of_commission', 'Date of Commission', ['class' => 'form-label required']) }}
                                <div class="candidate-employment-date-input">
                                    <i class="fa-regular fa-calendar candidate-employment-date-icon"></i>
                                    {{ Form::text('date_of_commission', null, ['class' => 'form-control', 'placeholder' => 'mm/dd/yy', 'autocomplete' => 'off', 'data-employment-date' => 'start']) }}
                                </div>
                            </div>
                            <div class="candidate-education-form-field">
                                {{ Form::label('date_of_retirement', 'Date of Retirement', ['class' => 'form-label required']) }}
                                <div class="candidate-employment-date-input">
                                    <i class="fa-regular fa-calendar candidate-employment-date-icon"></i>
                                    {{ Form::text('date_of_retirement', null, ['class' => 'form-control', 'placeholder' => 'mm/dd/yy', 'autocomplete' => 'off', 'data-employment-date' => 'end']) }}
                                </div>
                            </div>
                        </div>
                        <p class="candidate-retired-army-note">
                            <strong>Note:</strong> Please, write your Military Service Information in Employment History.
                        </p>
                        <div class="candidate-profile-section-actions candidate-employment-form-actions">
                            <button type="button" class="btn btn-primary" data-retired-army-save>Save</button>
                            <button type="button" class="btn btn-secondary" data-retired-army-close>Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const employmentSectionLinks = document.querySelectorAll('[data-employment-section-link]');
            const employmentSectionBodies = document.querySelectorAll('.candidate-employment-page .candidate-profile-section__collapse');
            const addTrigger = document.querySelector('[data-employment-add-trigger]');
            const addFormWrap = document.querySelector('[data-employment-add-form-wrap]');
            const employmentQuillEditors = [];

            const initEmploymentQuillEditors = function () {
                if (typeof Quill === 'undefined') {
                    document.querySelectorAll('[data-employment-quill-input]').forEach(function (input) {
                        input.classList.remove('d-none');
                    });
                    return;
                }

                document.querySelectorAll('[data-employment-quill-editor]').forEach(function (element) {
                    if (element.dataset.quillReady === 'true') {
                        return;
                    }

                    const wrapper = element.closest('.candidate-education-editor');
                    const input = wrapper ? wrapper.querySelector('[data-employment-quill-input]') : null;
                    if (!input) {
                        return;
                    }

                    const quill = new Quill(element, {
                        modules: {
                            toolbar: [
                                ['bold', 'italic'],
                                [{ list: 'bullet' }],
                            ],
                        },
                        placeholder: element.dataset.placeholder || '',
                        theme: 'snow',
                    });

                    if (input.value) {
                        quill.root.innerHTML = input.value;
                    }

                    quill.on('text-change', function () {
                        input.value = quill.getText().trim().length ? quill.root.innerHTML : '';
                    });

                    element.dataset.quillReady = 'true';
                    employmentQuillEditors.push({ quill, input, form: element.closest('[data-employment-form]') });
                });
            };

            const syncEmploymentQuillEditors = function (form) {
                employmentQuillEditors.forEach(function (editor) {
                    if (!form || editor.form === form) {
                        editor.input.value = editor.quill.getText().trim().length ? editor.quill.root.innerHTML : '';
                    }
                });
            };

            const initEmploymentDatePickers = function () {
                if (typeof flatpickr === 'undefined') {
                    return;
                }

                document.querySelectorAll('[data-employment-form], [data-retired-army-form]').forEach(function (form) {
                    const startInput = form.querySelector('[data-employment-date="start"]');
                    const endInput = form.querySelector('[data-employment-date="end"]');

                    if (!startInput || !endInput || startInput.dataset.flatpickrReady === 'true') {
                        return;
                    }

                    const endPicker = flatpickr(endInput, {
                        allowInput: true,
                        dateFormat: 'd M Y',
                        locale: typeof getLoggedInUserLang !== 'undefined' ? getLoggedInUserLang : 'default',
                    });

                    flatpickr(startInput, {
                        allowInput: true,
                        dateFormat: 'd M Y',
                        locale: typeof getLoggedInUserLang !== 'undefined' ? getLoggedInUserLang : 'default',
                        onChange: function (selectedDates, dateStr) {
                            endPicker.set('minDate', dateStr || null);
                            if (endInput.value && selectedDates.length && new Date(endInput.value) < selectedDates[0]) {
                                endPicker.clear();
                            }
                        },
                    });

                    if (startInput.value) {
                        endPicker.set('minDate', startInput.value);
                    }

                    startInput.dataset.flatpickrReady = 'true';
                    endInput.dataset.flatpickrReady = 'true';
                });
            };

            const setActiveEmploymentSection = function (panelId) {
                employmentSectionLinks.forEach(function (link) {
                    link.classList.toggle('active', link.dataset.employmentSectionLink === panelId);
                });
            };

            const closeOtherEmploymentSections = function (activeSection) {
                if (typeof bootstrap === 'undefined') {
                    return;
                }

                employmentSectionBodies.forEach(function (section) {
                    if (section !== activeSection) {
                        bootstrap.Collapse.getOrCreateInstance(section, { toggle: false }).hide();
                    }
                });
            };

            const hideInlineForms = function () {
                document.querySelectorAll('.candidate-employment-edit-form').forEach(function (form) {
                    form.classList.add('d-none');
                    const item = form.closest('.candidate-employment-list-item');
                    const summary = item ? item.querySelector('[data-employment-summary]') : null;
                    if (summary) {
                        summary.classList.remove('d-none');
                    }
                });

                if (addFormWrap) {
                    addFormWrap.classList.add('d-none');
                }
            };

            employmentSectionBodies.forEach(function (section) {
                const toggle = document.querySelector('[data-bs-target="#' + section.id + '"]');
                if (!toggle) {
                    return;
                }

                const label = toggle.querySelector('span');
                const icon = toggle.querySelector('i');
                const header = toggle.closest('.candidate-education-panel__header');
                const panel = section.closest('.candidate-education-panel');
                const addActions = panel ? panel.querySelectorAll('.candidate-education-panel__header [data-employment-add-action]') : [];

                const setPanelToggleState = function (isOpen) {
                    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                    if (label) {
                        label.textContent = isOpen
                            ? (toggle.dataset.collapseLabel || '{{ __('messages.candidate_profile.collapse') }}')
                            : (toggle.dataset.expandLabel || '{{ __('messages.candidate_profile.expand') }}');
                    }
                    if (icon) {
                        icon.classList.toggle('fa-chevron-up', isOpen);
                        icon.classList.toggle('fa-chevron-down', !isOpen);
                    }
                    addActions.forEach(function (addAction) {
                        addAction.classList.toggle('d-none', !isOpen);
                    });
                    if (header) {
                        header.classList.toggle('collapsed', !isOpen);
                    }
                };

                section.addEventListener('shown.bs.collapse', function () {
                    closeOtherEmploymentSections(section);
                    setPanelToggleState(true);
                    if (panel) {
                        setActiveEmploymentSection(panel.id);
                    }
                });
                section.addEventListener('hidden.bs.collapse', function () {
                    setPanelToggleState(false);
                });

                if (header) {
                    header.addEventListener('click', function (event) {
                        if (event.target.closest('button, a, input, select, textarea, label, .ql-toolbar, .ql-container')) {
                            return;
                        }

                        toggle.click();
                    });
                }

                setPanelToggleState(section.classList.contains('show'));
            });

            employmentSectionLinks.forEach(function (link) {
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    const panel = document.getElementById(link.dataset.employmentSectionLink);
                    const section = panel ? panel.querySelector('.candidate-profile-section__collapse') : null;

                    if (!panel || !section || typeof bootstrap === 'undefined') {
                        return;
                    }

                    closeOtherEmploymentSections(section);
                    bootstrap.Collapse.getOrCreateInstance(section, { toggle: false }).show();
                    setActiveEmploymentSection(panel.id);
                    panel.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start',
                    });
                });
            });

            if (addTrigger && addFormWrap) {
                addTrigger.addEventListener('click', function (event) {
                    event.preventDefault();
                    hideInlineForms();
                    addFormWrap.classList.remove('d-none');
                    addFormWrap.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            }

            document.querySelectorAll('.candidate-employment-edit-trigger').forEach(function (trigger) {
                trigger.addEventListener('click', function (event) {
                    event.preventDefault();
                    hideInlineForms();
                    const item = trigger.closest('.candidate-employment-list-item');
                    const summary = item ? item.querySelector('[data-employment-summary]') : null;
                    const form = item ? item.querySelector('.candidate-employment-edit-form') : null;
                    if (summary && form) {
                        summary.classList.add('d-none');
                        form.classList.remove('d-none');
                        item.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            });

            document.querySelectorAll('[data-employment-form-close]').forEach(function (button) {
                button.addEventListener('click', function () {
                    hideInlineForms();
                });
            });

            const retiredArmySummary = document.querySelector('[data-retired-army-summary]');
            const retiredArmyForm = document.querySelector('[data-retired-army-form]');
            const showRetiredArmySummary = function () {
                if (retiredArmySummary) {
                    retiredArmySummary.classList.remove('d-none');
                }
                if (retiredArmyForm) {
                    retiredArmyForm.classList.add('d-none');
                }
            };
            const showRetiredArmyForm = function () {
                if (retiredArmySummary) {
                    retiredArmySummary.classList.add('d-none');
                }
                if (retiredArmyForm) {
                    retiredArmyForm.classList.remove('d-none');
                }
            };
            const setRetiredArmyValue = function (name, value) {
                document.querySelectorAll('[data-retired-army-value="' + name + '"]').forEach(function (element) {
                    element.textContent = value || (name === 'baNo' ? '-' : '---');
                });
            };

            document.querySelectorAll('[data-retired-army-edit]').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    showRetiredArmyForm();
                });
            });

            document.querySelectorAll('[data-retired-army-save]').forEach(function (button) {
                button.addEventListener('click', function () {
                    if (!retiredArmyForm) {
                        return;
                    }

                    const baPrefix = retiredArmyForm.querySelector('[name="ba_no_prefix"]');
                    const baNo = retiredArmyForm.querySelector('[name="ba_no"]');
                    const getValue = function (selector) {
                        const field = retiredArmyForm.querySelector(selector);
                        return field ? field.value.trim() : '';
                    };

                    setRetiredArmyValue('baNo', [baPrefix ? baPrefix.value : '', baNo ? baNo.value : ''].filter(Boolean).join(' '));
                    setRetiredArmyValue('rank', getValue('[name="army_rank"]'));
                    setRetiredArmyValue('type', getValue('[name="army_type"]'));
                    setRetiredArmyValue('arms', getValue('[name="army_arms"]'));
                    setRetiredArmyValue('trade', getValue('[name="army_trade"]'));
                    setRetiredArmyValue('course', getValue('[name="army_course"]'));
                    setRetiredArmyValue('commissionDate', getValue('[name="date_of_commission"]'));
                    setRetiredArmyValue('retirementDate', getValue('[name="date_of_retirement"]'));
                    showRetiredArmySummary();
                });
            });

            document.querySelectorAll('[data-retired-army-delete]').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    ['baNo', 'rank', 'type', 'arms', 'trade', 'course', 'commissionDate', 'retirementDate'].forEach(function (name) {
                        setRetiredArmyValue(name, '');
                    });
                });
            });

            document.querySelectorAll('[data-retired-army-close]').forEach(function (button) {
                button.addEventListener('click', function () {
                    showRetiredArmySummary();
                });
            });

            document.querySelectorAll('[data-employment-working]').forEach(function (checkbox) {
                const form = checkbox.closest('[data-employment-form]');
                const endInput = form ? form.querySelector('[data-employment-end-date]') : null;
                const syncEndInput = function () {
                    if (!endInput) {
                        return;
                    }
                    endInput.disabled = checkbox.checked;
                    endInput.placeholder = checkbox.checked ? 'Continuing...' : 'Employment Period End Date';
                    if (checkbox.checked) {
                        endInput.value = '';
                    }
                };
                checkbox.addEventListener('change', syncEndInput);
                syncEndInput();
            });

            document.querySelectorAll('[data-employment-expertise-add]').forEach(function (button) {
                button.addEventListener('click', function () {
                    const field = button.closest('.candidate-education-form-field');
                    const firstRow = field ? field.querySelector('[data-employment-expertise-row]') : null;
                    if (!field || !firstRow) {
                        return;
                    }

                    const rowCount = field.querySelectorAll('[data-employment-expertise-row]').length;
                    if (rowCount >= 10) {
                        return;
                    }

                    const newRow = firstRow.cloneNode(true);
                    newRow.querySelectorAll('input').forEach(function (input) {
                        input.value = '';
                    });
                    field.insertBefore(newRow, button);
                });
            });

            document.addEventListener('input', function (event) {
                const durationInput = event.target.closest('[data-employment-expertise-duration]');
                if (!durationInput) {
                    return;
                }

                if (Number(durationInput.value) > 12) {
                    durationInput.value = 12;
                    if (typeof displayErrorMessage === 'function') {
                        displayErrorMessage('Duration cannot be more than 12 months.');
                    }
                }
            });

            document.addEventListener('click', function (event) {
                const removeButton = event.target.closest('[data-employment-expertise-remove]');
                if (!removeButton) {
                    return;
                }

                const field = removeButton.closest('.candidate-education-form-field');
                const rows = field ? field.querySelectorAll('[data-employment-expertise-row]') : [];
                const row = removeButton.closest('[data-employment-expertise-row]');
                if (rows.length > 1 && row) {
                    row.remove();
                } else if (row) {
                    row.querySelectorAll('input').forEach(function (input) {
                        input.value = '';
                    });
                }
            });

            document.querySelectorAll('[data-employment-form]').forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();

                    const invalidDuration = Array.from(form.querySelectorAll('[data-employment-expertise-duration]')).
                        find(function (input) {
                            return input.value !== '' && Number(input.value) > 12;
                        });
                    if (invalidDuration) {
                        invalidDuration.focus();
                        if (typeof displayErrorMessage === 'function') {
                            displayErrorMessage('Duration cannot be more than 12 months.');
                        } else {
                            alert('Duration cannot be more than 12 months.');
                        }
                        return;
                    }

                    syncEmploymentQuillEditors(form);
                    const formData = new FormData(form);
                    const method = (form.dataset.method || 'POST').toUpperCase();
                    const token = document.querySelector('meta[name="csrf-token"]');
                    ['company_business', 'department', 'company_location', 'area_of_expertise[]', 'expertise_duration[]'].forEach(function (field) {
                        formData.delete(field);
                    });
                    if (method !== 'POST') {
                        formData.append('_method', method);
                    }

                    fetch(form.dataset.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token ? token.content : '',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: formData,
                    }).then(function (response) {
                        if (!response.ok) {
                            return response.json().then(function (data) {
                                throw new Error(data.message || 'Unable to save experience.');
                            });
                        }
                        return response.json();
                    }).then(function () {
                        window.location.reload();
                    }).catch(function (error) {
                        if (typeof displayErrorMessage === 'function') {
                            displayErrorMessage(error.message);
                        } else {
                            alert(error.message);
                        }
                    });
                });
            });

            initEmploymentDatePickers();
            initEmploymentQuillEditors();
        });
    </script>
@endpush
