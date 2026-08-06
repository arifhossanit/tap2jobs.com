@extends('front_web.layouts.app')

@section('title')
    {{ __('web.register') }}
@endsection

@section('content')
    <div class="register-page">
        <section class="hero-section position-relative bg-gradient pt-15 pb-40">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-6 text-center mb-lg-0 mb-md-5 mb-sm-4">
                        <div class="hero-content">
                            <h1 class="text-secondary mb-3">
                                {{ __('web.register_menu.employer').' '.__('web.register') }}
                            </h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb justify-content-center mb-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('front.home') }}" class="fs-18 text-gray">@lang('web.home')</a>
                                    </li>
                                    <li class="breadcrumb-item text-primary fs-18" aria-current="page">@lang('web.register')</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </section>

<<<<<<< HEAD
        <!-- start candidate login section -->
        <section class="py-100">
            <div class="p-4">
                @php
                    $registerLeftAds = getActiveAdsByPosition(\App\Models\Ad::POSITION_REGISTER_LEFT);
                    $registerRightAds = getActiveAdsByPosition(\App\Models\Ad::POSITION_REGISTER_RIGHT);
                    $hasRegisterSideAds = $registerLeftAds->isNotEmpty() || $registerRightAds->isNotEmpty();
                @endphp
                <div class="row align-items-start justify-content-center">
                    @if ($hasRegisterSideAds)
                        <div class="col-xl-3 col-lg-3 d-none d-lg-block mb-4">
                            @include('front_web.common.register_side_ad', ['ads' => $registerLeftAds])
                        </div>
                        <div class="col-xl-6 col-lg-6">
                    @else
                        <div class="col-xl-6 col-lg-8 mx-auto">
                    @endif
=======
        <section class="employer-register-content">
            <div class="container">
                <div class="row">
                    <div class="mx-auto">
>>>>>>> f784efedf471acf57cea89d298f2110b9ca9b208
                        @include('flash::message')

                        <form method="POST" action="{{ route('front.save.register') }}" id="addEmployerNewForm"
                              >
                            @csrf
                            <input type="hidden" name="type" value="2">

                            <div class="row">
                                {{-- <div class="col-12 mb-4">
                                    <div class="form-group row">
                                        <div class="col-sm-6 col-12 mb-3 mb-sm-0">
                                            <a href="{{ route('candidate.register') }}" class="btn btn-light-primary d-block">
                                                {{ __('web.register_menu.candidate') }}
                                            </a>
                                        </div>
                                        <div class="col-sm-6 col-12">
                                            <a href="{{ route('employer.register') }}" class="btn btn-primary d-block">
                                                {{ __('web.register_menu.employer') }}
                                            </a>
                                        </div>
                                    </div>
                                </div> --}}

                                <div id="employerValidationErrBox">
                                    @include('layouts.errors')
                                </div>

                                <div class="shadow rounded border" >
                                    <section class="employer-user-information-card">
                                        <h2 class="employer-user-information-title">
                                            <i class="fa-solid fa-user-tie"></i>
                                            <span>{{ __('messages.employer_register.user_information_title') }}</span>
                                        </h2>

                                        <div class="row g-4">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="employerUsername" class="employer-user-information-label">
                                                        {{ __('messages.employer_register.username') }} <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="employer-user-information-input">
                                                        <i class="fa-regular fa-user"></i>
                                                        <input type="text" name="username" id="employerUsername"
                                                               class="form-control" value="{{ old('username') }}"
                                                               maxlength="100" placeholder="{{ __('messages.employer_register.username_placeholder') }}" required>
                                                    </div>
                                                    <div class="employer-live-validation-message" id="employerUsernameFeedback"
                                                         aria-live="polite"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="employerPassword" class="employer-user-information-label">
                                                        {{ __('messages.employer_register.password') }} <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="employer-user-information-input">
                                                        <i class="fa-solid fa-key"></i>
                                                        <input type="password" name="password" id="employerPassword"
                                                               class="form-control" minlength="6" maxlength="20"
                                                               placeholder="{{ __('messages.employer_register.password_placeholder') }}" required
                                                               onkeypress="return avoidSpace(event)">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="employerConfirmPassword" class="employer-user-information-label">
                                                        {{ __('messages.employer_register.confirm_password') }} <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="employer-user-information-input">
                                                        <i class="fa-solid fa-key"></i>
                                                        <input type="password" name="password_confirmation"
                                                               id="employerConfirmPassword" class="form-control"
                                                               minlength="6" maxlength="20" placeholder="{{ __('messages.employer_register.confirm_password_placeholder') }}"
                                                               required onkeypress="return avoidSpace(event)">
                                                    </div>
                                                    <div class="employer-live-validation-message" id="employerConfirmPasswordFeedback"
                                                         aria-live="polite"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>


                                <div class="shadow rounded border mt-4">
                                        <section class="employer-company-information-card">
                                            <h2 class="employer-company-information-title">
                                                <i class="fa-solid fa-building"></i>
                                                <span>{{ __('messages.employer_register.company_information_title') }}</span>
                                            </h2>

                                            <div class="row g-4">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="registerCompanyName" class="employer-company-information-label">
                                                            {{ __('messages.employer_register.company_name') }} <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" name="company_name" id="registerCompanyName"
                                                               class="form-control employer-company-information-control"
                                                               value="{{ old('company_name') }}" maxlength="180"
                                                               placeholder="{{ __('messages.employer_register.company_name_placeholder') }}" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="registerCompanyNameBn" class="employer-company-information-label">
                                                            {{ __('messages.employer_register.company_name_bn') }}
                                                        </label>
                                                        <input type="text" name="company_name_bn" id="registerCompanyNameBn"
                                                               class="form-control employer-company-information-control"
                                                               value="{{ old('company_name_bn') }}" maxlength="180"
                                                               placeholder="{{ __('messages.employer_register.company_name_bn_placeholder') }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="registerEstablishedIn" class="employer-company-information-label">
                                                            {{ __('messages.employer_register.year_of_establishment') }} <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="number" name="established_in" id="registerEstablishedIn"
                                                               class="form-control employer-company-information-control"
                                                               value="{{ old('established_in') }}" min="1800" max="{{ date('Y') }}"
                                                               placeholder="{{ __('messages.employer_register.year_of_establishment_placeholder') }}" required>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="employer-company-information-label">
                                                            {{ __('messages.employer_register.number_of_employees') }} <span class="text-danger">*</span>
                                                        </label>
                                                        <div class="employer-company-employee-options">
                                                            @foreach (['1-25', '26-50', '51-100', '101-500', '501-1000', '1000+'] as $employeeRange)
                                                                <label for="employeeRange{{ $loop->index }}">
                                                                    <input type="radio" name="employee_range"
                                                                           id="employeeRange{{ $loop->index }}"
                                                                           value="{{ $employeeRange }}"
                                                                        {{ old('employee_range') === $employeeRange ? 'checked' : '' }}
                                                                           required>
                                                                    <span>{{ $employeeRange }}</span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="employer-company-information-label">
                                                            {{ __('messages.employer_register.company_address') }} <span class="text-danger">*</span>
                                                        </label>
                                                        <div class="row g-3">
                                                            <div class="col-md-4">
                                                                <div class="employer-company-country-select">
                                                                    <span class="employer-register-bd-flag employer-company-bd-flag"
                                                                          aria-hidden="true"></span>
                                                                    <select name="country_id" id="registerCountryId"
                                                                            class="form-select employer-company-information-control"
                                                                            data-bangladesh-id="{{ $bangladeshId }}" required>
                                                                        @foreach ($countries as $countryId => $countryName)
                                                                            <option value="{{ $countryId }}"
                                                                                {{ (int) old('country_id', $bangladeshId) === (int) $countryId ? 'selected' : '' }}>
                                                                                {{ $countryName }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <select name="state_id" id="registerStateId"
                                                                        class="form-select employer-company-information-control" required>
                                                                    <option value="">{{ __('messages.employer_register.select_district') }}</option>
                                                                    @foreach ($states as $stateId => $stateName)
                                                                        <option value="{{ $stateId }}"
                                                                            {{ (int) old('state_id') === (int) $stateId ? 'selected' : '' }}>
                                                                            {{ $stateName }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <select name="city_id" id="registerCityId"
                                                                        class="form-select employer-company-information-control"
                                                                        data-old-city-id="{{ old('city_id') }}" required disabled>
                                                                    <option value="">{{ __('messages.employer_register.select_thana') }}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <textarea name="company_address"
                                                              class="form-control employer-company-information-control employer-company-address"
                                                              maxlength="255" rows="3"
                                                              placeholder="{{ __('messages.employer_register.company_address_en_placeholder') }}"
                                                              required>{{ old('company_address') }}</textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <textarea name="company_address_bn"
                                                              class="form-control employer-company-information-control employer-company-address"
                                                              maxlength="1000" rows="3"
                                                              placeholder="{{ __('messages.employer_register.company_address_bn_placeholder') }}">{{ old('company_address_bn') }}</textarea>
                                                </div>

                                                <div class="col-12">
                                                    <div class="employer-register-industry-type-row">
                                                        <div class="employer-register-industry-type-select">
                                                            <label for="registerIndustryType" class="employer-company-information-label">
                                                                {{ __('messages.employer_register.industry_type') }} <span class="text-danger">*</span>
                                                            </label>
                                                            <select id="registerIndustryType"
                                                                    class="form-select employer-company-information-control">
                                                                <option value="all" selected>{{ __('messages.employer_register.all') }}</option>
                                                                @foreach ($industryTypes as $industryTypeId => $industryTypeName)
                                                                    <option value="{{ $industryTypeId }}">{{ $industryTypeName }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <button type="button" class="employer-register-add-industry-trigger"
                                                                id="registerAddIndustryTrigger" data-bs-toggle="modal"
                                                                data-bs-target="#registerAddIndustryModal">
                                                            <i class="fa-solid fa-plus"></i>
                                                            <span>{{ __('messages.employer_register.add_new_industry') }}</span>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="employer-company-industry-picker">
                                                        <div class="employer-company-industry-search">
                                                            <input type="search" id="registerIndustrySearch"
                                                                   class="form-control employer-company-information-control"
                                                                   placeholder="{{ __('messages.employer_register.search_industry') }}">
                                                            <i class="fa-solid fa-magnifying-glass"></i>
                                                        </div>

                                                        <div class="employer-register-industry-options employer-company-industry-options"
                                                             id="registerIndustryOptions">
                                                            @foreach ($industryRecords as $industry)
                                                                <label data-industry-name="{{ strtolower($industry->name) }}"
                                                                       data-industry-type-id="{{ $industry->industry_type_id }}">
                                                                    <input type="checkbox" name="industry_ids[]"
                                                                           value="{{ $industry->id }}"
                                                                        {{ collect(old('industry_ids', []))->contains((string) $industry->id) ? 'checked' : '' }}>
                                                                    <span>{{ $industry->name }}</span>
                                                                </label>
                                                            @endforeach
                                                        </div>

                                                        <div class="text-gray d-none" id="registerIndustryEmpty">{{ __('messages.employer_register.no_industry_found') }}</div>
                                                        <button type="button"
                                                                class="btn btn-link p-0 d-none employer-register-see-more"
                                                                id="registerIndustryMore">{{ __('messages.employer_register.see_more') }}</button>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="employer-register-industry-tags" id="registerIndustryTags"></div>
                                                    <div id="registerCustomIndustryInputs"></div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="registerBusinessDescription"
                                                               class="employer-company-information-label">{{ __('messages.employer_register.business_description') }}</label>
                                                        <textarea name="details" id="registerBusinessDescription"
                                                                  class="form-control employer-company-information-control employer-company-description"
                                                                  maxlength="5000" rows="3"
                                                                  placeholder="{{ __('messages.employer_register.business_description_placeholder') }}">{{ old('details') }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="registerTradeLicense"
                                                               class="employer-company-information-label">{{ __('messages.employer_register.trade_license_no') }}</label>
                                                        <input type="text" name="trade_license_no" id="registerTradeLicense"
                                                               class="form-control employer-company-information-control"
                                                               value="{{ old('trade_license_no') }}" maxlength="100"
                                                               placeholder="{{ __('messages.employer_register.trade_license_no_placeholder') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="registerRlNo" class="employer-company-information-label">
                                                            {{ __('messages.employer_register.rl_no') }} <span class="text-muted">({{ __('messages.employer_register.rl_no_only_recruiting_agency') }})</span>
                                                        </label>
                                                        <input type="text" name="rl_no" id="registerRlNo"
                                                               class="form-control employer-company-information-control"
                                                               value="{{ old('rl_no') }}" maxlength="100" inputmode="numeric"
                                                               pattern="[0-9]*"
                                                               oninput="this.value = this.value.replace(/\D/g, '')"
                                                               placeholder="{{ __('messages.employer_register.enter_number_only') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="registerWebsite"
                                                               class="employer-company-information-label">{{ __('messages.employer_register.website_url') }}</label>
                                                        <input type="url" name="website" id="registerWebsite"
                                                               class="form-control employer-company-information-control"
                                                               value="{{ old('website') }}" maxlength="255"
                                                               placeholder="{{ __('messages.employer_register.website_url_placeholder') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    </div>


                                <div class="shadow rounded border mt-4">
                                    <section class="employer-contact-information-card">
                                        <h2 class="employer-contact-information-title">
                                            <i class="fa-solid fa-phone"></i>
                                            <span>{{ __('messages.employer_register.contact_information_title') }}</span>
                                        </h2>

                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="registerContactName" class="employer-contact-information-label">
                                                        {{ __('messages.employer_register.contact_person_name') }} <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" name="contact_person_name" id="registerContactName"
                                                           class="form-control employer-contact-information-control"
                                                           value="{{ old('contact_person_name') }}" maxlength="180"
                                                           placeholder="{{ __('messages.employer_register.contact_person_name_placeholder') }}" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="registerContactDesignation" class="employer-contact-information-label">
                                                        {{ __('messages.employer_register.contact_person_designation') }} <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" name="contact_person_designation"
                                                           id="registerContactDesignation"
                                                           class="form-control employer-contact-information-control"
                                                           value="{{ old('contact_person_designation') }}" maxlength="180"
                                                           placeholder="{{ __('messages.employer_register.contact_person_designation_placeholder') }}" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="employerEmail" class="employer-contact-information-label">
                                                        {{ __('messages.employer_register.contact_person_email') }} <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="email" name="email" id="employerEmail"
                                                           class="form-control employer-contact-information-control"
                                                           value="{{ old('email') }}" maxlength="170"
                                                           placeholder="{{ __('messages.employer_register.contact_person_email_placeholder') }}" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group employer-register-phone-field employer-contact-phone-field">
                                                    <label for="employerRegisterPhone" class="employer-contact-information-label">
                                                        {{ __('messages.employer_register.contact_person_mobile') }} <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="tel" name="phone" id="employerRegisterPhone"
                                                           class="form-control employer-contact-information-control"
                                                           value="{{ old('phone') }}" minlength="4" maxlength="15"
                                                           inputmode="numeric" pattern="[0-9]{4,15}"
                                                           placeholder="{{ __('messages.employer_register.enter_mobile_number') }}" required>
                                                    <input type="hidden" name="region_code" id="employerRegisterRegionCode"
                                                           value="{{ old('region_code', '880') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>

                                <div class="shadow rounded border mt-4">
                                    <section class="employer-accessibility-card">
                                        @php
                                            $registerHasDisabilityFacilities = (int) old('has_disability_facilities', 0) === 1;
                                            $registerSelectedDisabilityFacilities = collect(old('disability_facilities', []));
                                            $registerDisabilityFacilityOptions = [
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
                                        <h2 class="employer-accessibility-title">
                                            <i class="fa-solid fa-universal-access"></i>
                                            <span>{{ __('messages.employer_register.accessibility_title') }}</span>
                                        </h2>

                                        <label class="employer-accessibility-check" for="registerDisabilityFacilities">
                                            <input type="checkbox" name="has_disability_facilities" value="1"
                                                   id="registerDisabilityFacilities" data-register-facilities-toggle
                                                {{ $registerHasDisabilityFacilities ? 'checked' : '' }}>
                                            <span>{{ __('messages.employer_register.enable_facilities') }}</span>
                                        </label>

                                        <a href="javascript:void(0)" class="employer-accessibility-learn-more">{{ __('messages.employer_register.learn_more') }}</a>

                                        <div class="employer-register-disability-details {{ $registerHasDisabilityFacilities ? '' : 'd-none' }}"
                                             id="registerDisabilityDetails">
                                            <div class="employer-register-disability-overview">
                                                <div class="employer-register-disability-questions">
                                                    <fieldset>
                                                        <legend>{{ __('messages.employer_register.disability_inclusion_policy') }}</legend>
                                                        <label>
                                                            <input type="radio" name="disability_inclusion_policy" value="1"
                                                                {{ (int) old('disability_inclusion_policy') === 1 ? 'checked' : '' }}>
                                                            {{ __('messages.employer_register.yes') }}
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="disability_inclusion_policy" value="0"
                                                                {{ old('disability_inclusion_policy') !== null && (int) old('disability_inclusion_policy') === 0 ? 'checked' : '' }}>
                                                            {{ __('messages.employer_register.no') }}
                                                        </label>
                                                    </fieldset>
                                                    <fieldset id="registerDisabilitySupportQuestion"
                                                              class="{{ old('disability_inclusion_policy') !== null && (int) old('disability_inclusion_policy') === 0 ? '' : 'd-none' }}">
                                                        <legend>{{ __('messages.employer_register.disability_support') }}</legend>
                                                        <label>
                                                            <input type="radio" name="disability_inclusion_support" value="1"
                                                                {{ (int) old('disability_inclusion_support') === 1 ? 'checked' : '' }}>
                                                            {{ __('messages.employer_register.yes') }}
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="disability_inclusion_support" value="0"
                                                                {{ old('disability_inclusion_support') !== null && (int) old('disability_inclusion_support') === 0 ? 'checked' : '' }}>
                                                            {{ __('messages.employer_register.no') }}
                                                        </label>
                                                    </fieldset>
                                                    <fieldset>
                                                        <legend>{{ __('messages.employer_register.disability_training') }}</legend>
                                                        <label>
                                                            <input type="radio" name="disability_inclusion_training" value="1"
                                                                {{ (int) old('disability_inclusion_training') === 1 ? 'checked' : '' }}>
                                                            {{ __('messages.employer_register.yes') }}
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="disability_inclusion_training" value="0"
                                                                {{ old('disability_inclusion_training') !== null && (int) old('disability_inclusion_training') === 0 ? 'checked' : '' }}>
                                                            {{ __('messages.employer_register.no') }}
                                                        </label>
                                                    </fieldset>
                                                </div>
                                                <div class="employer-register-disability-illustration" aria-hidden="true">
                                                    <img src="{{ asset('assets/img/disability.svg') }}" alt="">
                                                </div>
                                            </div>

                                            <div class="employer-register-disability-checklist">
                                                <h3>{{ __('messages.employer_register.disability_facilities_question') }}</h3>
                                                <div class="employer-register-disability-checklist-grid">
                                                    @foreach ($registerDisabilityFacilityOptions as $facilityKey => $facilityLabel)
                                                        <label>
                                                            <input type="checkbox" name="disability_facilities[]"
                                                                   value="{{ $facilityKey }}"
                                                                {{ $registerSelectedDisabilityFacilities->contains($facilityKey) ? 'checked' : '' }}>
                                                            <span>{{ $facilityLabel }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>

                                <div class="shadow rounded border mt-4">
                                    <section class="employer-pricing-policy-card" id="employerPricingPolicyCard">
                                        <div class="employer-pricing-policy-header">
                                            <h2>{{ __('messages.employer_register.pricing_policy', ['name' => getAppName()]) }}</h2>
                                            <button type="button" class="employer-pricing-policy-toggle"
                                                    id="employerPricingPolicyToggle" aria-expanded="true"
                                                    aria-controls="employerPricingPolicyContent"
                                                    aria-label="{{ __('messages.employer_register.toggle_pricing_policy') }}">
                                                <i class="fa-solid fa-chevron-down"></i>
                                            </button>
                                        </div>

                                        <div class="employer-pricing-policy-content" id="employerPricingPolicyContent">
                                            <label for="remember" class="employer-pricing-policy-check">
                                                <input type="checkbox" name="privacyPolicy" value="1"
                                                       id="remember" required>
                                                <span>{{ __('messages.employer_register.privacy_policy_agree') }}
                                                    <a href="{{ route('privacy.policy.list') }}" target="_blank">{{ __('messages.employer_register.privacy_policy') }}</a>
                                                </span>
                                            </label>
                                        </div>
                                    </section>
                                </div>


                            @if ($isGoogleReCaptchaEnabled)
                                <div class="col-12 mt-4">
                                    <div class="form-group">
                                        <div class="g-recaptcha d-flex justify-content-center"
                                             id="gRecaptchaContainerCompanyRegistration"
                                             data-sitekey="{{ config('app.google_recaptcha_site_key') }}"></div>
                                        <div id="g-recaptcha-error"></div>
                                    </div>
                                </div>
                            @endif

                            <div class="col-3 d-grid my-4">
                                <button type="submit" class="btn btn-secondary btn-secondary-login" id="btnEmployerSave"
                                        data-loading-text="<span class='spinner-border spinner-border-sm'></span> {{ __('messages.common.process') }}">
                                    {{ __('web.register_menu.create_account') }}
                                </button>
                            </div>

                            @php
                                $envSetting = getEnvSetting();
                            @endphp
                            <div class="col-12">
                                <div class="d-grid">
                                    @if (
                                        !empty($envSetting['facebook_app_id'] || config('services.facebook.client_id')) &&
                                        !empty($envSetting['facebook_app_secret'] || config('services.facebook.client_secret')) &&
                                        !empty($envSetting['facebook_redirect'] || config('services.facebook.redirect')))
                                        <a href="{{ url('/login/facebook?type=2') }}"
                                           class="btn facebook-btn d-flex align-items-center justify-content-center mb-3">
                                            <i class="fa-brands fa-facebook-f fs-5 {{ getFrontSelectLanguage() == 'ar' ? 'ms-3' : 'me-3' }}"></i>
                                            {{ __('web.login_menu.login_via_facebook') }}
                                        </a>
                                    @endif
                                    @if (
                                        !empty($envSetting['google_client_id'] || config('services.google.client_id')) &&
                                        !empty($envSetting['google_client_secret'] || config('services.google.client_secret')) &&
                                        !empty($envSetting['google_redirect'] || config('services.google.redirect')))
                                        <a href="{{ url('/login/google?type=2') }}"
                                           class="btn google-btn d-flex align-items-center justify-content-center mb-3">
                                            <i class="fa-brands fa-google fs-5 {{ getFrontSelectLanguage() == 'ar' ? 'ms-3' : 'me-3' }}"></i>
                                            {{ __('web.login_menu.login_via_google') }}
                                        </a>
                                    @endif
                                    @if (
                                        !empty($envSetting['linkedin_client_id'] || config('services.linkedin-openid.client_id')) &&
                                        !empty($envSetting['linkedin_client_secret'] || config('services.linkedin-openid.client_secret')) &&
                                        !empty(config('services.linkedin-openid.redirect')))
                                        <a href="{{ url('/login/linkedin-openid?type=2') }}"
                                           class="btn linkedin-btn d-flex align-items-center justify-content-center">
                                            <i class="fa-brands fa-linkedin-in fs-5 {{ getFrontSelectLanguage() == 'ar' ? 'ms-3' : 'me-3' }}"></i>
                                            {{ __('web.login_menu.login_via_linkedin') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>

                        <div class="modal fade employer-register-add-industry-modal" id="registerAddIndustryModal"
                             tabindex="-1" aria-labelledby="registerAddIndustryModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <div class="employer-register-add-industry-modal-heading">
                                            <span class="employer-register-add-industry-modal-icon">
                                                <i class="fa-solid fa-plus"></i>
                                            </span>
                                            <div>
                                                <h2 class="modal-title" id="registerAddIndustryModalLabel">{{ __('messages.employer_register.add_new_industry_title') }}</h2>
                                                <p>{{ __('messages.employer_register.specify_industry') }}</p>
                                            </div>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="alert alert-danger d-none" id="registerIndustryModalError"></div>
                                        <div class="mb-4">
                                            <label for="registerModalIndustryType" class="form-label">{{ __('messages.employer_register.industry_type') }}</label>
                                            <select class="form-select" id="registerModalIndustryType">
                                                @foreach ($industryTypes as $industryTypeId => $industryTypeName)
                                                    <option value="{{ $industryTypeId }}">{{ $industryTypeName }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="registerModalIndustryName" class="form-label">{{ __('messages.employer_register.your_industry_name') }}</label>
                                            <input type="text" class="form-control" id="registerModalIndustryName"
                                                   maxlength="150" placeholder="{{ __('messages.employer_register.type_industry_name') }}">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-success" id="registerAddIndustryButton">{{ __('messages.employer_register.add') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($hasRegisterSideAds)
                        <div class="col-xl-3 col-lg-3 d-none d-lg-block mb-4">
                            @include('front_web.common.register_side_ad', ['ads' => $registerRightAds])
                        </div>
                        <div class="col-12 d-lg-none mt-4">
                            <div class="row">
                                @if ($registerLeftAds->isNotEmpty())
                                    <div class="col-sm-6 mb-3">
                                        @include('front_web.common.register_side_ad', ['ads' => $registerLeftAds])
                                    </div>
                                @endif
                                @if ($registerRightAds->isNotEmpty())
                                    <div class="col-sm-6 mb-3">
                                        @include('front_web.common.register_side_ad', ['ads' => $registerRightAds])
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>

    {{ Form::hidden('isGoogleReCaptchaEnabled', (bool) $isGoogleReCaptchaEnabled, ['id' => 'isGoogleReCaptchaEnabled']) }}
@endsection
