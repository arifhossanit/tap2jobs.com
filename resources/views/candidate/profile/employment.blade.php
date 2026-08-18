@extends('candidate.profile.index')
@section('section')
    @php
        $candidateExperiences = $data['candidateExperiences'];
        $countryOptions = is_array($countries) ? $countries : $countries->toArray();
        $fallbackCountryId = $candidateExperiences->first()->country_id ?? $user->country_id ?? array_key_first($countryOptions);
        $formatExperienceDate = function ($date) {
            return ! empty($date) ? \Carbon\Carbon::parse($date)->format('d M Y') : '';
        };
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
        $retiredArmyEmployment = $data['candidateRetiredArmyEmployment'] ?? null;
        $hasRetiredArmyEmployment = ! empty($retiredArmyEmployment);
        $retiredArmyBaNo = $hasRetiredArmyEmployment
            ? collect([$retiredArmyEmployment->ba_no_prefix, $retiredArmyEmployment->ba_no])->filter()->implode(' ')
            : '';
        $locationText = function ($experience) {
            return collect([
                $experience->company_location ?? null,
                $experience->country ?? null,
            ])->filter()->implode(', ') ?: '---';
        };
        $expertiseText = function ($experience) {
            if (! $experience->relationLoaded('expertises') || $experience->expertises->isEmpty()) {
                return '---';
            }

            return $experience->expertises->map(function ($expertise) {
                $duration = filled($expertise->duration_months) ? ' ('.$expertise->duration_months.' month(s))' : '';

                return $expertise->name.$duration;
            })->implode(', ');
        };
    @endphp

    <div class="mb-xl-8 candidate-employment-page candidate-profile-accordion" id="candidateEmploymentAccordion">
        <div class="candidate-education-panel candidate-profile-section" id="candidateExperienceDetails">
            <div class="candidate-profile-section__header">
                <span>{{ __('messages.candidate_profile.job_experience') }}</span>
                <span class="candidate-profile-section__header-actions">
                    <a href="javascript:void(0)" class="candidate-education-add" data-employment-add-trigger data-employment-add-action>
                        <i class="fa-solid fa-plus"></i>
                        <span>{{ __('messages.candidate_profile.add_experience') }}</span>
                    </a>
                    <button type="button" class="candidate-profile-section__toggle" data-bs-toggle="collapse"
                            data-bs-target="#candidateExperiencePanelBody" aria-expanded="true"
                            aria-controls="candidateExperiencePanelBody"
                            data-collapse-label="{{ __('messages.candidate_profile.collapse') }}"
                            data-expand-label="{{ __('messages.candidate_profile.expand') }}">
                        <span>{{ __('messages.candidate_profile.collapse') }}</span>
                        <i class="fa-solid fa-chevron-up"></i>
                    </button>
                </span>
            </div>

            <div id="candidateExperiencePanelBody" class="collapse show candidate-profile-section__collapse"
                 data-bs-parent="#candidateEmploymentAccordion">
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
                                        <h2>{{ __('messages.candidate_profile.experience') }} {{ $candidateProfileNumber($loop->iteration) }}</h2>
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
                                                <span>{{ __('messages.candidate_profile.company_name') }}</span>
                                                <strong>{{ $candidateExperience->company ?: '---' }}</strong>
                                            </div>
                                            <div class="candidate-education-detail">
                                                <span>{{ __('messages.candidate_profile.designation') }}</span>
                                                <strong>{{ $candidateExperience->experience_title ?: '---' }}</strong>
                                            </div>
                                            <div class="candidate-education-detail">
                                                <span>{{ __('messages.candidate_profile.employment_period') }}</span>
                                                <strong>{{ $startDate ?: '---' }} - {{ $endDate ?: '---' }}</strong>
                                            </div>
                                            <div class="candidate-education-detail">
                                                <span>{{ __('messages.candidate_profile.responsibility') }}</span>
                                                <strong>{{ $candidateExperience->description ? Str::limit(strip_tags($candidateExperience->description), 225, '...') : '---' }}</strong>
                                            </div>
                                            <div class="candidate-education-detail">
                                                <span>{{ __('messages.candidate_profile.company_location') }}</span>
                                                <strong>{{ $locationText($candidateExperience) }}</strong>
                                            </div>
                                            <div class="candidate-education-detail">
                                                <span>{{ __('messages.candidate_profile.area_of_expertise') }}</span>
                                                <strong>{{ $expertiseText($candidateExperience) }}</strong>
                                            </div>
                                        </div>
                                        <div class="candidate-education-detail-column">
                                            <div class="candidate-education-detail">
                                                <span>{{ __('messages.candidate_profile.company_business') }}</span>
                                                <strong>{{ $candidateExperience->company_business ?: '---' }}</strong>
                                            </div>
                                            <div class="candidate-education-detail">
                                                <span>{{ __('messages.candidate_profile.department') }}</span>
                                                <strong>{{ $candidateExperience->department ?: '---' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{ Form::open(['class' => 'candidate-employment-form candidate-employment-edit-form d-none', 'data-employment-form' => true, 'data-method' => 'PUT', 'data-action' => route('candidate.update-experience', $candidateExperience->id)]) }}
                                    <h2>{{ __('messages.candidate_profile.experience') }} {{ $candidateProfileNumber($loop->iteration) }}</h2>
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
                                <h2>{{ __('messages.candidate_profile.experience') }} {{ $candidateProfileNumber($candidateExperiences->count() + 1) }}</h2>
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

        <div class="candidate-education-panel candidate-profile-section" id="candidateRetiredArmyEmployment">
            <div class="candidate-profile-section__header collapsed">
                <span>{{ __('messages.candidate_profile.army_experience') }}</span>
                <span class="candidate-profile-section__header-actions">
                    <a class="candidate-education-add {{ $hasRetiredArmyEmployment ? 'd-none' : '' }}" href="javascript:void(0)" data-retired-army-add-trigger data-retired-army-add-action>
                        <i class="fa-solid fa-plus"></i> {{ __('messages.candidate_profile.add_employment_history') }}
                    </a>
                    <button type="button" class="candidate-profile-section__toggle" data-bs-toggle="collapse"
                            data-bs-target="#candidateRetiredArmyEmploymentPanelBody" aria-expanded="false"
                            aria-controls="candidateRetiredArmyEmploymentPanelBody"
                            data-collapse-label="{{ __('messages.candidate_profile.collapse') }}"
                            data-expand-label="{{ __('messages.candidate_profile.expand') }}">
                        <span>{{ __('messages.candidate_profile.expand') }}</span>
                        <i class="fa-solid fa-chevron-up"></i>
                    </button>
                </span>
            </div>
            <div id="candidateRetiredArmyEmploymentPanelBody" class="collapse candidate-profile-section__collapse"
                 data-bs-parent="#candidateEmploymentAccordion">
                <div class="candidate-profile-section__body candidate-education-panel__body">
                    <p class="candidate-skill-empty candidate-retired-army-empty {{ $hasRetiredArmyEmployment ? 'd-none' : '' }}" data-retired-army-empty>---</p>
                    <div class="candidate-retired-army-summary {{ $hasRetiredArmyEmployment ? '' : 'd-none' }}" data-retired-army-summary>
                        <div class="candidate-education-item__head">
                            <h2>{{ __('messages.candidate_profile.information') }}</h2>
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
                            <div class="candidate-education-detail-column">
                                <div class="candidate-education-detail">
                                    <span>{{ __('messages.candidate_profile.ba_no') }}</span>
                                    <strong data-retired-army-value="baNo">{{ $retiredArmyBaNo ?: '---' }}</strong>
                                </div>
                                <div class="candidate-education-detail">
                                    <span>{{ __('messages.candidate_profile.type') }}</span>
                                    <strong data-retired-army-value="type">{{ $retiredArmyEmployment->type ?? '---' }}</strong>
                                </div>
                                <div class="candidate-education-detail">
                                    <span>{{ __('messages.candidate_profile.trade') }}</span>
                                    <strong data-retired-army-value="trade">{{ $retiredArmyEmployment->trade ?? '---' }}</strong>
                                </div>
                                <div class="candidate-education-detail">
                                    <span>{{ __('messages.candidate_profile.date_of_commission') }}</span>
                                    <strong data-retired-army-value="commissionDate">{{ $hasRetiredArmyEmployment ? $formatExperienceDate($retiredArmyEmployment->date_of_commission) : '---' }}</strong>
                                </div>
                            </div>
                            <div class="candidate-education-detail-column">
                                <div class="candidate-education-detail">
                                    <span>{{ __('messages.candidate_profile.ranks') }}</span>
                                    <strong data-retired-army-value="rank">{{ $retiredArmyEmployment->rank ?? '---' }}</strong>
                                </div>
                                <div class="candidate-education-detail">
                                    <span>{{ __('messages.candidate_profile.arms') }}</span>
                                    <strong data-retired-army-value="arms">{{ $retiredArmyEmployment->arms ?? '---' }}</strong>
                                </div>
                                <div class="candidate-education-detail">
                                    <span>{{ __('messages.candidate_profile.course') }}</span>
                                    <strong data-retired-army-value="course">{{ $retiredArmyEmployment->course ?? '---' }}</strong>
                                </div>
                                <div class="candidate-education-detail">
                                    <span>{{ __('messages.candidate_profile.date_of_retirement') }}</span>
                                    <strong data-retired-army-value="retirementDate">{{ $hasRetiredArmyEmployment ? $formatExperienceDate($retiredArmyEmployment->date_of_retirement) : '---' }}</strong>
                                </div>
                            </div>
	                        </div>
                            <button type="button" class="candidate-retired-army-add" data-retired-army-edit>
                                <i class="fa-solid fa-plus"></i>
                                <span>{{ __('messages.candidate_profile.add_experience_at_bangladesh_army') }}</span>
                            </button>
	                    </div>

                    <form class="candidate-retired-army-form {{ $hasRetiredArmyEmployment ? 'd-none' : '' }}"
                          data-retired-army-form
                          data-action="{{ route('candidate.retired-army-employment.update') }}"
                          data-delete-action="{{ route('candidate.retired-army-employment.destroy') }}">
                        <h2>{{ __('messages.candidate_profile.information') }}</h2>
                        <div class="candidate-retired-army-grid">
                            <div class="candidate-education-form-field">
                                {{ Form::label('ba_no_prefix', __('messages.candidate_profile.ba_no'), ['class' => 'form-label required']) }}
                                <div class="candidate-retired-army-ba-group">
                                    {{ Form::select('ba_no_prefix', ['' => __('messages.candidate_profile.select_ba_no'), 'BA' => 'BA', 'BSS' => 'BSS', 'JC' => 'JC'], $retiredArmyEmployment->ba_no_prefix ?? null, ['class' => 'form-select']) }}
                                    {{ Form::text('ba_no', $retiredArmyEmployment->ba_no ?? null, ['class' => 'form-control', 'required', 'placeholder' => __('messages.candidate_profile.enter_ba_no')]) }}
                                </div>
                            </div>
                            <div class="candidate-education-form-field">
                                {{ Form::label('rank', __('messages.candidate_profile.ranks'), ['class' => 'form-label required']) }}
                                {{ Form::select('rank', ['' => __('messages.candidate_profile.enter_rank'), 'Captain' => 'Captain', 'Major' => 'Major', 'Colonel' => 'Colonel'], $retiredArmyEmployment->rank ?? null, ['class' => 'form-select', 'required']) }}
                            </div>
                            <div class="candidate-education-form-field">
                                {{ Form::label('type', __('messages.candidate_profile.type'), ['class' => 'form-label required']) }}
                                {{ Form::select('type', ['' => __('messages.candidate_profile.enter_type'), 'Commissioned' => 'Commissioned', 'Non Commissioned' => 'Non Commissioned'], $retiredArmyEmployment->type ?? null, ['class' => 'form-select', 'required']) }}
                            </div>
                            <div class="candidate-education-form-field">
                                {{ Form::label('arms', __('messages.candidate_profile.arms'), ['class' => 'form-label required']) }}
                                {{ Form::select('arms', ['' => __('messages.candidate_profile.enter_type'), 'Infantry' => 'Infantry', 'Artillery' => 'Artillery', 'Signals' => 'Signals'], $retiredArmyEmployment->arms ?? null, ['class' => 'form-select', 'required']) }}
                            </div>
                            <div class="candidate-education-form-field">
                                {{ Form::label('trade', __('messages.candidate_profile.trade'), ['class' => 'form-label']) }}
                                {{ Form::text('trade', $retiredArmyEmployment->trade ?? null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_type')]) }}
                            </div>
                            <div class="candidate-education-form-field">
                                {{ Form::label('course', __('messages.candidate_profile.course'), ['class' => 'form-label']) }}
                                {{ Form::text('course', $retiredArmyEmployment->course ?? null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_type')]) }}
                            </div>
                            <div class="candidate-education-form-field">
                                {{ Form::label('date_of_commission', __('messages.candidate_profile.date_of_commission'), ['class' => 'form-label required']) }}
                                <div class="candidate-employment-date-input">
                                    <i class="fa-regular fa-calendar candidate-employment-date-icon"></i>
                                    {{ Form::text('date_of_commission', $hasRetiredArmyEmployment ? $formatExperienceDate($retiredArmyEmployment->date_of_commission) : null, ['class' => 'form-control', 'required', 'placeholder' => 'mm/dd/yy', 'autocomplete' => 'off', 'data-employment-date' => 'start']) }}
                                </div>
                            </div>
                            <div class="candidate-education-form-field">
                                {{ Form::label('date_of_retirement', __('messages.candidate_profile.date_of_retirement'), ['class' => 'form-label required']) }}
                                <div class="candidate-employment-date-input">
                                    <i class="fa-regular fa-calendar candidate-employment-date-icon"></i>
                                    {{ Form::text('date_of_retirement', $hasRetiredArmyEmployment ? $formatExperienceDate($retiredArmyEmployment->date_of_retirement) : null, ['class' => 'form-control', 'required', 'placeholder' => 'mm/dd/yy', 'autocomplete' => 'off', 'data-employment-date' => 'end']) }}
                                </div>
                            </div>
                        </div>
                        <p class="candidate-retired-army-note">
                            <strong>{{ __('messages.candidate_profile.note') }}:</strong> {{ __('messages.candidate_profile.military_service_note') }}
                        </p>
                        <div class="candidate-profile-section-actions candidate-employment-form-actions">
                            <button type="submit" class="btn btn-primary" data-retired-army-save>{{ __('messages.common.save') }}</button>
                            <button type="button" class="btn btn-secondary" data-retired-army-close>{{ __('messages.common.close') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
            let hasRetiredArmyEmployment = {{ $hasRetiredArmyEmployment ? 'true' : 'false' }};
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
            const formatRetiredArmyDate = function (value) {
                if (!value) {
                    return '';
                }

                const match = String(value).match(/^(\d{4})-(\d{2})-(\d{2})/);
                if (match) {
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    return match[3] + ' ' + months[Number(match[2]) - 1] + ' ' + match[1];
                }

                const date = new Date(value);
                if (Number.isNaN(date.getTime())) {
                    return value;
                }

                return date.toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                }).replace(/ /g, ' ');
            };
            const setRetiredArmyFormValue = function (name, value) {
                if (!retiredArmyForm) {
                    return;
                }

                const field = retiredArmyForm.querySelector('[name="' + name + '"]');
                if (field) {
                    field.value = value || '';
                }
            };
            const applyRetiredArmyEmployment = function (employment) {
                const baNo = [employment.ba_no_prefix, employment.ba_no].filter(Boolean).join(' ');

                setRetiredArmyValue('baNo', baNo);
                setRetiredArmyValue('rank', employment.rank);
                setRetiredArmyValue('type', employment.type);
                setRetiredArmyValue('arms', employment.arms);
                setRetiredArmyValue('trade', employment.trade);
                setRetiredArmyValue('course', employment.course);
                setRetiredArmyValue('commissionDate', formatRetiredArmyDate(employment.date_of_commission));
                setRetiredArmyValue('retirementDate', formatRetiredArmyDate(employment.date_of_retirement));

                setRetiredArmyFormValue('ba_no_prefix', employment.ba_no_prefix);
                setRetiredArmyFormValue('ba_no', employment.ba_no);
                setRetiredArmyFormValue('rank', employment.rank);
                setRetiredArmyFormValue('type', employment.type);
                setRetiredArmyFormValue('arms', employment.arms);
                setRetiredArmyFormValue('trade', employment.trade);
                setRetiredArmyFormValue('course', employment.course);
                setRetiredArmyFormValue('date_of_commission', formatRetiredArmyDate(employment.date_of_commission));
                setRetiredArmyFormValue('date_of_retirement', formatRetiredArmyDate(employment.date_of_retirement));
            };
            const employmentFormData = function (form) {
                const formData = new FormData(form);

                form.querySelectorAll('[data-employment-date]').forEach(function (input) {
                    if (!input.name || !input._flatpickr || !input._flatpickr.selectedDates.length) {
                        return;
                    }

                    const selectedDate = input._flatpickr.selectedDates[0];
                    const year = selectedDate.getFullYear();
                    const month = String(selectedDate.getMonth() + 1).padStart(2, '0');
                    const day = String(selectedDate.getDate()).padStart(2, '0');
                    formData.set(input.name, year + '-' + month + '-' + day);
                });

                return formData;
            };
            const resolveErrorMessage = function (data, fallback) {
                if (data && data.errors) {
                    const firstError = Object.values(data.errors)[0];
                    if (Array.isArray(firstError) && firstError.length) {
                        return firstError[0];
                    }
                }

                return (data && data.message) || fallback;
            };

            document.querySelectorAll('[data-retired-army-edit]').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    showRetiredArmyForm();
                });
            });

            if (retiredArmyForm) {
                retiredArmyForm.addEventListener('submit', function (event) {
                    event.preventDefault();

                    const token = document.querySelector('meta[name="csrf-token"]');
                    const formData = employmentFormData(retiredArmyForm);
                    formData.append('_method', 'PUT');

                    fetch(retiredArmyForm.dataset.action, {
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
                                throw new Error(resolveErrorMessage(data, 'Unable to save retired army experience.'));
                            });
                        }
                        return response.json();
                    }).then(function (data) {
                        applyRetiredArmyEmployment(data.data || {});
                        hasRetiredArmyEmployment = true;
                        showRetiredArmySummary();
                        if (typeof displaySuccessMessage === 'function') {
                            displaySuccessMessage(data.message);
                        }
                    }).catch(function (error) {
                        if (typeof displayErrorMessage === 'function') {
                            displayErrorMessage(error.message);
                        } else {
                            alert(error.message);
                        }
                    });
                });
            }

            document.querySelectorAll('[data-retired-army-delete]').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();

                    if (!retiredArmyForm || !retiredArmyForm.dataset.deleteAction) {
                        return;
                    }

                    const removeEmployment = function () {
                        const token = document.querySelector('meta[name="csrf-token"]');
                        fetch(retiredArmyForm.dataset.deleteAction, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token ? token.content : '',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            body: new URLSearchParams({ _method: 'DELETE' }),
                        }).then(function (response) {
                            if (!response.ok) {
                                return response.json().then(function (data) {
                                    throw new Error(resolveErrorMessage(data, 'Unable to delete retired army experience.'));
                                });
                            }
                            return response.json();
                        }).then(function (data) {
                            ['baNo', 'rank', 'type', 'arms', 'trade', 'course', 'commissionDate', 'retirementDate'].forEach(function (name) {
                                setRetiredArmyValue(name, '');
                            });
                            retiredArmyForm.reset();
                            hasRetiredArmyEmployment = false;
                            showRetiredArmyForm();
                            if (typeof displaySuccessMessage === 'function') {
                                displaySuccessMessage(data.message);
                            }
                        }).catch(function (error) {
                            if (typeof displayErrorMessage === 'function') {
                                displayErrorMessage(error.message);
                            } else {
                                alert(error.message);
                            }
                        });
                    };

                    if (typeof swal === 'function') {
                        swal({
                            title: '{{ __('messages.common.delete') }} !',
                            text: '{{ __('messages.common.are_you_sure') }}',
                            buttons: {
                                confirm: '{{ __('messages.common.yes_delete') }}',
                                cancel: '{{ __('messages.common.no_cancel') }}',
                            },
                            icon: 'warning',
                        }).then(function (willDelete) {
                            if (willDelete) {
                                removeEmployment();
                            }
                        });
                        return;
                    }

                    if (window.confirm('Are you sure want to delete this')) {
                        removeEmployment();
                    }
                });
            });

            document.querySelectorAll('[data-retired-army-close]').forEach(function (button) {
                button.addEventListener('click', function () {
                    if (hasRetiredArmyEmployment) {
                        showRetiredArmySummary();
                    }
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
                const deleteExpBtn = event.target.closest('.delete-experience');
                if (deleteExpBtn) {
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
                    return;
                }

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
                    form.querySelectorAll('[data-employment-date]').forEach(function (input) {
                        if (!input.name || !input._flatpickr || !input._flatpickr.selectedDates.length) {
                            return;
                        }

                        const selectedDate = input._flatpickr.selectedDates[0];
                        const year = selectedDate.getFullYear();
                        const month = String(selectedDate.getMonth() + 1).padStart(2, '0');
                        const day = String(selectedDate.getDate()).padStart(2, '0');
                        formData.set(input.name, year + '-' + month + '-' + day);
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
