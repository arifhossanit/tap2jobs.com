{{ Form::hidden('user_id', $user->id) }}
{{ Form::hidden('ownership_type_id', $company->ownership_type_id) }}
{{ Form::hidden('no_of_offices', $company->no_of_offices) }}
{{ Form::hidden('company_size_id', $company->company_size_id) }}
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
        <span>{{ __('messages.company.company_details') }} Information</span>
    </div>

    <div class="employer-account-form">
        <div class="row">
            <div class="col-xl-4 col-md-6 col-sm-12 mb-5">
                {{ Form::label('name', 'Company Name', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::text('name', isset($user) ? $user->full_name : null, ['class' => 'form-control', 'required', 'placeholder' => __('messages.company.name')]) }}
            </div>
            <div class="col-xl-4 col-md-6 col-sm-12 mb-5">
                {{ Form::label('company_name_bn', 'কোম্পানির নাম (বাংলায়)', ['class' => 'form-label']) }}
                {{ Form::text('company_name_bn', $company->company_name_bn, ['class' => 'form-control', 'placeholder' => 'কোম্পানির নাম বাংলায় লিখুন']) }}
            </div>
            <div class="col-xl-4 col-md-6 col-sm-12 mb-5">
                {{ Form::label('established_in', 'Year of Establishment', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::selectYear('established_in', date('Y'), 2000, isset($company->established_in) ? $company->established_in : '', ['class' => 'form-select', 'data-control' => 'select2', 'id' => 'establishedIn']) }}
            </div>
            <div class="col-12 mb-5">
                <div class="employer-account-field-heading required-heading">Number of Employees</div>
                @php
                    $employeeSizeOrder = ['1-25', '26-50', '51-100', '101-500', '501-1000', '1000+'];
                    $legacySize = $data['companySize'][$company->company_size_id] ?? null;
                    $selectedEmployeeRange = $company->employee_range ?? match ($legacySize) {
                        '5-10', '11-20' => '1-25',
                        '21-50' => '26-50',
                        '51-100' => '51-100',
                        default => null,
                    };
                @endphp
                <div class="employer-company-size-options">
                    @foreach ($employeeSizeOrder as $sizeLabel)
                        <label class="employer-choice-card">
                            {{ Form::radio('employee_range', $sizeLabel, $selectedEmployeeRange === $sizeLabel, ['required']) }}
                            <span>{{ $sizeLabel }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="col-12">
                <div class="employer-account-field-heading required-heading">Company Address</div>
            </div>
            <div class="col-xl-4 col-md-6 col-sm-12 mb-5">
                {{ Form::select('country_id', $data['countries'], null, ['id' => 'countryId', 'class' => 'form-select', 'data-control' => 'select2', 'placeholder' => __('messages.company.select_country')]) }}
            </div>
            <div class="col-xl-4 col-md-6 col-sm-12 mb-5">
                {{ Form::select('state_id', isset($states) && $states != null ? $states : [], null, ['id' => 'stateId', 'class' => 'form-select', 'data-control' => 'select2', 'placeholder' => __('messages.company.select_state')]) }}
            </div>
            <div class="col-xl-4 col-md-6 col-sm-12 mb-5">
                {{ Form::select('city_id', isset($cities) && $cities != null ? $cities : [], null, ['id' => 'cityId', 'class' => 'form-select', 'data-control' => 'select2', 'placeholder' => __('messages.company.select_city')]) }}
            </div>
            <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
                {{ Form::textarea('company_summary', $company->company_summary, ['class' => 'form-control employer-company-summary', 'rows' => 3, 'placeholder' => 'Write a short company summary']) }}
            </div>
            <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
                {{ Form::textarea('company_summary_bn', $company->company_summary_bn, ['class' => 'form-control employer-company-summary', 'rows' => 3, 'placeholder' => 'কোম্পানির সংক্ষিপ্ত বিবরণ বাংলায় লিখুন']) }}
            </div>
            @php
                $selectedIndustryIds = collect($company->industry_ids ?: [$company->industry_id])
                    ->filter()
                    ->map(fn ($id) => (int) $id)
                    ->values();
                $industryTypeOptions = ['all' => 'All'] + $data['industryTypes']->toArray();
            @endphp
            <div class="col-12 mb-5">
                <div class="employer-industry-type-row">
                    <div class="employer-industry-type-select">
                        {{ Form::label('industry_filter', __('messages.company.industry').' Type', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::select('industry_filter', $industryTypeOptions, 'all', ['class' => 'form-select', 'id' => 'employerIndustryType']) }}
                    </div>
                    <button type="button" class="employer-add-industry-trigger" id="employerAddIndustryTrigger"
                            data-bs-toggle="modal" data-bs-target="#employerAddIndustryModal">
                        <i class="fa-solid fa-plus"></i>
                        <span>Add a New Industry</span>
                    </button>
                </div>
            </div>
            <div class="col-12 mb-4">
                <div class="employer-industry-picker">
                    <div class="employer-industry-search-wrap">
                        <input type="search" class="form-control" id="employerIndustrySearch" placeholder="Search Industry">
                        <span class="employer-industry-search-icon" aria-hidden="true">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                    </div>
                    <div class="employer-industry-options" id="employerIndustryOptions">
                        @foreach ($data['industryRecords'] as $industryOption)
                            <label class="employer-industry-option"
                                   data-industry-name="{{ strtolower($industryOption->name) }}"
                                   data-industry-type-id="{{ $industryOption->industry_type_id }}">
                                <input type="checkbox" name="industry_ids[]" value="{{ $industryOption->id }}"
                                       {{ $selectedIndustryIds->contains((int) $industryOption->id) ? 'checked' : '' }}>
                                <span>{{ $industryOption->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div class="employer-industry-empty d-none" id="employerIndustryEmpty">No industry found in this category.</div>
                    <button type="button" class="employer-industry-more d-none" id="employerIndustryMore">See more</button>
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
                {{ Form::label('details', 'Business Description', ['class' => 'form-label']) }}
                <span class="required"></span>
                <div id="editEmployeeDetails"></div>
                {{ Form::hidden('details', $company->details, ['id' => 'editEmployerDetail']) }}
            </div>
            <div class="col-xl-4 col-md-6 col-sm-12 mb-5 employer-company-final-field">
                {{ Form::label('trade_license_no', 'Business/ Trade License No', ['class' => 'form-label']) }}
                {{ Form::text('trade_license_no', $company->trade_license_no, ['class' => 'form-control', 'placeholder' => 'Enter Trade License No']) }}
            </div>
            <div class="col-xl-4 col-md-6 col-sm-12 mb-5 employer-company-final-field">
                <label for="rl_no" class="form-label">
                    RL No. <span class="text-muted fw-normal">(Only for Recruiting Agency)</span>
                </label>
                {{ Form::text('rl_no', $company->rl_no, ['class' => 'form-control', 'placeholder' => 'Enter Number Only', 'inputmode' => 'numeric']) }}
            </div>
            <div class="col-xl-4 col-md-6 col-sm-12 mb-5 employer-company-final-field">
                {{ Form::label('website', 'Website URL', ['class' => 'form-label']) }}
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
        <h3 class="employer-account-section-subtitle">Primary Contact</h3>
        <div class="row employer-primary-contact-grid">
            <div class="col-md-6 col-sm-12">
                <label for="employerContactPerson" class="form-label">
                    Contact Person's Name <span class="text-danger">*</span>
                </label>
                <select id="employerContactPerson" class="form-select" required>
                    <option value="{{ $user->id }}" selected>{{ $user->full_name }}</option>
                </select>
            </div>
            <div class="col-md-6 col-sm-12">
                <label for="employerContactDesignation" class="form-label">
                    Contact Person's Designation <span class="text-danger">*</span>
                </label>
                <input type="text" name="ceo" id="employerContactDesignation"
                       class="form-control employer-contact-readonly"
                       value="{{ $company->ceo ?: $user->full_name }}" readonly>
            </div>
            <div class="col-md-6 col-sm-12">
                <label for="email" class="form-label">
                    Contact Person's Email <span class="text-danger">*</span>
                </label>
                {{ Form::email('email', null, ['class' => 'form-control employer-contact-readonly', 'required', 'readonly']) }}
            </div>
            <div class="col-md-6 col-sm-12 mobile-itel-width employer-contact-mobile">
                <label for="phoneNumber" class="form-label">
                    Contact Person's Mobile <span class="text-danger">*</span>
                </label>
                {{ Form::tel('phone', null, ['class' => 'form-control employer-contact-readonly', 'required', 'readonly', 'id' => 'phoneNumber']) }}
                {{ Form::hidden('region_code', null, ['id' => 'prefix_code']) }}
                <span id="valid-msg" class="d-none text-success d-block fw-400 fs-small mt-2">{{ __('messages.phone.valid_number') }}</span>
                <span id="error-msg" class="d-none text-danger d-block fw-400 fs-small mt-2"></span>
            </div>
            <div class="col-12">
                <button type="button" class="employer-contact-action" id="employerEditContactPersonButton">
                    Add/Edit Contact Person
                </button>
            </div>
        </div>
    </div>
</section>

<section class="employer-account-content-panel" id="billingAddressPanel">
    <div class="employer-account-section-title">
        <i class="fa-regular fa-file-lines"></i>
        <span>Billing Address</span>
    </div>

    <div class="employer-account-form employer-billing-form">
        <div class="row employer-billing-grid">
            <div class="col-md-6 col-sm-12">
                <label for="location" class="form-label">Billing Address <span class="text-danger">*</span></label>
                {{ Form::text('location', $company->location, ['class' => 'form-control', 'required', 'placeholder' => 'Enter billing address']) }}
            </div>
            <div class="col-md-6 col-sm-12 employer-billing-mobile">
                <label for="billingPhoneNumber" class="form-label">Billing Contact Number<span class="text-danger">*</span></label>
                <input type="tel" name="billing_phone" id="billingPhoneNumber" class="form-control" required
                       value="{{ old('billing_phone', $company->billing_phone ?: $user->phone) }}">
                {{ Form::hidden('billing_region_code', $company->billing_region_code ?: $user->region_code ?: '880', ['id' => 'billingPrefixCode']) }}
                <span id="billing-phone-error" class="d-none text-danger d-block fw-400 fs-small mt-2"></span>
            </div>
            <div class="col-12">
                <label for="billing_email" class="form-label">Billing Contact's Email <span class="text-danger">*</span></label>
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
                    'accessible_documentation' => 'Accessible documentation and alternative formats',
                    'accessible_washrooms' => 'Accessible Washrooms / Toilets',
                    'adapted_transport' => 'Adapted Transport facility for Distant Travelling',
                    'assistive_software' => 'Assistive Software, communication and computer devices',
                    'flexible_shifts' => 'Available Flexible working shifts',
                    'work_from_home' => 'Offering Work from home',
                    'ramps_lifts' => 'Ramps or Lifts or Escalators for entry and move between floors',
                    'reasonable_accommodation' => 'Reasonable Accommodation in Recruitment/interview procedures like sign language, oral/typed/video interview',
                    'warning_indicators' => 'Warning Indicators or Markers in place for hazards, staircase',
                    'workstation_adaptations' => 'Workstation or seating adaptations for easy use',
                ];
            @endphp
            <div class="employer-facilities-question__title">
                <span>Does your company have facilities for person with disabilities?</span>
                <a href="javascript:void(0)" aria-label="Learn more about disability facilities">Learn more</a>
            </div>
            <div class="employer-facilities-options">
                @foreach ([1 => ['Yes', 'Facilities for person with Disabilities here.'], 0 => ['No', 'Facilities for person with Disabilities here.']] as $facilityValue => [$facilityTitle, $facilityText])
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
                            <legend>Do you have Disability Inclusion Policy</legend>
                            <label>{{ Form::radio('disability_inclusion_policy', 1, (int) old('disability_inclusion_policy', $company->disability_inclusion_policy) === 1) }} Yes</label>
                            <label>{{ Form::radio('disability_inclusion_policy', 0, old('disability_inclusion_policy', $company->disability_inclusion_policy) !== null && (int) old('disability_inclusion_policy', $company->disability_inclusion_policy) === 0) }} No</label>
                        </fieldset>
                        <fieldset>
                            <legend>Do you provide Disability Inclusion Training for your Employees?</legend>
                            <label>{{ Form::radio('disability_inclusion_training', 1, (int) old('disability_inclusion_training', $company->disability_inclusion_training) === 1) }} Yes</label>
                            <label>{{ Form::radio('disability_inclusion_training', 0, old('disability_inclusion_training', $company->disability_inclusion_training) !== null && (int) old('disability_inclusion_training', $company->disability_inclusion_training) === 0) }} No</label>
                        </fieldset>
                    </div>
                    <div class="employer-disability-illustration" aria-hidden="true">
                        <i class="fa-solid fa-cloud employer-disability-cloud employer-disability-cloud--one"></i>
                        <i class="fa-solid fa-cloud employer-disability-cloud employer-disability-cloud--two"></i>
                        <i class="fa-solid fa-person"></i>
                        <i class="fa-solid fa-wheelchair-move"></i>
                        <span></span>
                    </div>
                </div>

                <div class="employer-disability-checklist">
                    <h3>As part of Disability Inclusion, what do your company have?</h3>
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
    {{ Form::submit('Save Changes', ['class' => 'btn employer-save-changes']) }}
</div>
