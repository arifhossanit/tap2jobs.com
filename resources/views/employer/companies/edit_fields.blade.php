{{ Form::hidden('industry_id', $company->industry_id, ['id' => 'primaryIndustryId']) }}

<section class="employer-account-content-panel active" id="companyDetailsPanel">
    <div class="employer-account-logo-row">
        <label class="employer-account-logo-picker" for="employerCompanyLogo">
            <img src="{{ $company->company_url }}" alt="{{ $user->full_name }}">
            <span><i class="fa-solid fa-arrow-up-from-bracket"></i></span>
            {{ Form::file('image', ['id' => 'employerCompanyLogo', 'class' => 'd-none', 'accept' => '.png, .jpg, .jpeg']) }}
        </label>
        <div>
            <strong>{{ $user->full_name }}</strong>
            <span>{{ __('messages.employer_dashboard.upload_company_logo') }}</span>
        </div>
    </div>

    <div class="employer-account-section-title">
        <i class="fa-solid fa-building"></i>
        <span>{{ __('messages.employer_account.company_details_information') }}</span>
    </div>

    <div class="employer-account-form">
        <div class="row">
            <div class="col-xl-4 col-md-6 col-sm-12 mb-5">
                {{ Form::label('name', __('messages.employer_account.company_name'), ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::text('name', isset($user) ? $user->full_name : null, ['class' => 'form-control', 'required', 'placeholder' => __('messages.company.name')]) }}
            </div>
            <div class="col-xl-4 col-md-6 col-sm-12 mb-5">
                {{ Form::label('company_name_bn', __('messages.employer_account.company_name_bn'), ['class' => 'form-label']) }}
                {{ Form::text('company_name_bn', $company->company_name_bn, ['class' => 'form-control', 'placeholder' => __('messages.employer_account.company_name_bn_placeholder')]) }}
            </div>
            <div class="col-xl-4 col-md-6 col-sm-12 mb-5">
                {{ Form::label('established_in', __('messages.employer_account.year_of_establishment'), ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::selectYear('established_in', date('Y'), 2000, isset($company->established_in) ? $company->established_in : '', ['class' => 'form-select', 'data-control' => 'select2', 'id' => 'establishedIn']) }}
            </div>
            <div class="col-12 mb-5">
                <div class="employer-account-field-heading required-heading">{{ __('messages.employer_account.number_of_employees') }}</div>
                @php
                    $legacySize = $data['companySize'][$company->company_size_id] ?? null;
                    $selectedEmployeeRange = in_array($company->employee_range, $data['companySize']->values()->all(), true)
                        ? $company->employee_range
                        : $legacySize;
                @endphp
                <div class="employer-company-size-options">
                    @foreach ($data['companySize'] as $sizeLabel)
                        <label class="employer-choice-card">
                            {{ Form::radio('employee_range', $sizeLabel, $selectedEmployeeRange === $sizeLabel, ['required']) }}
                            <span>{{ $sizeLabel }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="col-12">
                <div class="employer-account-field-heading required-heading">{{ __('messages.employer_account.company_address') }}</div>
            </div>
            <div class="col-xl-3 col-md-6 col-sm-12 mb-5">
                {{ Form::select('country_id', $data['countries'], null, ['id' => 'countryId', 'class' => 'form-select', 'data-control' => 'select2', 'placeholder' => __('messages.company.select_country'), 'required']) }}
            </div>
            <div class="col-xl-3 col-md-6 col-sm-12 mb-5">
                {{ Form::select('state_id', isset($states) && $states != null ? $states : [], null, ['id' => 'stateId', 'class' => 'form-select', 'data-control' => 'select2', 'placeholder' => __('messages.company.select_state'), 'required']) }}
            </div>
            <div class="col-xl-3 col-md-6 col-sm-12 mb-5">
                {{ Form::select('city_id', isset($cities) && $cities != null ? $cities : [], null, ['id' => 'cityId', 'class' => 'form-select', 'data-control' => 'select2', 'placeholder' => __('messages.company.select_city'), 'required']) }}
            </div>
            <div class="col-xl-3 col-md-6 col-sm-12 mb-5">
                {{ Form::select('thana_id', isset($thanas) && $thanas != null ? $thanas : [], old('thana_id', $company->user->thana_id ?? null), ['id' => 'thanaId', 'class' => 'form-select', 'data-control' => 'select2', 'placeholder' => __('messages.company.select_thana')]) }}
            </div>
            <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
                {{ Form::textarea('location', old('location', $company->location ?: $company->company_summary), ['class' => 'form-control employer-company-summary', 'rows' => 3, 'maxlength' => 255, 'required', 'placeholder' => __('messages.employer_register.company_address_en_placeholder')]) }}
            </div>
            <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
                {{ Form::textarea('company_address_bn', old('company_address_bn', $company->company_address_bn ?: $company->company_summary_bn), ['class' => 'form-control employer-company-summary', 'rows' => 3, 'maxlength' => 1000, 'placeholder' => __('messages.employer_register.company_address_bn_placeholder')]) }}
            </div>
            @php
                $selectedIndustryIds = collect($company->industry_ids ?: [$company->industry_id])
                    ->filter()
                    ->map(fn ($id) => (int) $id)
                    ->values();
            @endphp
            <div class="col-12 mb-5">
                <div class="employer-industry-type-row">
                    <button type="button" class="employer-add-industry-trigger" id="employerAddIndustryTrigger"
                            data-bs-toggle="modal" data-bs-target="#employerAddIndustryModal">
                        <i class="fa-solid fa-plus"></i>
                        <span>{{ __('messages.employer_account.add_new_industry') }}</span>
                    </button>
                </div>
            </div>
            <div class="col-12 mb-4">
                <div class="employer-industry-picker">
                    <div class="employer-industry-search-wrap">
                        <input type="search" class="form-control" id="employerIndustrySearch" placeholder="{{ __('messages.employer_account.search_industry') }}">
                        <span class="employer-industry-search-icon" aria-hidden="true">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                    </div>
                    <div class="employer-industry-options" id="employerIndustryOptions">
                        @foreach ($data['industryRecords'] as $industryOption)
                            <label class="employer-industry-option"
                                   data-industry-name="{{ strtolower($industryOption->name) }}">
                                <input type="checkbox" name="industry_ids[]" value="{{ $industryOption->id }}"
                                       {{ $selectedIndustryIds->contains((int) $industryOption->id) ? 'checked' : '' }}>
                                <span>{{ $industryOption->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div class="employer-industry-empty d-none" id="employerIndustryEmpty">{{ __('messages.employer_account.no_industry_found_category') }}</div>
                    <button type="button" class="employer-industry-more d-none" id="employerIndustryMore">{{ __('messages.employer_account.see_more') }}</button>
                </div>
            </div>
            <div class="col-12 mb-5">
                <div class="employer-industry-tags" id="employerIndustryTags">
                    @foreach ($selectedIndustryIds as $selectedIndustryId)
                        @if (isset($data['industries'][$selectedIndustryId]))
                            <span data-industry-id="{{ $selectedIndustryId }}">
                                {{ $data['industries'][$selectedIndustryId] }} <i class="fa-solid fa-xmark"></i>
                            </span>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="col-12 mb-5">
                {{ Form::label('details', __('messages.employer_account.business_description'), ['class' => 'form-label']) }}
                <span class="required"></span>
                <div id="editEmployeeDetails"></div>
                {{ Form::hidden('details', $company->details, ['id' => 'editEmployerDetail']) }}
            </div>
            <div class="col-xl-4 col-md-6 col-sm-12 mb-5 employer-company-final-field">
                {{ Form::label('trade_license_no', __('messages.employer_account.trade_license_no'), ['class' => 'form-label']) }}
                {{ Form::text('trade_license_no', $company->trade_license_no, ['class' => 'form-control', 'placeholder' => __('messages.employer_account.enter_trade_license_no')]) }}
            </div>
            <div class="col-xl-4 col-md-6 col-sm-12 mb-5 employer-company-final-field">
                <label for="rl_no" class="form-label">
                    {{ __('messages.employer_account.rl_no') }} <span class="text-muted fw-normal">({{ __('messages.employer_account.rl_no_only_recruiting_agency') }})</span>
                </label>
                {{ Form::text('rl_no', $company->rl_no, ['class' => 'form-control', 'placeholder' => __('messages.employer_account.enter_number_only'), 'inputmode' => 'numeric', 'pattern' => '[0-9]*', 'oninput' => "this.value = this.value.replace(/\\D/g, '')"]) }}
            </div>
            <div class="col-xl-4 col-md-6 col-sm-12 mb-5 employer-company-final-field">
                {{ Form::label('website', __('messages.employer_account.website_url'), ['class' => 'form-label']) }}
                {{ Form::text('website', isset($company) ? $company->website : null, ['class' => 'form-control', 'placeholder' => __('messages.company.website')]) }}
            </div>
        </div>
    </div>
</section>

<section class="employer-account-content-panel" id="contactDetailsPanel">
    <div class="employer-account-section-title">
        <i class="fa-solid fa-phone"></i>
        <span>{{ __('messages.company.contact_details') }}</span>
    </div>

    <div class="employer-account-form">
        <h3 class="employer-account-section-subtitle">{{ __('messages.employer_account.primary_contact') }}</h3>
        <div class="row employer-primary-contact-grid">
            <div class="col-md-6 col-sm-12">
                <label for="employerContactPerson" class="form-label">
                    {{ __('messages.employer_account.contact_person_name') }} <span class="text-danger">*</span>
                </label>
                <input type="text" name="contact_person_name" id="employerContactPerson"
                       class="form-control employer-contact-readonly" maxlength="180" required readonly
                       value="{{ old('contact_person_name', $company->contact_person_name ?: $user->full_name) }}"
                       placeholder="{{ __('messages.employer_register.contact_person_name_placeholder') }}">
            </div>
            <div class="col-md-6 col-sm-12">
                <label for="employerContactDesignation" class="form-label">
                    {{ __('messages.employer_account.contact_person_designation') }} <span class="text-danger">*</span>
                </label>
                <input type="text" name="ceo" id="employerContactDesignation"
                       class="form-control employer-contact-readonly"
                       value="{{ $company->ceo ?: $user->full_name }}" readonly>
            </div>
            <div class="col-md-6 col-sm-12">
                <label for="email" class="form-label">
                    {{ __('messages.employer_account.contact_person_email') }} <span class="text-danger">*</span>
                </label>
                {{ Form::email('email', null, ['class' => 'form-control employer-contact-readonly', 'required', 'readonly']) }}
            </div>
            <div class="col-md-6 col-sm-12 mobile-itel-width employer-contact-mobile">
                <label for="phoneNumber" class="form-label">
                    {{ __('messages.employer_account.contact_person_mobile') }} <span class="text-danger">*</span>
                </label>
                {{ Form::tel('phone', null, ['class' => 'form-control employer-contact-readonly', 'required', 'readonly', 'id' => 'phoneNumber', 'maxlength' => 11, 'inputmode' => 'numeric', 'pattern' => '[0-9]{1,11}', 'oninput' => "this.value = this.value.replace(/\\D/g, '').slice(0, 11)"]) }}
                {{ Form::tel('phone', null, ['class' => 'form-control employer-contact-readonly', 'required', 'readonly', 'id' => 'phoneNumber', 'maxlength' => 11, 'inputmode' => 'numeric', 'pattern' => '[0-9]{1,11}', 'oninput' => "this.value = this.value.replace(/\\D/g, '').slice(0, 11)"]) }}
                {{ Form::hidden('region_code', null, ['id' => 'prefix_code']) }}
                <span id="valid-msg" class="d-none text-success d-block fw-400 fs-small mt-2">{{ __('messages.phone.valid_number') }}</span>
                <span id="error-msg" class="d-none text-danger d-block fw-400 fs-small mt-2"></span>
            </div>
            <div class="col-12">
                <button type="button" class="employer-contact-action" id="employerEditContactPersonButton">
                    {{ __('messages.employer_account.add_edit_contact_person') }}
                </button>
            </div>
        </div>
    </div>
</section>

<section class="employer-account-content-panel" id="billingAddressPanel">
    <div class="employer-account-section-title">
        <i class="fa-regular fa-file-lines"></i>
        <span>{{ __('messages.employer_account.billing_address') }}</span>
    </div>

    <div class="employer-account-form employer-billing-form">
        <div class="row employer-billing-grid">
            <div class="col-md-6 col-sm-12">
                <label for="billing_address" class="form-label">{{ __('messages.employer_account.billing_address') }} <span class="text-danger">*</span></label>
                {{ Form::text('billing_address', old('billing_address', $company->billing_address ?: $company->location), ['class' => 'form-control', 'required', 'maxlength' => 255, 'placeholder' => __('messages.employer_account.enter_billing_address')]) }}
            </div>
            <div class="col-md-6 col-sm-12 employer-billing-mobile">
                <label for="billingPhoneNumber" class="form-label">{{ __('messages.employer_account.billing_contact_number') }}<span class="text-danger">*</span></label>
                <input type="tel" name="billing_phone" id="billingPhoneNumber" class="form-control" required
                       maxlength="11" inputmode="numeric" pattern="[0-9]{1,11}"
                       value="{{ old('billing_phone', $company->billing_phone ?: $user->phone) }}">
                {{ Form::hidden('billing_region_code', $company->billing_region_code ?: $user->region_code ?: '880', ['id' => 'billingPrefixCode']) }}
                <span id="billing-phone-error" class="d-none text-danger d-block fw-400 fs-small mt-2"></span>
            </div>
            <div class="col-12">
                <label for="billing_email" class="form-label">{{ __('messages.employer_account.billing_contact_email') }} <span class="text-danger">*</span></label>
                <input type="email" name="billing_email" id="billing_email" class="form-control" required
                       value="{{ old('billing_email', $company->billing_email ?: $user->email) }}">
            </div>
        </div>

        <div class="employer-billing-divider"></div>

        <div class="employer-facilities-question">
            @php
                $hasDisabilityFacilities = (int) old('has_disability_facilities', (int) $company->has_disability_facilities);
                $selectedDisabilityFacilities = collect(old('disability_facilities', $company->disability_facilities ?: []));
                $disabilityFacilityOptions = [
                    'accessible_documentation' => __('messages.employer_register.facilities.accessible_documentation'),
                    'accessible_washrooms' => __('messages.employer_register.facilities.accessible_washrooms'),
                    'adapted_transport' => __('messages.employer_register.facilities.adapted_transport'),
                    'assistive_software' => __('messages.employer_register.facilities.assistive_software'),
                    'flexible_shifts' => __('messages.employer_register.facilities.flexible_shifts'),
                    'work_from_home' => __('messages.employer_register.facilities.work_from_home'),
                    'ramps_lifts' => __('messages.employer_register.facilities.ramps_lifts'),
                    'reasonable_accommodation' => __('messages.employer_register.facilities.reasonable_accommodation'),
                    'warning_indicators' => __('messages.employer_register.facilities.warning_indicators'),
                    'workstation_adaptations' => __('messages.employer_register.facilities.workstation_adaptations'),
                ];
            @endphp
            <div class="employer-facilities-question__title">
                <span>{{ __('messages.employer_account.has_disability_facilities_question') }}</span>
                <a href="javascript:void(0)" aria-label="{{ __('messages.employer_account.learn_more_about_disability') }}">{{ __('messages.employer_account.learn_more') }}</a>
            </div>
            <div class="employer-facilities-options">
                @foreach ([1 => [__('messages.employer_account.yes'), __('messages.employer_account.facilities_text')], 0 => [__('messages.employer_account.no'), __('messages.employer_account.facilities_text')]] as $facilityValue => [$facilityTitle, $facilityText])
                    <label class="employer-facility-card">
                        {{ Form::radio('has_disability_facilities', $facilityValue, $hasDisabilityFacilities === $facilityValue, ['required', 'data-facilities-toggle' => true]) }}
                        <strong>{{ $facilityTitle }}</strong>
                        <span>{{ $facilityText }}</span>
                    </label>
                @endforeach
            </div>

            <div class="employer-disability-details {{ $hasDisabilityFacilities === 1 ? '' : 'd-none' }}" id="employerDisabilityDetails">
                <div class="employer-disability-overview">
                    <div class="employer-disability-questions">
                        <fieldset>
                            <legend>{{ __('messages.employer_account.disability_inclusion_policy') }}</legend>
                            <label>{{ Form::radio('disability_inclusion_policy', 1, (int) old('disability_inclusion_policy', $company->disability_inclusion_policy) === 1) }} {{ __('messages.employer_account.yes') }}</label>
                            <label>{{ Form::radio('disability_inclusion_policy', 0, old('disability_inclusion_policy', $company->disability_inclusion_policy) !== null && (int) old('disability_inclusion_policy', $company->disability_inclusion_policy) === 0) }} {{ __('messages.employer_account.no') }}</label>
                        </fieldset>
                        <fieldset id="employerDisabilitySupportQuestion"
                                  class="{{ old('disability_inclusion_policy', $company->disability_inclusion_policy) !== null && (int) old('disability_inclusion_policy', $company->disability_inclusion_policy) === 0 ? '' : 'd-none' }}">
                            <legend>{{ __('messages.employer_account.disability_support') }}</legend>
                            <label>{{ Form::radio('disability_inclusion_support', 1, (int) old('disability_inclusion_support', $company->disability_inclusion_support) === 1) }} {{ __('messages.employer_account.yes') }}</label>
                            <label>{{ Form::radio('disability_inclusion_support', 0, old('disability_inclusion_support', $company->disability_inclusion_support) !== null && (int) old('disability_inclusion_support', $company->disability_inclusion_support) === 0) }} {{ __('messages.employer_account.no') }}</label>
                        </fieldset>
                        <fieldset>
                            <legend>{{ __('messages.employer_account.disability_training') }}</legend>
                            <label>{{ Form::radio('disability_inclusion_training', 1, (int) old('disability_inclusion_training', $company->disability_inclusion_training) === 1) }} {{ __('messages.employer_account.yes') }}</label>
                            <label>{{ Form::radio('disability_inclusion_training', 0, old('disability_inclusion_training', $company->disability_inclusion_training) !== null && (int) old('disability_inclusion_training', $company->disability_inclusion_training) === 0) }} {{ __('messages.employer_account.no') }}</label>
                        </fieldset>
                    </div>
                    <div class="employer-disability-illustration" aria-hidden="true">
                        <img src="{{ asset('assets/img/disability.svg') }}" alt="">
                    </div>
                </div>

                <div class="employer-disability-checklist">
                    <h3>{{ __('messages.employer_account.disability_facilities_question') }}</h3>
                    <div class="employer-disability-checklist__grid">
                        @foreach ($disabilityFacilityOptions as $facilityKey => $facilityLabel)
                            <label>
                                {{ Form::checkbox('disability_facilities[]', $facilityKey, $selectedDisabilityFacilities->contains($facilityKey)) }}
                                <span>{{ $facilityLabel }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="employer-account-actions">
    {{ Form::button(__('messages.employer_account.save_changes'), [
        'type' => 'submit',
        'class' => 'btn employer-save-changes',
        'id' => 'employerSaveChanges',
        'data-loading-text' => "<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span> ".__('messages.common.saving'),
    ]) }}
</div>
