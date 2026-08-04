@extends('employer.layouts.app')
@section('title')
    {{ __('messages.company.edit_employer') }}
@endsection
@push('css')
    {{--    <link href="{{ asset('assets/css/summernote.min.css') }}" rel="stylesheet" type="text/css"/> --}}
    {{--    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css"/> --}}
    <link rel="stylesheet" href="{{ asset('assets/css/inttel/css/intlTelInput.css') }}">
@endpush
@section('content')
    <div class="employer-account-page">
        <div class="row">
            <div class="col-12">
                @include('layouts.errors')
                @include('flash::message')
                <div class="alert alert-danger  hide d-none" id="editValidationErrorsBox">
                    <i class="fa-solid fa-face-frown me-5"></i>
                </div>
            </div>
        </div>
        <div class="employer-account-layout">
            <aside class="employer-account-sidebar">
                <h2>{{ __('messages.settings') }}</h2>
                <nav class="employer-account-nav">
                    <a class="active" href="#companyDetails">
                        <i class="fa-regular fa-user"></i>
                        <span>{{ __('messages.user.edit_profile') }}</span>
                        <i class="fa-solid fa-chevron-up"></i>
                    </a>
                    <a href="#companyDetails">{{ __('messages.company.company_details') }}</a>
                    <a href="#contactDetails">{{ __('messages.company.contact_details') }}</a>
                    <a href="#billingAddress">{{ __('messages.company.location') }}</a>
                    <button type="button" class="changePasswordModal" data-id="{{ getLoggedInUserId() }}">
                        <i class="fa-solid fa-key"></i>
                        <span>{{ __('messages.user.change_password') }}</span>
                    </button>
                </nav>
            </aside>

            <main class="employer-account-panel">
                {{ Form::model($user, ['route' => ['company.update.form', $company->id], 'method' => 'put', 'id' => 'editCompanyForm', 'files' => true]) }}
                <div class="employer-account-panel__head">
                    <div>
                        <h1>{{ __('messages.company.edit_employer') }}</h1>
                        <p>{{ __('messages.employer_dashboard.profile_subtitle') }}</p>
                    </div>
                    @if ($isFeaturedEnable)
                        @if ($company->activeFeatured)
                            <div class="badge badge-info text-gray-900 d-inline-block rounded">
                                {{ __('messages.front_settings.featured') }}
                                {{ __('messages.front_settings.exipre_on') }}
                                {{ (new Carbon\Carbon($company->activeFeatured->end_time))->format('d/m/y') }}
                            </div>
                        @elseif ($isFeaturedAvilabal)
                            <a class="btn btn-info btn-sm" id="makeFeatured">{{ __('messages.front_settings.make_featured') }}</a>
                        @endif
                    @endif
                </div>

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

                <div class="employer-account-section-title" id="companyDetails">
                    <i class="fa-solid fa-building"></i>
                    <span>{{ __('messages.company.company_details') }}</span>
                </div>

                <div class="employer-account-form">
                    @include('employer.companies.edit_fields')
                </div>
                {{ Form::close() }}
                {{ Form::hidden('countryId', $company->user->country_id, ['id' => 'countryId']) }}
                {{ Form::hidden('stateId', $company->user->state_id, ['id' => 'stateId']) }}
                {{ Form::hidden('cityId', $company->user->city_id, ['id' => 'cityId']) }}
                {{ Form::hidden('companyId', $company->id, ['id' => 'employerCompanyId']) }}
                {{ Form::hidden('employerPanel', true, ['class' => 'employerPanel']) }}
                {{ Form::hidden('isEdit', true, ['class' => 'isEdit']) }}
            </main>
        </div>
    </div>
@endsection

@push('scripts')
    {{--    <script src="https://js.stripe.com/v3/"></script> --}}
    <script>
        var phoneNo = "{{ old('region_code') . old('phone') }}";
        document.addEventListener('change', function (event) {
            if (event.target && event.target.id === 'employerCompanyLogo' && event.target.files[0]) {
                const preview = document.querySelector('.employer-account-logo-picker img');

                if (preview) {
                    preview.src = URL.createObjectURL(event.target.files[0]);
                }
            }
        });
    </script>
    {{--    <script src="{{mix('assets/js/companies/create-edit.js')}}"></script> --}}
    {{--    <script src="{{ asset('assets/js/companies/companies_stripe_payment.js') }}"></script> --}}
    {{--    <script src="{{ mix('assets/js/custom/phone-number-country-code.js') }}"></script> --}}
@endpush
