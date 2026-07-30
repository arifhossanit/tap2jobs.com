@extends('candidate.profile.index')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/inttel/css/intlTelInput.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}">
@endpush
@section('section')
    @php
        $genderOptions = ['0' => __('messages.common.male'), '1' => __('messages.common.female'), '2' => __('messages.candidate_profile.other')];
        $religionOptions = [
            '' => __('messages.candidate_profile.select_religion'),
            'Islam' => 'Islam',
            'Hinduism' => 'Hinduism',
            'Christianity' => 'Christianity',
            'Buddhism' => 'Buddhism',
            'Other' => __('messages.candidate_profile.other'),
        ];
        $bloodGroups = [
            '' => __('messages.candidate_profile.select_blood_group'),
            'A+' => 'A+',
            'A-' => 'A-',
            'B+' => 'B+',
            'B-' => 'B-',
            'AB+' => 'AB+',
            'AB-' => 'AB-',
            'O+' => 'O+',
            'O-' => 'O-',
        ];
        $profileDisplayValue = function ($value) {
            return filled($value) ? $value : '---';
        };
        $profileDisplayDate = filled($user->dob) ? \Carbon\Carbon::parse($user->dob)->format('d M Y') : '---';
        $passportIssueDate = filled($user->candidate->passport_issue_date ?? null) ? \Carbon\Carbon::parse($user->candidate->passport_issue_date)->format('d M Y') : '---';
        $profileGender = $genderOptions[(string) ($user->gender ?? '0')] ?? '---';
        $profileMaritalStatus = isset($user->candidate->marital_status_id) && isset($data['maritalStatus'][$user->candidate->marital_status_id]) ? $data['maritalStatus'][$user->candidate->marital_status_id] : '---';
    @endphp
    {{ Form::model($user, ['route' => 'candidate-profile.update', 'files' => true, 'id' => 'candidateProfileUpdate', 'method' => 'put']) }}
    {{ Form::hidden('isEdit', true, ['id' => 'isEdit']) }}

    <div class="candidate-profile-accordion" id="candidateProfileAccordion">
        <div class="alert alert-danger d-none" id="validationErrors">
            <i class='fa-solid fa-face-frown me-4'></i>
        </div>

        <section class="candidate-profile-section">
            <div class="candidate-profile-section__header">
                <span>{{ __('messages.candidate_profile.personal_details') }}</span>
                <span class="candidate-profile-section__header-actions">
                    <button class="candidate-personal-edit-action" type="button" data-personal-edit-toggle>
                        <i class="fa-solid fa-pen-to-square"></i> {{ __('messages.common.edit') }}
                    </button>
                    <button class="candidate-profile-section__toggle" type="button" data-bs-toggle="collapse"
                            data-bs-target="#candidatePersonalDetails" aria-expanded="true"
                            aria-controls="candidatePersonalDetails">
                        {{ __('messages.candidate_profile.collapse') }} <i class="fa-solid fa-chevron-up"></i>
                    </button>
                </span>
            </div>
            <div id="candidatePersonalDetails" class="collapse show candidate-profile-section__collapse"
                 data-bs-parent="#candidateProfileAccordion">
                <div class="candidate-profile-section__body">
                    <div class="candidate-personal-summary">
                        <div class="candidate-personal-image-row">
                            <img src="{{ $user->avatar }}" data-original-src="{{ $user->avatar }}" alt="{{ __('messages.candidate_profile.personal_details') }}" class="candidate-personal-avatar" id="candidatePersonalAvatar">
                            <div>
                                <label class="candidate-personal-image-btn">
                                    {{ __('messages.tooltip.change_image') }}
                                    <input type="file" name="image" id="candidatePersonalImageInput" class="d-none" accept="image/png,image/jpeg,image/jpg">
                                </label>
                                <span class="candidate-personal-or">{{ __('messages.candidate_profile.or') }}</span>
                                <button type="button" class="candidate-personal-delete">{{ __('messages.common.delete') }}</button>
                                <p>{{ __('messages.candidate_profile.upload_profile_image_note') }}</p>
                            </div>
                        </div>
                        <div class="candidate-personal-summary-grid">
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate_profile.first_name') }}</span>
                                <strong>{{ $profileDisplayValue($user->first_name) }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate_profile.last_name') }}</span>
                                <strong>{{ $profileDisplayValue($user->last_name) }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate_profile.father_name') }}</span>
                                <strong>{{ $profileDisplayValue($user->candidate->father_name ?? null) }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate_profile.mother_name') }}</span>
                                <strong>{{ $profileDisplayValue($user->candidate->mother_name ?? null) }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate_profile.date_of_birth') }}</span>
                                <strong>{{ $profileDisplayDate }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate.gender') }}</span>
                                <strong>{{ $profileGender }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate_profile.religion') }}</span>
                                <strong>{{ $profileDisplayValue($user->candidate->religion ?? null) }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate.marital_status') }}</span>
                                <strong>{{ $profileDisplayValue($profileMaritalStatus) }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate.nationality') }}</span>
                                <strong>{{ $profileDisplayValue($user->candidate->nationality ?? null) }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate_profile.national_id_number') }}</span>
                                <strong>{{ $profileDisplayValue($user->candidate->national_id_card ?? null) }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate_profile.passport_number') }}</span>
                                <strong>{{ $profileDisplayValue($user->candidate->passport_number ?? null) }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate_profile.passport_issue_date') }}</span>
                                <strong>{{ $passportIssueDate }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate_profile.primary_mobile') }}</span>
                                <a href="javascript:void(0)" class="candidate-personal-change-user-id"><i class="fa-solid fa-plus"></i> {{ __('messages.candidate_profile.change_user_id') }}</a>
                                <strong>{{ $profileDisplayValue($user->phone) }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate_profile.secondary_mobile') }}</span>
                                <strong>{{ $profileDisplayValue($user->candidate->secondary_mobile ?? null) }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate_profile.primary_email') }}</span>
                                <strong>{{ $profileDisplayValue($user->email) }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate_profile.alternate_email') }}</span>
                                <strong>{{ $profileDisplayValue($user->candidate->alternate_email ?? null) }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate_profile.emergency_contact') }}</span>
                                <strong>{{ $profileDisplayValue($user->candidate->emergency_contact ?? null) }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate_profile.blood_group') }}</span>
                                <strong>{{ $profileDisplayValue($user->candidate->blood_group ?? null) }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate_profile.height_meters') }}</span>
                                <strong>{{ $profileDisplayValue($user->candidate->height ?? null) }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate_profile.weight_kg') }}</span>
                                <strong>{{ $profileDisplayValue($user->candidate->weight ?? null) }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="row candidate-personal-form d-none">
                        <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
                            {{ Form::label('first_name', __('messages.candidate_profile.first_name'), ['class' => 'form-label']) }}
                            <span class="required"></span>
                            {{ Form::text('first_name', $user->first_name, ['class' => 'form-control', 'required', 'placeholder' => __('messages.candidate_profile.enter_first_name')]) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
                            {{ Form::label('last_name', __('messages.candidate_profile.last_name'), ['class' => 'form-label']) }}
                            {{ Form::text('last_name', $user->last_name, ['class' => 'form-control', 'required', 'placeholder' => __('messages.candidate_profile.enter_last_name')]) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
                            {{ Form::label('father_name', __('messages.candidate_profile.father_name'), ['class' => 'form-label']) }}
                            {{ Form::text('father_name', $user->candidate->father_name, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_father_name')]) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
                            {{ Form::label('mother_name', __('messages.candidate_profile.mother_name'), ['class' => 'form-label']) }}
                            {{ Form::text('mother_name', $user->candidate->mother_name ?? null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_mother_name')]) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
                            {{ Form::label('dob', __('messages.candidate_profile.date_of_birth'), ['class' => 'form-label']) }}
                            <span class="required"></span>
                            <input type="text" name="dob" id="birthDate"
                                   class="form-control {{ getLoggedInUser()->theme_mode ? 'bg-light' : 'bg-white' }}"
                                   autocomplete="off" placeholder="{{ __('messages.candidate_profile.enter_date_of_birth') }}"
                                   value="{{ $user->dob }}">
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
                            {{ Form::label('gender', __('messages.candidate.gender'), ['class' => 'form-label']) }}
                            <span class="required"></span>
                            {{ Form::select('gender', $genderOptions, isset($user->gender) ? (string) $user->gender : '0', ['class' => 'form-select', 'required']) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
                            {{ Form::label('religion', __('messages.candidate_profile.religion'), ['class' => 'form-label']) }}
                            {{ Form::select('religion', $religionOptions, $user->candidate->religion ?? null, ['class' => 'form-select']) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
                            {{ Form::label('marital_status', __('messages.candidate.marital_status'), ['class' => 'form-label']) }}
                            <span class="required"></span>
                            {{ Form::select('marital_status_id', $data['maritalStatus'], isset($user->candidate->marital_status_id) ? $user->candidate->marital_status_id : null, ['class' => 'form-select', 'id' => 'maritalStatusId', 'required']) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
                            <div class="candidate-nationality-label">
                                <span>{{ Form::label('nationality', __('messages.candidate.nationality'), ['class' => 'form-label mb-0']) }} <span class="required"></span></span>
                                <label class="candidate-nationality-check">
                                    {{ Form::checkbox('is_bangladeshi', '1', (isset($user->candidate->nationality) ? $user->candidate->nationality : 'Bangladeshi') == 'Bangladeshi', ['class' => 'form-check-input', 'id' => 'isBangladeshi']) }}
                                    <span>{{ __('messages.candidate_profile.bangladeshi') }}</span>
                                </label>
                            </div>
                            {{ Form::text('nationality', isset($user->candidate->nationality) ? $user->candidate->nationality : 'Bangladeshi', ['class' => 'form-control', 'id' => 'nationalityInput', 'placeholder' => __('messages.candidate_profile.enter_nationality')]) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
                            {{ Form::label('national_id_card', __('messages.candidate_profile.national_id_number'), ['class' => 'form-label']) }}
                            {{ Form::text('national_id_card', isset($user->candidate->national_id_card) ? $user->candidate->national_id_card : null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_national_id_number')]) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
                            {{ Form::label('passport_number', __('messages.candidate_profile.passport_number'), ['class' => 'form-label']) }}
                            {{ Form::text('passport_number', $user->candidate->passport_number ?? null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_passport_number')]) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
                            {{ Form::label('passport_issue_date', __('messages.candidate_profile.passport_issue_date'), ['class' => 'form-label']) }}
                            <input type="text" name="passport_issue_date" id="passportIssueDate"
                                   class="form-control {{ getLoggedInUser()->theme_mode ? 'bg-light' : 'bg-white' }}"
                                   autocomplete="off" placeholder="{{ __('messages.candidate_profile.enter_passport_issue_date') }}"
                                   value="{{ $user->candidate->passport_issue_date ?? null }}">
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 mb-5 mobile-itel-width">
                            {{ Form::label('phone', __('messages.candidate_profile.primary_mobile'), ['class' => 'form-label']) }}
                            <span class="candidate-field-note">({{ __('messages.candidate_profile.phone_note') }})</span>
                            <span class="required"></span>
                            {{ Form::tel('phone', isset($user->phone) ? $user->phone : null, ['class' => 'form-control', 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")', 'id' => 'phoneNumber']) }}
                            {{ Form::hidden('region_code', null, ['id' => 'prefix_code']) }}
                            <span id="valid-msg" class="text-success d-block fw-400 fs-small mt-2 d-none">{{ __('messages.phone.valid_number') }}</span>
                            <span id="error-msg" class="text-danger d-block fw-400 fs-small mt-2 d-none"></span>
                            {{-- <a href="javascript:void(0)" class="candidate-change-user-id"><i class="fa-solid fa-plus"></i> Change User Id</a> --}}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
                            {{ Form::label('secondary_mobile', __('messages.candidate_profile.secondary_mobile'), ['class' => 'form-label']) }}
                            {{ Form::text('secondary_mobile', $user->candidate->secondary_mobile ?? null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_phone_number'), 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")']) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-6 mb-5">
                            {{ Form::label('email', __('messages.candidate_profile.primary_email'), ['class' => 'form-label']) }}
                            <span class="candidate-field-note">({{ __('messages.candidate_profile.email_note') }})</span>
                            {{ Form::email('email', isset($user) ? $user->email : null, ['class' => 'form-control', 'required', 'placeholder' => __('messages.candidate_profile.enter_primary_email')]) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-6 mb-5">
                            {{ Form::label('alternate_email', __('messages.candidate_profile.alternate_email'), ['class' => 'form-label']) }}
                            {{ Form::email('alternate_email', $user->candidate->alternate_email ?? null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_alternate_email')]) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
                            {{ Form::label('emergency_contact', __('messages.candidate_profile.emergency_contact'), ['class' => 'form-label']) }}
                            {{ Form::text('emergency_contact', $user->candidate->emergency_contact ?? null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_emergency_contact'), 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")']) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
                            {{ Form::label('blood_group', __('messages.candidate_profile.blood_group'), ['class' => 'form-label']) }}
                            {{ Form::select('blood_group', $bloodGroups, $user->candidate->blood_group ?? null, ['class' => 'form-select']) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
                            {{ Form::label('height', __('messages.candidate_profile.height_meters'), ['class' => 'form-label']) }}
                            {{ Form::number('height', $user->candidate->height ?? null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_height'), 'step' => '0.01', 'min' => '0']) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
                            {{ Form::label('weight', __('messages.candidate_profile.weight_kg'), ['class' => 'form-label']) }}
                            {{ Form::number('weight', $user->candidate->weight ?? null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_weight'), 'step' => '0.01', 'min' => '0']) }}
                        </div>
                        <div class="col-12">
                            <div class="candidate-profile-section-actions">
                                {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary btnSave']) }}
                                <button type="button" class="btn btn-outline-secondary" data-personal-edit-close>{{ __('messages.common.close') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="candidate-profile-section">
            <div class="candidate-profile-section__header collapsed">
                <span>{{ __('messages.candidate_profile.address_details') }}</span>
                <span class="candidate-profile-section__header-actions">
                    <button class="candidate-address-edit-action d-none" type="button" data-address-edit-toggle>
                        <i class="fa-solid fa-pen-to-square"></i> {{ __('messages.common.edit') }}
                    </button>
                    <button class="candidate-profile-section__toggle" type="button" data-bs-toggle="collapse"
                            data-bs-target="#candidateAddressDetails" aria-expanded="false"
                            aria-controls="candidateAddressDetails">
                        {{ __('messages.candidate_profile.expand') }} <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </span>
            </div>
            <div id="candidateAddressDetails" class="collapse candidate-profile-section__collapse"
                 data-bs-parent="#candidateProfileAccordion">
                <div class="candidate-profile-section__body">
                    @php
                        $addressDisplayValue = function ($value) {
                            return filled($value) ? $value : '---';
                        };
                        $addressCountry = ($data['countries'] ?? [])[$user->country_id ?? null] ?? null;
                        $addressState = ($states ?? [])[$user->state_id ?? null] ?? null;
                        $addressCity = ($cities ?? [])[$user->city_id ?? null] ?? null;
                        $presentAddressParts = collect([
                            $user->candidate->address ?? null,
                            $addressCity,
                            $addressState,
                            $addressCountry,
                        ])->filter(fn ($value) => filled($value))->values();
                        $presentAddress = $presentAddressParts->isNotEmpty() ? $presentAddressParts->implode(', ') : '---';
                    @endphp
                    <div class="candidate-address-summary">
                        <div class="candidate-address-summary-item">
                            <span>{{ __('messages.candidate_profile.present_address') }}</span>
                            <strong>{{ $presentAddress }}</strong>
                        </div>
                        <div class="candidate-address-summary-item">
                            <span>{{ __('messages.candidate_profile.permanent_address') }}</span>
                            <strong>{{ __('messages.candidate_profile.same_as_present_address') }}</strong>
                        </div>
                    </div>
                    <div class="row candidate-address-form d-none">
                        <div class="col-xl-4 col-md-6 col-sm-12 mb-5">
                            {{ Form::label('country', __('messages.company.country') . ':', ['class' => 'form-label']) }}
                            {{ Form::select('country_id', $data['countries'], $user->country_id ?? null, ['class' => 'form-select ', 'id' => 'countryId', 'placeholder' => __('messages.company.select_country')]) }}
                        </div>
                        <div class="col-xl-4 col-md-6 col-sm-12 mb-5">
                            {{ Form::label('state', __('messages.company.state') . ':', ['class' => 'form-label']) }}
                            {{ Form::select('state_id', isset($states) && $states != null ? $states : [], $user->state_id ?? null, ['id' => 'stateId', 'class' => 'form-select', 'placeholder' => __('messages.company.select_state')]) }}
                        </div>
                        <div class="col-xl-4 col-md-6 col-sm-12 mb-5">
                            {{ Form::label('city', __('messages.company.city') . ':', ['class' => 'form-label']) }}
                            {{ Form::select('city_id', isset($cities) && $cities != null ? $cities : [], $user->city_id ?? null, ['class' => 'form-select ', 'id' => 'cityId', 'placeholder' => __('messages.company.select_city')]) }}
                        </div>
                        <div class="col-12 mb-5">
                            {{ Form::label('address', __('messages.candidate.address') . ':', ['class' => 'form-label']) }}
                            {{ Form::textarea('address', isset($user->candidate->address) ? $user->candidate->address : null, ['class' => 'form-control', 'rows' => '5', 'placeholder' => __('messages.candidate.address')]) }}
                        </div>
                        <div class="col-12">
                            <div class="candidate-profile-section-actions">
                                {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary btnSave']) }}
                                <button type="button" class="btn btn-outline-secondary" data-address-edit-close>{{ __('messages.common.close') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="candidate-profile-section">
            <div class="candidate-profile-section__header collapsed">
                <span>{{ __('messages.candidate_profile.career_application_information') }}</span>
                <span class="candidate-profile-section__header-actions">
                    <button class="candidate-section-edit-action d-none" type="button" data-career-edit-toggle>
                        <i class="fa-solid fa-pen-to-square"></i> {{ __('messages.common.edit') }}
                    </button>
                    <button class="candidate-profile-section__toggle" type="button" data-bs-toggle="collapse"
                            data-bs-target="#candidateCareerApplication" aria-expanded="false"
                            aria-controls="candidateCareerApplication">
                        {{ __('messages.candidate_profile.expand') }} <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </span>
            </div>
            <div id="candidateCareerApplication" class="collapse candidate-profile-section__collapse"
                 data-bs-parent="#candidateProfileAccordion">
                <div class="candidate-profile-section__body">
                    @php
                        $defaultObjective = __('messages.candidate_profile.objective_default');
                        $selectedJobLevel = $user->candidate->job_level ?? 'mid';
                        $selectedJobNature = $user->candidate->job_nature ?? 'full_time';
                        $careerDisplayValue = function ($value) {
                            return filled($value) ? $value : '---';
                        };
                        $careerFormatSalary = function ($value) {
                            if (!filled($value)) {
                                return '---';
                            }

                            $amount = preg_replace('/[^\d.]/', '', (string) $value);

                            return filled($amount) ? '৳ ' . number_format((float) $amount) : '---';
                        };
                        $jobLevelLabels = [
                            'entry' => __('messages.candidate_profile.entry_level'),
                            'mid' => __('messages.candidate_profile.mid_level'),
                            'top' => __('messages.candidate_profile.top_level'),
                        ];
                        $jobNatureLabels = [
                            'full_time' => __('messages.candidate_profile.full_time'),
                            'part_time' => __('messages.candidate_profile.part_time'),
                            'contract' => __('messages.candidate_profile.contract'),
                            'internship' => __('messages.candidate_profile.internship'),
                            'freelance' => __('messages.candidate_profile.freelance'),
                        ];
                    @endphp
                    <div class="candidate-career-summary">
                        <div class="candidate-career-summary-item candidate-career-summary-item--full">
                            <span>{{ __('messages.candidate_profile.objective') }}</span>
                            <strong>{{ $careerDisplayValue($user->candidate->objective ?? $defaultObjective) }}</strong>
                        </div>
                        <div class="candidate-career-summary-grid">
                            <div class="candidate-career-summary-item">
                                <span>{{ __('messages.candidate_profile.present_salary') }} ({{ __('messages.candidate_profile.taka_month') }})</span>
                                <strong>{{ $careerFormatSalary($user->candidate->current_salary ?? '20000') }}</strong>
                            </div>
                            <div class="candidate-career-summary-item">
                                <span>{{ __('messages.candidate_profile.expected_salary') }} ({{ __('messages.candidate_profile.taka_month') }})</span>
                                <strong>{{ $careerFormatSalary($user->candidate->expected_salary ?? '25000') }}</strong>
                            </div>
                            <div class="candidate-career-summary-item">
                                <span>{{ __('messages.candidate_profile.looking_for') }} ({{ __('messages.candidate_profile.job_level') }})</span>
                                <strong>{{ $jobLevelLabels[$selectedJobLevel] ?? '---' }}</strong>
                            </div>
                            <div class="candidate-career-summary-item">
                                <span>{{ __('messages.candidate_profile.available_for') }} ({{ __('messages.candidate_profile.job_nature') }})</span>
                                <strong>{{ $jobNatureLabels[$selectedJobNature] ?? '---' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="row candidate-career-form d-none">
                        <div class="col-12 mb-5">
                            <div class="candidate-objective-head">
                                <div>
                                    {{ Form::label('objective', __('messages.candidate_profile.objective'), ['class' => 'form-label']) }}
                                    <span class="required"></span>
                                    <p class="candidate-objective-help">{{ __('messages.candidate_profile.objective_help') }}</p>
                                </div>
                                <span class="candidate-objective-example-wrap">
                                    <button type="button" class="candidate-objective-example">
                                        {{ __('messages.candidate_profile.example') }}
                                        <i class="fa-solid fa-circle-info"></i>
                                    </button>
                                    <span class="candidate-objective-popover">
                                        <strong>{{ __('messages.candidate_profile.good_example') }}</strong>
                                        <span>{{ __('messages.candidate_profile.good_objective_example') }}</span>
                                        <strong>{{ __('messages.candidate_profile.bad_example') }}</strong>
                                        <span>{{ __('messages.candidate_profile.bad_objective_example') }}</span>
                                    </span>
                                </span>
                            </div>
                            {{ Form::textarea('objective', $user->candidate->objective ?? $defaultObjective, ['class' => 'form-control candidate-objective-textarea', 'rows' => 5, 'placeholder' => $defaultObjective]) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
                            {{ Form::label('current_salary', __('messages.candidate_profile.present_salary'), ['class' => 'form-label']) }}
                            <span class="candidate-field-note">({{ __('messages.candidate_profile.taka_month') }})</span>
                            {{ Form::text('current_salary', $user->candidate->current_salary ?? '20000', ['class' => 'form-control', 'placeholder' => '20000']) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
                            {{ Form::label('expected_salary', __('messages.candidate_profile.expected_salary'), ['class' => 'form-label']) }}
                            <span class="candidate-field-note">({{ __('messages.candidate_profile.taka_month') }})</span>
                            {{ Form::text('expected_salary', $user->candidate->expected_salary ?? '25000', ['class' => 'form-control', 'placeholder' => '25000']) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
                            <div class="form-label mb-3">
                                {{ __('messages.candidate_profile.looking_for') }}
                                <span class="candidate-field-note">({{ __('messages.candidate_profile.job_level') }})</span>
                            </div>
                            <div class="candidate-career-options">
                                <label class="candidate-career-radio">
                                    {{ Form::radio('job_level', 'entry', $selectedJobLevel === 'entry', ['class' => 'form-check-input']) }}
                                    <span>{{ __('messages.candidate_profile.entry_level') }}</span>
                                </label>
                                <label class="candidate-career-radio">
                                    {{ Form::radio('job_level', 'mid', $selectedJobLevel === 'mid', ['class' => 'form-check-input']) }}
                                    <span>{{ __('messages.candidate_profile.mid_level') }}</span>
                                </label>
                                <label class="candidate-career-radio">
                                    {{ Form::radio('job_level', 'top', $selectedJobLevel === 'top', ['class' => 'form-check-input']) }}
                                    <span>{{ __('messages.candidate_profile.top_level') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
                            <div class="form-label mb-3">
                                {{ __('messages.candidate_profile.available_for') }}
                                <span class="candidate-field-note">({{ __('messages.candidate_profile.job_nature') }})</span>
                            </div>
                            <div class="candidate-career-options candidate-career-options--wide">
                                <label class="candidate-career-radio">
                                    {{ Form::radio('job_nature', 'full_time', $selectedJobNature === 'full_time', ['class' => 'form-check-input']) }}
                                    <span>{{ __('messages.candidate_profile.full_time') }}</span>
                                </label>
                                <label class="candidate-career-radio">
                                    {{ Form::radio('job_nature', 'part_time', $selectedJobNature === 'part_time', ['class' => 'form-check-input']) }}
                                    <span>{{ __('messages.candidate_profile.part_time') }}</span>
                                </label>
                                <label class="candidate-career-radio">
                                    {{ Form::radio('job_nature', 'contract', $selectedJobNature === 'contract', ['class' => 'form-check-input']) }}
                                    <span>{{ __('messages.candidate_profile.contract') }}</span>
                                </label>
                                <label class="candidate-career-radio">
                                    {{ Form::radio('job_nature', 'internship', $selectedJobNature === 'internship', ['class' => 'form-check-input']) }}
                                    <span>{{ __('messages.candidate_profile.internship') }}</span>
                                </label>
                                <label class="candidate-career-radio">
                                    {{ Form::radio('job_nature', 'freelance', $selectedJobNature === 'freelance', ['class' => 'form-check-input']) }}
                                    <span>{{ __('messages.candidate_profile.freelance') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="candidate-profile-section-actions">
                                {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary']) }}
                                <button type="button" class="btn btn-light btn-active-light-primary" data-career-edit-close>{{ __('messages.common.close') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="candidate-profile-section">
            <div class="candidate-profile-section__header collapsed">
                <span>{{ __('messages.candidate_profile.preferred_area') }}</span>
                <span class="candidate-profile-section__header-actions">
                    <button class="candidate-section-edit-action d-none" type="button" data-preferred-edit-toggle>
                        <i class="fa-solid fa-pen-to-square"></i> {{ __('messages.common.edit') }}
                    </button>
                    <button class="candidate-profile-section__toggle" type="button" data-bs-toggle="collapse"
                            data-bs-target="#candidatePreferredArea" aria-expanded="false"
                            aria-controls="candidatePreferredArea">
                        {{ __('messages.candidate_profile.expand') }} <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </span>
            </div>
            <div id="candidatePreferredArea" class="collapse candidate-profile-section__collapse"
                 data-bs-parent="#candidateProfileAccordion">
                <div class="candidate-profile-section__body">
                    @php
                        $functionalOptions = collect($data['functionalArea'] ?? [])->take(8);
                        $specialSkillOptions = collect($data['skills'] ?? [])->take(8);
                        $districtOptions = collect($data['districts'] ?? [])->take(40);
                        $countryOptions = collect($data['outsideCountries'] ?? [])->take(80);
                        $organizationOptions = collect($data['organizationTypes'] ?? [])->take(30);

                        $preferredFunctional = collect($user->candidate->preferred_functional_categories ?? [])->map(fn ($id) => (string) $id)->toArray();
                        $preferredSkills = collect($user->candidate->preferred_special_skills ?? [])->map(fn ($id) => (string) $id)->toArray();
                        $preferredInside = collect($user->candidate->preferred_job_locations_inside ?? [])->map(fn ($id) => (string) $id)->toArray();
                        $preferredOutside = collect($user->candidate->preferred_job_locations_outside ?? [])->map(fn ($id) => (string) $id)->toArray();
                        $preferredOrganizations = collect($user->candidate->preferred_organization_types ?? [])->map(fn ($id) => (string) $id)->toArray();

                        if (empty($preferredFunctional)) {
                            $preferredFunctional = $functionalOptions->keys()->take(3)->map(fn ($id) => (string) $id)->toArray();
                        }
                        if (empty($preferredSkills)) {
                            $preferredSkills = $specialSkillOptions->keys()->take(1)->map(fn ($id) => (string) $id)->toArray();
                        }
                        if (empty($preferredInside)) {
                            $preferredInside = $districtOptions->keys()->take(3)->map(fn ($id) => (string) $id)->toArray();
                        }
                        $preferredNames = function ($ids, $options) {
                            return collect($ids)->map(function ($id) use ($options) {
                                return $options[$id] ?? null;
                            })->filter(fn ($value) => filled($value))->values();
                        };
                        $preferredFunctionalNames = $preferredNames($preferredFunctional, $functionalOptions);
                        $preferredSkillNames = $preferredNames($preferredSkills, $specialSkillOptions);
                        $preferredInsideNames = $preferredNames($preferredInside, $districtOptions);
                        $preferredOutsideNames = $preferredNames($preferredOutside, $countryOptions);
                        $preferredOrganizationNames = $preferredNames($preferredOrganizations, $organizationOptions);
                    @endphp
                    <div class="candidate-preferred-summary">
                        <div class="candidate-preferred-summary-block">
                            <h3>{{ __('messages.candidate_profile.preferred_job_categories') }}</h3>
                            <div class="candidate-preferred-summary-grid">
                                <div class="candidate-preferred-summary-item">
                                    <span>{{ __('messages.candidate_profile.functional') }}</span>
                                    <div class="candidate-preferred-summary-chips">
                                        @forelse($preferredFunctionalNames as $name)
                                            <span>{{ html_entity_decode($name) }}</span>
                                        @empty
                                            <strong>---</strong>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="candidate-preferred-summary-item">
                                    <span>{{ __('messages.candidate_profile.special_skills') }}</span>
                                    <div class="candidate-preferred-summary-chips">
                                        @forelse($preferredSkillNames as $name)
                                            <span>{{ html_entity_decode($name) }}</span>
                                        @empty
                                            <strong>---</strong>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="candidate-preferred-summary-block">
                            <h3>{{ __('messages.candidate_profile.preferred_job_location') }}</h3>
                            <div class="candidate-preferred-summary-grid">
                                <div class="candidate-preferred-summary-item">
                                    <span>{{ __('messages.candidate_profile.inside_bangladesh_districts') }}</span>
                                    <div class="candidate-preferred-summary-chips">
                                        @forelse($preferredInsideNames as $name)
                                            <span>{{ html_entity_decode($name) }}</span>
                                        @empty
                                            <strong>---</strong>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="candidate-preferred-summary-item">
                                    <span>{{ __('messages.candidate_profile.outside_bangladesh_countries') }}</span>
                                    <div class="candidate-preferred-summary-chips">
                                        @forelse($preferredOutsideNames as $name)
                                            <span>{{ html_entity_decode($name) }}</span>
                                        @empty
                                            <strong>---</strong>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="candidate-preferred-summary-item">
                                    <span>{{ __('messages.candidate_profile.add_preferred_organization_type') }}</span>
                                    <div class="candidate-preferred-summary-chips">
                                        @forelse($preferredOrganizationNames as $name)
                                            <span>{{ html_entity_decode($name) }}</span>
                                        @empty
                                            <strong>---</strong>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="candidate-preferred-area candidate-preferred-form d-none">
                        <div class="candidate-preferred-block">
                            <h3>{{ __('messages.candidate_profile.preferred_job_categories') }}<span class="required"></span></h3>
                            <p>{{ __('messages.candidate_profile.preferred_job_categories_help') }}</p>
                            <div class="candidate-preferred-category-grid">
                                <div>
                                    <div class="candidate-preferred-label">{{ __('messages.candidate_profile.functional') }} <span>({{ __('messages.candidate_profile.max_3') }})</span></div>
                                    <div class="candidate-preferred-checklist">
                                        @foreach($functionalOptions as $id => $name)
                                            <label class="candidate-preferred-check">
                                                <input class="form-check-input candidate-preferred-checkbox" type="checkbox"
                                                       name="preferred_functional_categories[]"
                                                       value="{{ $id }}" data-label="{{ html_entity_decode($name) }}"
                                                       data-chip-target="#functionalCategoryChips"
                                                       {{ in_array((string) $id, $preferredFunctional, true) ? 'checked' : '' }}>
                                                <span>{{ html_entity_decode($name) }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <div class="candidate-preferred-chips" id="functionalCategoryChips"></div>
                                </div>
                                <div>
                                    <div class="candidate-preferred-label">{{ __('messages.candidate_profile.special_skills') }} <span>({{ __('messages.candidate_profile.max_3') }})</span></div>
                                    <div class="candidate-preferred-checklist">
                                        @foreach($specialSkillOptions as $id => $name)
                                            <label class="candidate-preferred-check">
                                                <input class="form-check-input candidate-preferred-checkbox" type="checkbox"
                                                       name="preferred_special_skills[]"
                                                       value="{{ $id }}" data-label="{{ html_entity_decode($name) }}"
                                                       data-chip-target="#specialSkillChips"
                                                       {{ in_array((string) $id, $preferredSkills, true) ? 'checked' : '' }}>
                                                <span>{{ html_entity_decode($name) }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <div class="candidate-preferred-chips" id="specialSkillChips"></div>
                                </div>
                            </div>
                        </div>

                        <div class="candidate-preferred-block">
                            <h3>{{ __('messages.candidate_profile.preferred_job_location') }} <span class="required"></span></h3>
                            <p>{{ __('messages.candidate_profile.preferred_job_location_help') }}</p>

                            <div class="candidate-preferred-label">{{ __('messages.candidate_profile.inside_bangladesh_districts') }} <span>({{ __('messages.candidate_profile.max_15') }})</span></div>
                            {{ Form::select('preferred_job_locations_inside[]', $districtOptions->toArray(), $preferredInside, ['class' => 'form-select candidate-preferred-select', 'id' => 'preferredInsideDistricts', 'multiple' => true, 'data-placeholder' => __('messages.candidate_profile.add_districts'), 'data-chip-target' => '#insideDistrictChips']) }}
                            <div class="candidate-preferred-chips" id="insideDistrictChips"></div>

                            <div class="candidate-preferred-label mt-4">{{ __('messages.candidate_profile.outside_bangladesh_countries') }} <span>({{ __('messages.candidate_profile.max_10') }})</span></div>
                            {{ Form::select('preferred_job_locations_outside[]', $countryOptions->toArray(), $preferredOutside, ['class' => 'form-select candidate-preferred-select', 'id' => 'preferredOutsideCountries', 'multiple' => true, 'data-placeholder' => __('messages.candidate_profile.add_countries'), 'data-chip-target' => '#outsideCountryChips']) }}
                            <div class="candidate-preferred-chips" id="outsideCountryChips"></div>

                            <div class="candidate-preferred-label mt-4">{{ __('messages.candidate_profile.add_preferred_organization_type') }} <span>({{ __('messages.candidate_profile.max_12') }})</span></div>
                            {{ Form::select('preferred_organization_types[]', $organizationOptions->toArray(), $preferredOrganizations, ['class' => 'form-select candidate-preferred-select', 'id' => 'preferredOrganizationTypes', 'multiple' => true, 'data-placeholder' => __('messages.candidate_profile.add_organization_type'), 'data-chip-target' => '#organizationTypeChips']) }}
                            <div class="candidate-preferred-chips" id="organizationTypeChips"></div>
                        </div>

                        <div class="candidate-profile-section-actions">
                            {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary']) }}
                            <button type="button" class="btn btn-light btn-active-light-primary" data-preferred-edit-close>{{ __('messages.common.close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="candidate-profile-section">
            <div class="candidate-profile-section__header collapsed">
                <span>{{ __('messages.candidate_profile.relevant_information') }}</span>
                <span class="candidate-profile-section__header-actions">
                    <button class="candidate-section-edit-action d-none" type="button" data-relevant-edit-toggle>
                        <i class="fa-solid fa-pen-to-square"></i> {{ __('messages.common.edit') }}
                    </button>
                    <button class="candidate-profile-section__toggle" type="button" data-bs-toggle="collapse"
                            data-bs-target="#candidateRelevantInformation" aria-expanded="false"
                            aria-controls="candidateRelevantInformation">
                        {{ __('messages.candidate_profile.expand') }} <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </span>
            </div>
            <div id="candidateRelevantInformation" class="collapse candidate-profile-section__collapse"
                 data-bs-parent="#candidateProfileAccordion">
                <div class="candidate-profile-section__body">
                    @php
                        $relevantDisplayValue = function ($value) {
                            return filled($value) ? $value : '---';
                        };
                        $careerSummaryValue = $user->candidate->career_summary ?? null;
                    @endphp
                    <div class="candidate-relevant-summary">
                        <div class="candidate-relevant-summary-item">
                            <span>{{ __('messages.candidate_profile.career_summary') }}</span>
                            @if(filled($careerSummaryValue))
                                <div class="candidate-relevant-summary-content">{!! $careerSummaryValue !!}</div>
                            @else
                                <em>{{ __('messages.candidate_profile.no_description_provided') }}</em>
                            @endif
                        </div>
                        <div class="candidate-relevant-summary-item">
                            <span>{{ __('messages.candidate_profile.special_qualification') }}</span>
                            <strong>{!! nl2br(e($relevantDisplayValue($user->candidate->special_qualification ?? __('messages.candidate_profile.special_qualification_default')))) !!}</strong>
                        </div>
                        <div class="candidate-relevant-summary-item">
                            <span>{{ __('messages.candidate_profile.keywords') }}</span>
                            <strong>{{ $relevantDisplayValue($user->candidate->keywords ?? __('messages.candidate_profile.keywords_default')) }}</strong>
                        </div>
                    </div>
                    <div class="candidate-relevant-area candidate-relevant-form d-none">
                        <div class="candidate-relevant-field">
                            <div class="candidate-relevant-head">
                                <div>
                                    {{ Form::label('career_summary', __('messages.candidate_profile.career_summary'), ['class' => 'form-label']) }}
                                    <p>{{ __('messages.candidate_profile.career_summary_help') }}</p>
                                </div>
                                {{-- <button type="button" class="candidate-relevant-example">
                                    {{ __('messages.candidate_profile.example') }}
                                    <i class="fa-solid fa-circle-info"></i>
                                </button> --}}
                            </div>
                            <div class="candidate-relevant-editor">
                                {{ Form::textarea('career_summary', $user->candidate->career_summary ?? null, ['class' => 'd-none', 'data-relevant-quill-input' => true]) }}
                                <div class="candidate-relevant-quill" data-relevant-quill-editor
                                     data-placeholder="{{ __('messages.candidate_profile.enter_writing_texts') }}"></div>
                            </div>
                        </div>

                        <div class="candidate-relevant-field">
                            <div class="candidate-relevant-head">
                                <div>
                                    {{ Form::label('special_qualification', __('messages.candidate_profile.special_qualification'), ['class' => 'form-label']) }}
                                    <p>{{ __('messages.candidate_profile.special_qualification_help') }}</p>
                                </div>
                                {{-- <button type="button" class="candidate-relevant-example">
                                    {{ __('messages.candidate_profile.example') }}
                                    <i class="fa-solid fa-circle-info"></i>
                                </button> --}}
                            </div>
                            {{ Form::textarea('special_qualification', $user->candidate->special_qualification ?? __('messages.candidate_profile.special_qualification_default'), ['class' => 'form-control candidate-relevant-textarea', 'rows' => 4]) }}
                        </div>

                        <div class="candidate-relevant-field">
                            <div class="candidate-relevant-head">
                                <div>
                                    {{ Form::label('keywords', __('messages.candidate_profile.keywords'), ['class' => 'form-label']) }}
                                    <span class="required"></span>
                                    <p>{{ __('messages.candidate_profile.keywords_help') }}</p>
                                </div>
                                {{-- <button type="button" class="candidate-relevant-example">
                                    {{ __('messages.candidate_profile.example') }}
                                    <i class="fa-solid fa-circle-info"></i>
                                </button> --}}
                            </div>
                            {{ Form::textarea('keywords', $user->candidate->keywords ?? __('messages.candidate_profile.keywords_default'), ['class' => 'form-control candidate-relevant-textarea', 'rows' => 4]) }}
                        </div>

                        <div class="candidate-profile-section-actions">
                            {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary']) }}
                            <button type="button" class="btn btn-light btn-active-light-primary" data-relevant-edit-close>{{ __('messages.common.close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="candidate-profile-section">
            <div class="candidate-profile-section__header collapsed">
                <span>{{ __('messages.candidate_profile.disability_information') }}</span>
                <span class="candidate-profile-section__header-actions">
                    <button class="candidate-section-edit-action d-none" type="button" data-disability-edit-toggle>
                        <i class="fa-solid fa-pen-to-square"></i> {{ __('messages.common.edit') }}
                    </button>
                    <button class="candidate-profile-section__toggle" type="button" data-bs-toggle="collapse"
                            data-bs-target="#candidateDisabilityInformation" aria-expanded="false"
                            aria-controls="candidateDisabilityInformation">
                        {{ __('messages.candidate_profile.expand') }} <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </span>
            </div>
            <div id="candidateDisabilityInformation" class="collapse candidate-profile-section__collapse"
                 data-bs-parent="#candidateProfileAccordion">
                <div class="candidate-profile-section__body">
                    <div class="candidate-disability-summary">
                        <p>{{ __('messages.candidate_profile.disability_id_not_mentioned') }}</p>
                        <p>
                            {{ __('messages.candidate_profile.disability_support_prefix') }}
                            <a href="tel:+8801730369802">{{ __('messages.candidate_profile.disability_support_contact') }}</a>
                            {{ __('messages.candidate_profile.disability_support_suffix') }}
                        </p>
                    </div>
                    <div class="candidate-disability-area candidate-disability-form d-none">
                        <div class="candidate-disability-question">
                            <span>{{ __('messages.candidate_profile.have_disability_id_number') }}</span><span class="required"></span>
                        </div>
                        <div class="candidate-disability-options">
                            <label class="candidate-career-radio">
                                {{ Form::radio('has_disability_id', '1', isset($user->candidate->has_disability_id) ? $user->candidate->has_disability_id == 1 : false, ['class' => 'form-check-input']) }}
                                <span>{{ __('messages.common.yes') }}</span>
                            </label>
                            <label class="candidate-career-radio">
                                {{ Form::radio('has_disability_id', '0', isset($user->candidate->has_disability_id) ? $user->candidate->has_disability_id == 0 : false, ['class' => 'form-check-input']) }}
                                <span>{{ __('messages.common.no') }}</span>
                            </label>
                        </div>
                        <p class="candidate-disability-support">
                            {{ __('messages.candidate_profile.disability_support_prefix') }}
                            <a href="tel:+8801730369802">{{ __('messages.candidate_profile.disability_support_contact') }}</a>
                            {{ __('messages.candidate_profile.disability_support_suffix') }}
                        </p>
                        <div class="candidate-profile-section-actions">
                            {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary']) }}
                            <button type="button" class="btn btn-light btn-active-light-primary" data-disability-edit-close>{{ __('messages.common.close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
    {{ Form::close() }}
@endsection
@push('scripts')
    <script>
        var phoneNo = "{{ old('region_code') . old('phone') }}";
        var candidateProfileCollapseText = "{{ __('messages.candidate_profile.collapse') }}";
        var candidateProfileExpandText = "{{ __('messages.candidate_profile.expand') }}";

        document.addEventListener('DOMContentLoaded', function () {
            const accordion = document.getElementById('candidateProfileAccordion');
            const menuLinks = document.querySelectorAll('[data-profile-section-link]');
            if (!accordion || !menuLinks.length || typeof bootstrap === 'undefined') {
                return;
            }

            function setActiveSection(sectionId) {
                menuLinks.forEach(function (link) {
                    link.classList.toggle('active', link.dataset.profileSectionLink === sectionId);
                });
            }

            function setHeaderState(section, expanded) {
                const control = document.querySelector('[data-bs-target="#' + section.id + '"]');
                const header = control ? control.closest('.candidate-profile-section__header') : null;
                const toggle = header
                    ? header.querySelector('.candidate-profile-section__toggle')
                    : null;
                if (!toggle) {
                    return;
                }

                header.classList.toggle('collapsed', !expanded);
                toggle.innerHTML = expanded
                    ? candidateProfileCollapseText + ' <i class="fa-solid fa-chevron-up"></i>'
                    : candidateProfileExpandText + ' <i class="fa-solid fa-chevron-down"></i>';

                const editAction = header.querySelector('.candidate-section-edit-action, .candidate-personal-edit-action, .candidate-address-edit-action');
                if (editAction) {
                    editAction.classList.toggle('d-none', !expanded || header.classList.contains('candidate-profile-section__header--editing'));
                }
            }

            menuLinks.forEach(function (link) {
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    const sectionId = link.dataset.profileSectionLink;
                    const target = document.getElementById(sectionId);
                    if (!target) {
                        return;
                    }
                    bootstrap.Collapse.getOrCreateInstance(target, { toggle: false }).show();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            });

            accordion.querySelectorAll('.candidate-profile-section__collapse').forEach(function (section) {
                section.addEventListener('shown.bs.collapse', function () {
                    setActiveSection(section.id);
                    setHeaderState(section, true);
                });
                section.addEventListener('hidden.bs.collapse', function () {
                    setHeaderState(section, false);
                });
            });

            accordion.querySelectorAll('.candidate-profile-section__header').forEach(function (header) {
                header.addEventListener('click', function (event) {
                    if (event.target.closest('button, a, input, select, textarea, label')) {
                        return;
                    }

                    const toggle = header.querySelector('.candidate-profile-section__toggle');
                    if (toggle) {
                        toggle.click();
                    }
                });
            });
        });
    </script>
@endpush
