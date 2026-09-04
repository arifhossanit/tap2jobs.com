@extends('candidate.profile.index')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/inttel/css/intlTelInput.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}">
@endpush
@section('section')
    @php
        $candidate = optional($user->candidate);
        $profileReferenceOptions = $data['profileReferenceOptions'] ?? [];
        $genderOptions = $profileReferenceOptions['gender'] ?? ['0' => __('messages.common.male'), '1' => __('messages.common.female'), '2' => __('messages.candidate_profile.other')];
        $religionOptions = ['' => __('messages.candidate_profile.select_religion')] + ($profileReferenceOptions['religion'] ?? []);
        $bloodGroups = ['' => __('messages.candidate_profile.select_blood_group')] + ($profileReferenceOptions['blood_group'] ?? []);
        $profileDisplayValue = function ($value) {
            return filled($value) ? $value : '---';
        };
        $profileDisplayDate = filled($user->dob) ? \Carbon\Carbon::parse($user->dob)->format('d M Y') : '---';
        $passportIssueDate = filled($candidate->passport_issue_date ?? null) ? \Carbon\Carbon::parse($candidate->passport_issue_date)->format('d M Y') : '---';
        $profileGender = $genderOptions[(string) ($user->gender ?? '0')] ?? '---';
        $profileMaritalStatus = isset($candidate->marital_status_id) && isset($data['maritalStatus'][$candidate->marital_status_id]) ? $data['maritalStatus'][$candidate->marital_status_id] : '---';
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
                            aria-controls="candidatePersonalDetails"
                            data-collapse-label="{{ __('messages.candidate_profile.collapse') }}"
                            data-expand-label="{{ __('messages.candidate_profile.expand') }}">
                        <span>{{ __('messages.candidate_profile.collapse') }}</span>
                        <i class="fa-solid fa-chevron-up"></i>
                    </button>
                </span>
            </div>
            <div id="candidatePersonalDetails" class="collapse show candidate-profile-section__collapse"
                 data-bs-parent="#candidateProfileAccordion">
                <div class="candidate-profile-section__body">
                    <div class="candidate-personal-image-row">
                        <img src="{{ $user->avatar }}" data-original-src="{{ $user->avatar }}" alt="{{ __('messages.candidate_profile.personal_details') }}" class="candidate-personal-avatar" id="candidatePersonalAvatar">
                        <div class="candidate-personal-image-actions d-none">
                            <button type="button" class="candidate-personal-image-btn" data-candidate-image-modal-open>
                                {{ __('messages.tooltip.change_image') }}
                            </button>
                            <span class="candidate-personal-or">{{ __('messages.candidate_profile.or') }}</span>
                            <button type="button" class="candidate-personal-delete">{{ __('messages.common.delete') }}</button>
                            <p>{{ __('messages.candidate_profile.upload_profile_image_note') }}</p>
                        </div>
                    </div>
                    <div class="candidate-personal-summary">
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
                                <strong>{{ $profileDisplayValue($candidate->father_name ?? null) }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate_profile.mother_name') }}</span>
                                <strong>{{ $profileDisplayValue($candidate->mother_name ?? null) }}</strong>
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
                                <strong>{{ $profileDisplayValue($candidate->religion ?? null) }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate.marital_status') }}</span>
                                <strong>{{ $profileDisplayValue($profileMaritalStatus) }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate.nationality') }}</span>
                                <strong>{{ $profileDisplayValue($candidate->nationality ?? null) }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate_profile.national_id_number') }}</span>
                                <strong>{{ $profileDisplayValue($candidate->national_id_card ?? null) }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate_profile.passport_number') }}</span>
                                <strong>{{ $profileDisplayValue($candidate->passport_number ?? null) }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate_profile.passport_issue_date') }}</span>
                                <strong>{{ $passportIssueDate }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate_profile.primary_mobile') }}</span>                                
                                <strong>{{ $profileDisplayValue($user->phone) }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate_profile.secondary_mobile') }}</span>
                                <strong>{{ $profileDisplayValue($candidate->secondary_mobile ?? null) }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate_profile.primary_email') }}</span>
                                <strong>{{ $profileDisplayValue($user->email) }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate_profile.alternate_email') }}</span>
                                <strong>{{ $profileDisplayValue($candidate->alternate_email ?? null) }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate_profile.emergency_contact') }}</span>
                                <strong>{{ $profileDisplayValue($candidate->emergency_contact ?? null) }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate_profile.blood_group') }}</span>
                                <strong>{{ $profileDisplayValue($candidate->blood_group ?? null) }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate_profile.height_meters') }}</span>
                                <strong>{{ $profileDisplayValue($candidate->height ?? null) }}</strong>
                            </div>
                            <div class="candidate-personal-summary-item">
                                <span>{{ __('messages.candidate_profile.weight_kg') }}</span>
                                <strong>{{ $profileDisplayValue($candidate->weight ?? null) }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="row candidate-personal-form d-none">
                        <div class="col-xl-6 col-md-6 col-sm-12 ">
                            {{ Form::label('first_name', __('messages.candidate_profile.first_name'), ['class' => 'form-label']) }}
                            <span class="required"></span>
                            {{ Form::text('first_name', $user->first_name, ['class' => 'form-control', 'required', 'placeholder' => __('messages.candidate_profile.enter_first_name')]) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 ">
                            {{ Form::label('last_name', __('messages.candidate_profile.last_name'), ['class' => 'form-label']) }}
                            {{ Form::text('last_name', $user->last_name, ['class' => 'form-control', 'required', 'placeholder' => __('messages.candidate_profile.enter_last_name')]) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 ">
                            {{ Form::label('father_name', __('messages.candidate_profile.father_name'), ['class' => 'form-label']) }}
                            {{ Form::text('father_name', $candidate->father_name, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_father_name')]) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 ">
                            {{ Form::label('mother_name', __('messages.candidate_profile.mother_name'), ['class' => 'form-label']) }}
                            {{ Form::text('mother_name', $candidate->mother_name ?? null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_mother_name')]) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 ">
                            {{ Form::label('dob', __('messages.candidate_profile.date_of_birth'), ['class' => 'form-label']) }}
                            <input type="text" name="dob" id="birthDate"
                                   class="form-control {{ getLoggedInUser()->theme_mode ? 'bg-light' : 'bg-white' }}"
                                   autocomplete="off" placeholder="{{ __('messages.candidate_profile.enter_date_of_birth') }}"
                                   value="{{ $user->dob }}">
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 ">
                            {{ Form::label('gender', __('messages.candidate.gender'), ['class' => 'form-label']) }}
                            <span class="required"></span>
                            {{ Form::select('gender', $genderOptions, isset($user->gender) ? (string) $user->gender : '0', ['class' => 'form-select', 'required']) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 ">
                            {{ Form::label('religion', __('messages.candidate_profile.religion'), ['class' => 'form-label']) }}
                            {{ Form::select('religion', $religionOptions, $candidate->religion ?? null, ['class' => 'form-select']) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 ">
                            {{ Form::label('marital_status', __('messages.candidate.marital_status'), ['class' => 'form-label']) }}
                            <span class="required"></span>
                            {{ Form::select('marital_status_id', $data['maritalStatus'], isset($candidate->marital_status_id) ? $candidate->marital_status_id : null, ['class' => 'form-select', 'id' => 'maritalStatusId', 'required']) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 ">
                            <div class="candidate-nationality-label">
                                <span>{{ Form::label('nationality', __('messages.candidate.nationality'), ['class' => 'form-label mb-0']) }}</span>
                                <label class="candidate-nationality-check">
                                    {{ Form::checkbox('is_bangladeshi', '1', (isset($candidate->nationality) ? $candidate->nationality : 'Bangladeshi') == 'Bangladeshi', ['class' => 'form-check-input', 'id' => 'isBangladeshi']) }}
                                    <span>{{ __('messages.candidate_profile.bangladeshi') }}</span>
                                </label>
                            </div>
                            {{ Form::text('nationality', isset($candidate->nationality) ? $candidate->nationality : 'Bangladeshi', ['class' => 'form-control', 'id' => 'nationalityInput', 'placeholder' => __('messages.candidate_profile.enter_nationality')]) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 ">
                            {{ Form::label('national_id_card', __('messages.candidate_profile.national_id_number'), ['class' => 'form-label']) }}
                            {{ Form::text('national_id_card', isset($candidate->national_id_card) ? $candidate->national_id_card : null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_national_id_number')]) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 ">
                            {{ Form::label('passport_number', __('messages.candidate_profile.passport_number'), ['class' => 'form-label']) }}
                            {{ Form::text('passport_number', $candidate->passport_number ?? null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_passport_number')]) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 ">
                            {{ Form::label('passport_issue_date', __('messages.candidate_profile.passport_issue_date'), ['class' => 'form-label']) }}
                            <input type="text" name="passport_issue_date" id="passportIssueDate"
                                   class="form-control {{ getLoggedInUser()->theme_mode ? 'bg-light' : 'bg-white' }}"
                                   autocomplete="off" placeholder="{{ __('messages.candidate_profile.enter_passport_issue_date') }}"
                                   value="{{ $candidate->passport_issue_date ?? null }}">
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12  mobile-itel-width">
                            {{ Form::label('phone', __('messages.candidate_profile.primary_mobile'), ['class' => 'form-label']) }}
                            <span class="candidate-field-note">({{ __('messages.candidate_profile.phone_note') }})</span>
                            {{ Form::tel('phone', isset($user->phone) ? $user->phone : null, ['class' => 'form-control', 'maxlength' => '11', 'inputmode' => 'numeric', 'pattern' => '[0-9]{1,11}', 'oninput' => 'this.value = this.value.replace(/\D/g,"").slice(0, 11)', 'id' => 'phoneNumber']) }}
                            {{ Form::hidden('region_code', null, ['id' => 'prefix_code']) }}
                            <span id="valid-msg" class="text-success d-block fw-400 fs-small mt-2 d-none">{{ __('messages.phone.valid_number') }}</span>
                            <span id="error-msg" class="text-danger d-block fw-400 fs-small mt-2 d-none"></span>                            
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 ">
                            {{ Form::label('secondary_mobile', __('messages.candidate_profile.secondary_mobile'), ['class' => 'form-label']) }}
                            {{ Form::text('secondary_mobile', $candidate->secondary_mobile ?? null, ['class' => 'form-control', 'maxlength' => '11', 'inputmode' => 'numeric', 'pattern' => '[0-9]{1,11}', 'placeholder' => __('messages.candidate_profile.enter_phone_number'), 'oninput' => 'this.value = this.value.replace(/\D/g,"").slice(0, 11)']) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-6 ">
                            {{ Form::label('email', __('messages.candidate_profile.primary_email'), ['class' => 'form-label']) }}
                            {{-- <span class="candidate-field-note">({{ __('messages.candidate_profile.email_note') }})</span> --}}
                            {{ Form::email('email', isset($user) ? $user->email : null, ['class' => 'form-control', 'required', 'placeholder' => __('messages.candidate_profile.enter_primary_email')]) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-6 ">
                            {{ Form::label('alternate_email', __('messages.candidate_profile.alternate_email'), ['class' => 'form-label']) }}
                            {{ Form::email('alternate_email', $candidate->alternate_email ?? null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_alternate_email')]) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 ">
                            {{ Form::label('emergency_contact', __('messages.candidate_profile.emergency_contact'), ['class' => 'form-label']) }}
                            {{ Form::text('emergency_contact', $candidate->emergency_contact ?? null, ['class' => 'form-control', 'maxlength' => '11', 'inputmode' => 'numeric', 'pattern' => '[0-9]{1,11}', 'placeholder' => __('messages.candidate_profile.enter_emergency_contact'), 'oninput' => 'this.value = this.value.replace(/\D/g,"").slice(0, 11)']) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 ">
                            {{ Form::label('blood_group', __('messages.candidate_profile.blood_group'), ['class' => 'form-label']) }}
                            {{ Form::select('blood_group', $bloodGroups, $candidate->blood_group ?? null, ['class' => 'form-select']) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 ">
                            {{ Form::label('height', __('messages.candidate_profile.height_meters'), ['class' => 'form-label']) }}
                            {{ Form::number('height', $candidate->height ?? null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_height'), 'step' => '0.01', 'min' => '0']) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 ">
                            {{ Form::label('weight', __('messages.candidate_profile.weight_kg'), ['class' => 'form-label']) }}
                            {{ Form::number('weight', $candidate->weight ?? null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_weight'), 'step' => '0.01', 'min' => '0']) }}
                        </div>
                        <div class="col-12">
                            <div class="candidate-profile-section-actions">
                                {{ Form::submit(__('messages.common.save'), ['id' => 'btnSave', 'class' => 'candidate-skill-save', 'formaction' => route('candidate-profile.personal-details.update'), 'formnovalidate' => true, 'data-scoped-ajax-submit' => true]) }}
                                <button type="button" class="candidate-skill-close" data-personal-edit-close>{{ __('messages.common.close') }}</button>
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
                            aria-controls="candidateAddressDetails"
                            data-collapse-label="{{ __('messages.candidate_profile.collapse') }}"
                            data-expand-label="{{ __('messages.candidate_profile.expand') }}">
                        <span>{{ __('messages.candidate_profile.expand') }}</span>
                        <i class="fa-solid fa-chevron-up"></i>
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
                        $bangladeshId = \App\Models\Country::where('short_code', 'BD')->orWhere('name', 'Bangladesh')->value('id');
                        $presentAddressType = $candidate->present_address_type ?? (($bangladeshId && (int) $user->country_id === (int) $bangladeshId) ? 'inside' : 'inside');
                        $permanentSameAsPresent = $candidate->permanent_same_as_present ?? true;
                        $permanentAddressType = $candidate->permanent_address_type ?? null;
                        $districtList = $data['districts'] ?? ($states ?? []);
                        $presentThanas = ! empty($user->city_id) ? getThanas($user->city_id) : [];
                        $permanentStates = ! empty($candidate->permanent_country_id) ? getStates($candidate->permanent_country_id) : ($bangladeshId ? getStates($bangladeshId) : []);
                        $permanentCities = ! empty($candidate->permanent_state_id) ? getCities($candidate->permanent_state_id) : [];
                        $permanentThanas = ! empty($candidate->permanent_city_id) ? getThanas($candidate->permanent_city_id) : [];
                        $addressCountry = $presentAddressType === 'outside'
                            ? ($candidate->present_country_name ?? null)
                            : (($data['countries'] ?? [])[$user->country_id ?? null] ?? null);
                        $addressState = ($states ?? [])[$user->state_id ?? null] ?? null;
                        $addressCity = ($cities ?? [])[$user->city_id ?? null] ?? null;
                        $addressThana = ($presentThanas ?? [])[$user->thana_id ?? null] ?? null;
                        $presentStateDivision = $presentAddressType === 'outside'
                            ? ($candidate->present_state_division ?? null)
                            : $addressState;
                        $presentAddressParts = collect([
                            $candidate->address ?? null,
                            $candidate->present_post_office ?? null,
                            $addressThana,
                            $addressCity,
                            $presentStateDivision,
                            $addressCountry,
                        ])->filter(fn ($value) => filled($value))->values();
                        $presentAddress = $presentAddressParts->isNotEmpty() ? $presentAddressParts->implode(', ') : '---';
                        $permanentStateDivision = $permanentAddressType === 'outside'
                            ? ($candidate->permanent_state_division ?? null)
                            : (($permanentStates ?? [])[$candidate->permanent_state_id ?? null] ?? null);
                        $permanentAddressParts = collect([
                            $candidate->permanent_address ?? null,
                            $candidate->permanent_post_office ?? null,
                            ($permanentThanas ?? [])[$candidate->permanent_thana_id ?? null] ?? null,
                            ($permanentCities ?? [])[$candidate->permanent_city_id ?? null] ?? null,
                            $permanentStateDivision,
                            ($data['countries'] ?? [])[$candidate->permanent_country_id ?? null] ?? null,
                        ])->filter(fn ($value) => filled($value))->values();
                        $permanentAddress = $permanentSameAsPresent
                            ? __('messages.candidate_profile.same_as_present_address')
                            : ($permanentAddressParts->isNotEmpty() ? $permanentAddressParts->implode(', ') : '---');
                        $hasPermanentDetails = ! $permanentSameAsPresent && collect([
                            $candidate->permanent_address_type ?? null,
                            $candidate->permanent_country_id ?? null,
                            $candidate->permanent_state_id ?? null,
                            $candidate->permanent_state_division ?? null,
                            $candidate->permanent_city_id ?? null,
                            $candidate->permanent_thana_id ?? null,
                            $candidate->permanent_post_office ?? null,
                            $candidate->permanent_address ?? null,
                        ])->contains(fn ($value) => filled($value));
                    @endphp
                    <div class="candidate-address-summary">
                        <div class="candidate-address-summary-item">
                            <span>{{ __('messages.candidate_profile.present_address') }}</span>
                            <strong>{{ $presentAddress }}</strong>
                        </div>
                        <div class="candidate-address-summary-item">
                            <span>{{ __('messages.candidate_profile.permanent_address') }}</span>
                            <strong>{{ $permanentAddress }}</strong>
                        </div>
                    </div>
                    <div class="candidate-address-form d-none" data-has-permanent-details="{{ $hasPermanentDetails ? '1' : '0' }}">
                        <input type="hidden" name="country_id" id="countryId" value="{{ $user->country_id ?? $bangladeshId }}" data-bangladesh-id="{{ $bangladeshId }}">
                        <div class="candidate-address-heading">Present Address<span class="required"></span></div>
                        <div class="candidate-address-choice-row">
                            <label class="candidate-address-choice">
                                <input type="radio" name="present_address_type" value="inside" {{ $presentAddressType === 'inside' ? 'checked' : '' }}>
                                <span>Inside Bangladesh</span>
                            </label>
                            <label class="candidate-address-choice">
                                <input type="radio" name="present_address_type" value="outside" {{ $presentAddressType === 'outside' ? 'checked' : '' }}>
                                <span>Outside Bangladesh</span>
                            </label>
                        </div>
                        <div class="candidate-address-grid">
                            <div class="candidate-address-field candidate-address-country-field {{ $presentAddressType === 'outside' ? '' : 'd-none' }}">
                                {{ Form::label('present_country_name', 'Country', ['class' => 'form-label required']) }}
                                {{ Form::text('present_country_name', $candidate->present_country_name ?? null, ['class' => 'form-control', 'id' => 'presentCountryName', 'placeholder' => 'Enter your Country']) }}
                            </div>
                            <div class="candidate-address-field candidate-present-district-field {{ $presentAddressType === 'outside' ? 'd-none' : '' }}">
                                {{ Form::label('state_id', 'Division', ['class' => 'form-label required']) }}
                                {{ Form::select('state_id', $districtList, $user->state_id ?? null, ['id' => 'stateId', 'class' => 'form-select', 'placeholder' => 'Select your Division']) }}
                            </div>
                            <div class="candidate-address-field candidate-present-district-field {{ $presentAddressType === 'outside' ? 'd-none' : '' }}">
                                {{ Form::label('city_id', 'District', ['class' => 'form-label']) }}
                                {{ Form::select('city_id', $cities ?? [], $user->city_id ?? null, ['id' => 'cityId', 'class' => 'form-select', 'placeholder' => 'Select your District']) }}
                            </div>
                            <div class="candidate-address-field candidate-present-district-field {{ $presentAddressType === 'outside' ? 'd-none' : '' }}">
                                {{ Form::label('thana_id', 'Thana', ['class' => 'form-label']) }}
                                {{ Form::select('thana_id', $presentThanas ?? [], $user->thana_id ?? null, ['id' => 'thanaId', 'class' => 'form-select', 'placeholder' => 'Select Thana']) }}
                            </div>
                            <div class="candidate-address-field candidate-present-state-text-field {{ $presentAddressType === 'outside' ? '' : 'd-none' }}">
                                {{ Form::label('present_state_division', 'State/Division', ['class' => 'form-label']) }}
                                {{ Form::text('present_state_division', $candidate->present_state_division ?? null, ['class' => 'form-control', 'placeholder' => 'Enter your State/Division']) }}
                            </div>
                            <div class="candidate-address-field candidate-present-thana-po-field {{ $presentAddressType === 'outside' ? 'd-none' : '' }}">
                                {{ Form::label('present_post_office', 'Post Office', ['class' => 'form-label required']) }}
                                {{ Form::text('present_post_office', $candidate->present_post_office ?? null, ['class' => 'form-control', 'placeholder' => 'Enter your Post Office']) }}
                            </div>
                            <div class="candidate-address-field candidate-address-field--full">
                                {{ Form::label('address', 'House No/Road/Village', ['class' => 'form-label required']) }}
                                {{ Form::textarea('address', $candidate->address ?? null, ['class' => 'form-control candidate-address-textarea', 'rows' => 3, 'placeholder' => 'Enter your House No/Road/Village']) }}
                            </div>
                        </div>
                        <div class="candidate-address-permanent-row">
                            <h3>Permanent Address</h3>
                            <label class="candidate-address-same-check">
                                <input class="form-check-input" type="checkbox" name="permanent_same_as_present" value="1"
                                       id="permanentSameAsPresent" {{ $permanentSameAsPresent ? 'checked' : '' }}>
                                <span>Same as Present Address</span>
                            </label>
                        </div>
                        <input type="hidden" name="permanent_address_selected" id="permanentAddressSelected" value="{{ $hasPermanentDetails ? '1' : '0' }}">
                        <div class="candidate-permanent-address-options {{ $permanentSameAsPresent ? 'd-none' : '' }}">
                            <div class="candidate-address-choice-row">
                                <label class="candidate-address-choice">
                                    <input type="radio" name="permanent_address_type" value="inside" {{ $permanentAddressType === 'inside' ? 'checked' : '' }}>
                                    <span>Inside Bangladesh</span>
                                </label>
                                <label class="candidate-address-choice">
                                    <input type="radio" name="permanent_address_type" value="outside" {{ $permanentAddressType === 'outside' ? 'checked' : '' }}>
                                    <span>Outside Bangladesh</span>
                                </label>
                            </div>
                        </div>
                        <div class="candidate-permanent-address-fields {{ $permanentSameAsPresent || ! $hasPermanentDetails ? 'd-none' : '' }}">
                            <div class="candidate-address-grid">
                                <div class="candidate-address-field candidate-permanent-country-field d-none">
                                    {{ Form::label('permanent_country_id', 'Country', ['class' => 'form-label']) }}
                                    {{ Form::select('permanent_country_id', $data['countries'], $candidate->permanent_country_id ?? $bangladeshId, ['class' => 'form-select', 'id' => 'permanentCountryId', 'placeholder' => __('messages.company.select_country')]) }}
                                </div>
                                <div class="candidate-address-field candidate-permanent-district-field {{ $permanentAddressType === 'outside' ? 'd-none' : '' }}">
                                    {{ Form::label('permanent_state_id', 'Division', ['class' => 'form-label']) }}
                                    {{ Form::select('permanent_state_id', $permanentStates, $candidate->permanent_state_id ?? null, ['id' => 'permanentStateId', 'class' => 'form-select', 'placeholder' => 'Select your Division']) }}
                                </div>
                                <div class="candidate-address-field candidate-permanent-district-field {{ $permanentAddressType === 'outside' ? 'd-none' : '' }}">
                                    {{ Form::label('permanent_city_id', 'District', ['class' => 'form-label']) }}
                                    {{ Form::select('permanent_city_id', $permanentCities, $candidate->permanent_city_id ?? null, ['id' => 'permanentCityId', 'class' => 'form-select', 'placeholder' => 'Select your District']) }}
                                </div>
                                <div class="candidate-address-field candidate-permanent-district-field {{ $permanentAddressType === 'outside' ? 'd-none' : '' }}">
                                    {{ Form::label('permanent_thana_id', 'Thana', ['class' => 'form-label']) }}
                                    {{ Form::select('permanent_thana_id', $permanentThanas, $candidate->permanent_thana_id ?? null, ['id' => 'permanentThanaId', 'class' => 'form-select', 'placeholder' => 'Select Thana']) }}
                                </div>
                                <div class="candidate-address-field candidate-permanent-state-text-field {{ $permanentAddressType === 'outside' ? '' : 'd-none' }}">
                                    {{ Form::label('permanent_state_division', 'State/Division', ['class' => 'form-label']) }}
                                    {{ Form::text('permanent_state_division', $candidate->permanent_state_division ?? null, ['class' => 'form-control', 'placeholder' => 'Enter your State/Division']) }}
                                </div>
                                <div class="candidate-address-field candidate-permanent-thana-po-field {{ $permanentAddressType === 'outside' ? 'd-none' : '' }}">
                                    {{ Form::label('permanent_post_office', 'Post Office', ['class' => 'form-label']) }}
                                    {{ Form::text('permanent_post_office', $candidate->permanent_post_office ?? null, ['class' => 'form-control', 'placeholder' => 'Enter your Post Office']) }}
                                </div>
                                <div class="candidate-address-field candidate-address-field--full">
                                    {{ Form::label('permanent_address', 'House No/Road/Village', ['class' => 'form-label']) }}
                                    {{ Form::textarea('permanent_address', $candidate->permanent_address ?? null, ['class' => 'form-control candidate-address-textarea', 'rows' => 3, 'placeholder' => 'Enter your House No/Road/Village']) }}
                                </div>
                            </div>
                        </div>
                        <div class="candidate-profile-section-actions candidate-address-actions">
                            {{ Form::submit(__('messages.common.save'), ['class' => 'candidate-skill-save', 'formaction' => route('candidate-profile.address-details.update'), 'formnovalidate' => true, 'data-scoped-ajax-submit' => true]) }}
                            <button type="button" class="candidate-skill-close" data-address-edit-close>{{ __('messages.common.close') }}</button>
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
                            aria-controls="candidateCareerApplication"
                            data-collapse-label="{{ __('messages.candidate_profile.collapse') }}"
                            data-expand-label="{{ __('messages.candidate_profile.expand') }}">
                        <span>{{ __('messages.candidate_profile.expand') }}</span>
                        <i class="fa-solid fa-chevron-up"></i>
                    </button>
                </span>
            </div>
            <div id="candidateCareerApplication" class="collapse candidate-profile-section__collapse"
                 data-bs-parent="#candidateProfileAccordion">
                <div class="candidate-profile-section__body">
                    @php
                        $defaultObjective = __('messages.candidate_profile.objective_default');
                        $selectedJobLevel = $candidate->job_level ?? null;
                        $selectedJobNature = $candidate->job_nature ?? null;
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
                            <strong>{{ $careerDisplayValue($candidate->objective ?? null) }}</strong>
                        </div>
                        <div class="candidate-career-summary-grid">
                            <div class="candidate-career-summary-item">
                                <span>{{ __('messages.candidate_profile.present_salary') }} ({{ __('messages.candidate_profile.taka_month') }})</span>
                                <strong>{{ $careerFormatSalary($candidate->current_salary ?? null) }}</strong>
                            </div>
                            <div class="candidate-career-summary-item">
                                <span>{{ __('messages.candidate_profile.expected_salary') }} ({{ __('messages.candidate_profile.taka_month') }})</span>
                                <strong>{{ $careerFormatSalary($candidate->expected_salary ?? null) }}</strong>
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
                        <div class="col-12 ">
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
                            {{ Form::textarea('objective', $candidate->objective ?? null, ['class' => 'form-control candidate-objective-textarea', 'rows' => 5, 'placeholder' => $defaultObjective]) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 ">
                            {{ Form::label('current_salary', __('messages.candidate_profile.present_salary'), ['class' => 'form-label']) }}
                            <span class="candidate-field-note">({{ __('messages.candidate_profile.taka_month') }})</span>
                            {{ Form::text('current_salary', $candidate->current_salary ?? null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_current_salary')]) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 ">
                            {{ Form::label('expected_salary', __('messages.candidate_profile.expected_salary'), ['class' => 'form-label']) }}
                            <span class="candidate-field-note">({{ __('messages.candidate_profile.taka_month') }})</span>
                            {{ Form::text('expected_salary', $candidate->expected_salary ?? null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_expected_salary')]) }}
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 ">
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
                        <div class="col-xl-6 col-md-6 col-sm-12 ">
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
                                {{ Form::submit(__('messages.common.save'), ['class' => 'candidate-skill-save', 'formaction' => route('candidate-profile.career-application.update'), 'formnovalidate' => true, 'data-scoped-ajax-submit' => true]) }}
                                <button type="button" class="candidate-skill-close" data-career-edit-close>{{ __('messages.common.close') }}</button>
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
                            aria-controls="candidatePreferredArea"
                            data-collapse-label="{{ __('messages.candidate_profile.collapse') }}"
                            data-expand-label="{{ __('messages.candidate_profile.expand') }}">
                        <span>{{ __('messages.candidate_profile.expand') }}</span>
                        <i class="fa-solid fa-chevron-up"></i>
                    </button>
                </span>
            </div>
            <div id="candidatePreferredArea" class="collapse candidate-profile-section__collapse"
                 data-bs-parent="#candidateProfileAccordion">
                <div class="candidate-profile-section__body">
                    @php
                        $functionalOptions = collect($data['functionalArea'] ?? []);
                        $specialSkillOptions = collect($data['skills'] ?? []);
                        $districtOptions = collect($data['districts'] ?? []);
                        $countryOptions = collect($data['outsideCountries'] ?? []);
                        $organizationOptions = collect($data['organizationTypes'] ?? []);

                        $preferredFunctional = collect($candidate->preferred_functional_categories ?? [])->map(fn ($id) => (string) $id)->toArray();
                        $preferredSkills = collect($candidate->preferred_special_skills ?? [])->map(fn ($id) => (string) $id)->toArray();
                        $preferredInside = collect($candidate->preferred_job_locations_inside ?? [])->map(fn ($id) => (string) $id)->toArray();
                        $preferredOutside = collect($candidate->preferred_job_locations_outside ?? [])->map(fn ($id) => (string) $id)->toArray();
                        $preferredOrganizations = collect($candidate->preferred_organization_types ?? [])->map(fn ($id) => (string) $id)->toArray();

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
                            {{ Form::submit(__('messages.common.save'), ['class' => 'candidate-skill-save', 'formaction' => route('candidate-profile.preferred-area.update'), 'formnovalidate' => true, 'data-scoped-ajax-submit' => true]) }}
                            <button type="button" class="candidate-skill-close" data-preferred-edit-close>{{ __('messages.common.close') }}</button>
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
                            aria-controls="candidateRelevantInformation"
                            data-collapse-label="{{ __('messages.candidate_profile.collapse') }}"
                            data-expand-label="{{ __('messages.candidate_profile.expand') }}">
                        <span>{{ __('messages.candidate_profile.expand') }}</span>
                        <i class="fa-solid fa-chevron-up"></i>
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
                        $careerSummaryValue = $candidate->career_summary ?? null;
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
                            <strong>{!! nl2br(e($relevantDisplayValue($candidate->special_qualification ?? null))) !!}</strong>
                        </div>
                        <div class="candidate-relevant-summary-item">
                            <span>{{ __('messages.candidate_profile.keywords') }}</span>
                            <strong>{{ $relevantDisplayValue($candidate->keywords ?? null) }}</strong>
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
                                {{ Form::textarea('career_summary', $candidate->career_summary ?? null, ['class' => 'd-none', 'data-relevant-quill-input' => true]) }}
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
                            {{ Form::textarea('special_qualification', $candidate->special_qualification ?? null, ['class' => 'form-control candidate-relevant-textarea', 'rows' => 4, 'placeholder' => __('messages.candidate_profile.special_qualification_default')]) }}
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
                            {{ Form::textarea('keywords', $candidate->keywords ?? null, ['class' => 'form-control candidate-relevant-textarea', 'rows' => 4, 'placeholder' => __('messages.candidate_profile.keywords_default'), 'required' => true]) }}
                        </div>

                        <div class="candidate-profile-section-actions">
                            {{ Form::submit(__('messages.common.save'), ['class' => 'candidate-skill-save', 'formaction' => route('candidate-profile.relevant-information.update'), 'formnovalidate' => true, 'data-scoped-ajax-submit' => true]) }}
                            <button type="button" class="candidate-skill-close" data-relevant-edit-close>{{ __('messages.common.close') }}</button>
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
                            aria-controls="candidateDisabilityInformation"
                            data-collapse-label="{{ __('messages.candidate_profile.collapse') }}"
                            data-expand-label="{{ __('messages.candidate_profile.expand') }}">
                        <span>{{ __('messages.candidate_profile.expand') }}</span>
                        <i class="fa-solid fa-chevron-up"></i>
                    </button>
                </span>
            </div>
            <div id="candidateDisabilityInformation" class="collapse candidate-profile-section__collapse"
                 data-bs-parent="#candidateProfileAccordion">
                <div class="candidate-profile-section__body">
                    @php
                        $hasDisabilityId = $candidate->has_disability_id ?? null;
                        $showDisabilityDetails = (string) $hasDisabilityId === '1';
                        $disabilityShowOnProfile = $candidate->disability_id_show_on_profile ?? true;
                        $difficultyOptions = ['' => __('messages.candidate_profile.select_your_difficulty')]
                            + ($profileReferenceOptions['disability_difficulty'] ?? []);
                        $difficultySummaryOptions = $profileReferenceOptions['disability_difficulty'] ?? [];
                        $disabilitySummaryItems = [
                            [
                                'label' => __('messages.candidate_profile.national_disability_id'),
                                'value' => $candidate->disability_id_number ?? null,
                            ],
                            [
                                'label' => __('messages.candidate_profile.show_on_profile'),
                                'value' => $candidate->disability_id_show_on_profile === null ? null : ($candidate->disability_id_show_on_profile ? __('messages.common.yes') : __('messages.common.no')),
                            ],
                            [
                                'label' => __('messages.candidate_profile.difficulty_to_see'),
                                'value' => $difficultySummaryOptions[$candidate->disability_difficulty_seeing ?? null] ?? null,
                            ],
                            [
                                'label' => __('messages.candidate_profile.difficulty_to_hear'),
                                'value' => $difficultySummaryOptions[$candidate->disability_difficulty_hearing ?? null] ?? null,
                            ],
                            [
                                'label' => __('messages.candidate_profile.difficulty_to_concentrate_or_remember'),
                                'value' => $difficultySummaryOptions[$candidate->disability_difficulty_remembering ?? null] ?? null,
                            ],
                            [
                                'label' => __('messages.candidate_profile.difficulty_to_sit_stand_walk'),
                                'value' => $difficultySummaryOptions[$candidate->disability_difficulty_walking ?? null] ?? null,
                            ],
                            [
                                'label' => __('messages.candidate_profile.difficulty_to_communicate'),
                                'value' => $difficultySummaryOptions[$candidate->disability_difficulty_communicating ?? null] ?? null,
                            ],
                            [
                                'label' => __('messages.candidate_profile.difficulty_of_taking_care'),
                                'value' => $difficultySummaryOptions[$candidate->disability_difficulty_self_care ?? null] ?? null,
                            ],
                        ];
                        $hasDisabilitySummary = $showDisabilityDetails && collect($disabilitySummaryItems)->contains(fn ($item) => filled($item['value']));
                    @endphp
                    <div class="candidate-disability-summary">
                        @if($hasDisabilitySummary)
                            <div class="candidate-disability-summary-grid">
                                @foreach($disabilitySummaryItems as $summaryItem)
                                    <div class="candidate-disability-summary-item">
                                        <span>{{ $summaryItem['label'] }}</span>
                                        <strong>{{ filled($summaryItem['value']) ? $summaryItem['value'] : '---' }}</strong>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p>
                                {{ __('messages.candidate_profile.have_disability_id_number') }}
                                <strong>
                                    @if($hasDisabilityId === null)
                                        {{ __('messages.candidate_profile.disability_id_not_mentioned') }}
                                    @else
                                        {{ $hasDisabilityId ? __('messages.common.yes') : __('messages.common.no') }}
                                    @endif
                                </strong>
                            </p>
                            <p>
                                {{ __('messages.candidate_profile.disability_support_prefix') }}
                                {{-- <a href="tel:+8801730369802">{{ __('messages.candidate_profile.disability_support_contact') }}</a> --}}
                                {{ __('messages.candidate_profile.disability_support_suffix') }}
                            </p>
                        @endif
                    </div>
                    <div class="candidate-disability-area candidate-disability-form d-none">
                        <div class="candidate-disability-question">
                            <span>{{ __('messages.candidate_profile.have_disability_id_number') }}</span><span class="required"></span>
                        </div>
                        <div class="candidate-disability-options">
                            <label class="candidate-career-radio">
                                {{ Form::radio('has_disability_id', '1', $showDisabilityDetails, ['class' => 'form-check-input', 'data-disability-toggle' => true]) }}
                                <span>{{ __('messages.common.yes') }}</span>
                            </label>
                            <label class="candidate-career-radio">
                                {{ Form::radio('has_disability_id', '0', isset($candidate->has_disability_id) ? $candidate->has_disability_id == 0 : false, ['class' => 'form-check-input', 'data-disability-toggle' => true]) }}
                                <span>{{ __('messages.common.no') }}</span>
                            </label>
                        </div>
                        <div class="candidate-disability-details {{ $showDisabilityDetails ? '' : 'd-none' }}" data-disability-details>
                            <div class="candidate-disability-grid">
                                <div class="candidate-disability-field">
                                    {{ Form::label('disability_id_number', __('messages.candidate_profile.national_id_number'), ['class' => 'form-label required']) }}
                                    {{ Form::text('disability_id_number', $candidate->disability_id_number ?? null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_national_id_number'), 'data-disability-detail-input' => true]) }}
                                </div>
                                <div class="candidate-disability-field">
                                    {{ Form::label('disability_id_show_on_profile', __('messages.candidate_profile.show_on_profile'), ['class' => 'form-label']) }}
                                    <div class="candidate-disability-segmented">
                                        <label>
                                            {{ Form::radio('disability_id_show_on_profile', '1', (bool) $disabilityShowOnProfile, ['data-disability-detail-input' => true]) }}
                                            <span>{{ __('messages.common.yes') }}</span>
                                        </label>
                                        <label>
                                            {{ Form::radio('disability_id_show_on_profile', '0', ! (bool) $disabilityShowOnProfile, ['data-disability-detail-input' => true]) }}
                                            <span>{{ __('messages.common.no') }}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="candidate-disability-field">
                                    {{ Form::label('disability_difficulty_seeing', __('messages.candidate_profile.difficulty_to_see'), ['class' => 'form-label']) }}
                                    {{ Form::select('disability_difficulty_seeing', $difficultyOptions, $candidate->disability_difficulty_seeing ?? null, ['class' => 'form-select', 'data-disability-detail-input' => true]) }}
                                </div>
                                <div class="candidate-disability-field">
                                    {{ Form::label('disability_difficulty_hearing', __('messages.candidate_profile.difficulty_to_hear'), ['class' => 'form-label']) }}
                                    {{ Form::select('disability_difficulty_hearing', $difficultyOptions, $candidate->disability_difficulty_hearing ?? null, ['class' => 'form-select', 'data-disability-detail-input' => true]) }}
                                </div>
                                <div class="candidate-disability-field">
                                    {{ Form::label('disability_difficulty_remembering', __('messages.candidate_profile.difficulty_to_concentrate_or_remember'), ['class' => 'form-label']) }}
                                    {{ Form::select('disability_difficulty_remembering', $difficultyOptions, $candidate->disability_difficulty_remembering ?? null, ['class' => 'form-select', 'data-disability-detail-input' => true]) }}
                                </div>
                                <div class="candidate-disability-field">
                                    {{ Form::label('disability_difficulty_walking', __('messages.candidate_profile.difficulty_to_sit_stand_walk'), ['class' => 'form-label']) }}
                                    {{ Form::select('disability_difficulty_walking', $difficultyOptions, $candidate->disability_difficulty_walking ?? null, ['class' => 'form-select', 'data-disability-detail-input' => true]) }}
                                </div>
                                <div class="candidate-disability-field">
                                    {{ Form::label('disability_difficulty_communicating', __('messages.candidate_profile.difficulty_to_communicate'), ['class' => 'form-label']) }}
                                    {{ Form::select('disability_difficulty_communicating', $difficultyOptions, $candidate->disability_difficulty_communicating ?? null, ['class' => 'form-select', 'data-disability-detail-input' => true]) }}
                                </div>
                                <div class="candidate-disability-field">
                                    {{ Form::label('disability_difficulty_self_care', __('messages.candidate_profile.difficulty_of_taking_care'), ['class' => 'form-label']) }}
                                    {{ Form::select('disability_difficulty_self_care', $difficultyOptions, $candidate->disability_difficulty_self_care ?? null, ['class' => 'form-select', 'data-disability-detail-input' => true]) }}
                                </div>
                            </div>
                        </div>
                        <p class="candidate-disability-support {{ $showDisabilityDetails ? 'd-none' : '' }}" data-disability-support>
                            {{ __('messages.candidate_profile.disability_support_prefix') }}
                            <a href="tel:+8801730369802">{{ __('messages.candidate_profile.disability_support_contact') }}</a>
                            {{ __('messages.candidate_profile.disability_support_suffix') }}
                        </p>
                        <div class="candidate-profile-section-actions">
                            {{ Form::submit(__('messages.common.save'), ['class' => 'candidate-skill-save', 'formaction' => route('candidate-profile.disability-information.update'), 'formnovalidate' => true, 'data-scoped-ajax-submit' => true]) }}
                            <button type="button" class="candidate-skill-close" data-disability-edit-close>{{ __('messages.common.close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
    {{ Form::close() }}
    <input type="file" id="candidatePersonalImageInput" class="d-none" accept="image/png,image/jpeg,image/jpg">

    <div class="candidate-image-upload-modal d-none" id="candidateImageUploadModal" aria-hidden="true">
        <div class="candidate-image-upload-modal__backdrop" data-candidate-image-modal-close></div>
        <div class="candidate-image-upload-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="candidateImageUploadTitle">
            <button type="button" class="candidate-image-upload-modal__close" data-candidate-image-modal-close aria-label="{{ __('messages.common.close') }}">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="candidate-image-upload-modal__dropzone" data-candidate-image-dropzone>
                <div class="candidate-image-upload-modal__icon">
                    <i class="fa-regular fa-user"></i>
                </div>
                <p id="candidateImageUploadTitle">
                    <button type="button" data-candidate-image-input-trigger>Click here</button>
                    to upload or drop media here.
                </p>
                <span>Upload your Profile image JPG or PNG, 1MB max</span>
                <button type="button" class="candidate-image-upload-modal__upload" data-candidate-image-input-trigger>
                    Upload Image
                </button>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        var phoneNo = "{{ old('region_code') . old('phone') }}";
        var candidateProfileCollapseText = "{{ __('messages.candidate_profile.collapse') }}";
        var candidateProfileExpandText = "{{ __('messages.candidate_profile.expand') }}";

        document.addEventListener('DOMContentLoaded', function () {
            var target = window.location.hash ? document.querySelector(window.location.hash) : null;

            if (target && typeof window.scrollCandidateProfileSection === 'function') {
                window.scrollCandidateProfileSection(target);
            }
        });
    </script>
@endpush
