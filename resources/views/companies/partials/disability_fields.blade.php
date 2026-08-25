@php
    $hasDisabilityFacilities = (int) old('has_disability_facilities', isset($company) ? (int) $company->has_disability_facilities : 0);
    $selectedDisabilityFacilities = collect(old('disability_facilities', isset($company) ? ($company->disability_facilities ?: []) : []));
    $disabilityPolicy = old('disability_inclusion_policy', isset($company) ? $company->disability_inclusion_policy : null);
    $disabilitySupport = old('disability_inclusion_support', isset($company) ? $company->disability_inclusion_support : null);
    $disabilityTraining = old('disability_inclusion_training', isset($company) ? $company->disability_inclusion_training : null);
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

<div class="col-xl-6 col-md-6 col-sm-12 mb-5">
    <label class="form-label">{{ __('messages.employer_account.has_disability_facilities_question') }}</label>
    <div class="d-flex flex-wrap gap-4">
        <label class="form-check form-check-custom form-check-solid">
            {{ Form::radio('has_disability_facilities', 1, $hasDisabilityFacilities === 1, ['class' => 'form-check-input', 'data-facilities-toggle' => true]) }}
            <span class="form-check-label">{{ __('messages.employer_account.yes') }}</span>
        </label>
        <label class="form-check form-check-custom form-check-solid">
            {{ Form::radio('has_disability_facilities', 0, $hasDisabilityFacilities !== 1, ['class' => 'form-check-input', 'data-facilities-toggle' => true]) }}
            <span class="form-check-label">{{ __('messages.employer_account.no') }}</span>
        </label>
    </div>
</div>

<div class="col-12 mb-5 {{ $hasDisabilityFacilities === 1 ? '' : 'd-none' }}" id="companyDisabilityDetails">
    <div class="row">
        <div class="col-xl-4 col-md-6 col-sm-12 mb-5">
            <label class="form-label d-block">{{ __('messages.employer_account.disability_inclusion_policy') }}</label>
            <label class="me-5">{{ Form::radio('disability_inclusion_policy', 1, (int) $disabilityPolicy === 1, ['data-disability-policy' => true]) }} {{ __('messages.employer_account.yes') }}</label>
            <label>{{ Form::radio('disability_inclusion_policy', 0, $disabilityPolicy !== null && (int) $disabilityPolicy === 0, ['data-disability-policy' => true]) }} {{ __('messages.employer_account.no') }}</label>
        </div>
        <div class="col-xl-4 col-md-6 col-sm-12 mb-5 {{ $disabilityPolicy !== null && (int) $disabilityPolicy === 0 ? '' : 'd-none' }}" id="companyDisabilitySupportQuestion">
            <label class="form-label d-block">{{ __('messages.employer_account.disability_support') }}</label>
            <label class="me-5">{{ Form::radio('disability_inclusion_support', 1, (int) $disabilitySupport === 1) }} {{ __('messages.employer_account.yes') }}</label>
            <label>{{ Form::radio('disability_inclusion_support', 0, $disabilitySupport !== null && (int) $disabilitySupport === 0) }} {{ __('messages.employer_account.no') }}</label>
        </div>
        <div class="col-xl-4 col-md-6 col-sm-12 mb-5">
            <label class="form-label d-block">{{ __('messages.employer_account.disability_training') }}</label>
            <label class="me-5">{{ Form::radio('disability_inclusion_training', 1, (int) $disabilityTraining === 1) }} {{ __('messages.employer_account.yes') }}</label>
            <label>{{ Form::radio('disability_inclusion_training', 0, $disabilityTraining !== null && (int) $disabilityTraining === 0) }} {{ __('messages.employer_account.no') }}</label>
        </div>
        <div class="col-12">
            <label class="form-label d-block">{{ __('messages.employer_account.disability_facilities_question') }}</label>
            <div class="row">
                @foreach ($disabilityFacilityOptions as $facilityKey => $facilityLabel)
                    <label class="col-xl-4 col-md-6 col-sm-12 mb-3">
                        {{ Form::checkbox('disability_facilities[]', $facilityKey, $selectedDisabilityFacilities->contains($facilityKey)) }}
                        <span>{{ $facilityLabel }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    </div>
</div>
