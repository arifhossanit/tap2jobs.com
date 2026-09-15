@extends('front_web.layouts.app')

@section('title')
    {{ __('web.consultation.title') }}
@endsection

@section('page_css')
    <style>
        #consultationLeadForm .form-control,
        #consultationLeadForm .form-select {
            min-height: 58px;
            padding: 15px 20px;
        }

        #consultationLeadForm textarea.form-control {
            min-height: 120px;
        }

        #consultationLeadForm .consultation-type-select + .select2-container .select2-selection--single {
            height: 58px;
            border: 1px solid #ced4da;
            border-radius: 10px;
        }

        #consultationLeadForm .consultation-type-select + .select2-container .select2-selection__rendered {
            padding: 14px 48px 14px 20px;
            line-height: 28px;
        }

        #consultationLeadForm .consultation-type-select + .select2-container .select2-selection__arrow {
            height: 56px;
            right: 12px;
        }

        .consultation-type-dropdown .select2-results__options {
            max-height: 240px !important;
            overflow-y: auto !important;
        }

        .contact-img img {
            max-width: 100%;
            height: auto;
            object-fit: contain;
        }
    </style>
@endsection

@section('content')
    <div class="Blog-page">
        <section class="hero-section position-relative bg-gradient pt-15 pb-40">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-6 text-center">
                        <div class="hero-content">
                            <h1 class="text-secondary mb-2">{{ __('web.consultation.title') }}</h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb justify-content-center mb-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('front.home') }}" class="fs-18 text-gray">{{ __('web.home') }}</a>
                                    </li>
                                    <li class="breadcrumb-item text-primary fs-18" aria-current="page">
                                        {{ __('web.consultation.title') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="contact-us-section py-60">
            <div class="container">
                <div class="contact-us bg-light br-10">
                    <div class="row align-items-center">
                        <div class="col-lg-4 d-lg-flex d-none align-items-center justify-content-center p-4">
                            <div class="contact-img text-center">
                                <img src="{{ asset('img_template/consult-1.png') }}" class="img-fluid" alt="{{ __('web.consultation.title') }}">
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <form method="POST" action="{{ route('consultation.store') }}" id="consultationLeadForm"
                                  class="p-4 p-md-5">
                                @csrf
                                @if (session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                @include('front_web.layouts.errors')

                                <input type="hidden" name="ad_id" value="{{ old('ad_id', $ad?->id ?? request('ad_id')) }}">
                                <input type="hidden" name="source_page" value="{{ old('source_page', url()->previous()) }}">
                                <input type="hidden" name="clicked_url" value="{{ old('clicked_url', request()->fullUrl()) }}">
                                <input type="hidden" name="utm_source" value="{{ old('utm_source', request('utm_source')) }}">
                                <input type="hidden" name="utm_medium" value="{{ old('utm_medium', request('utm_medium')) }}">
                                <input type="hidden" name="utm_campaign" value="{{ old('utm_campaign', request('utm_campaign')) }}">

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group">
                                            <label class="fs-16 text-secondary mb-2">{{ __('web.consultation.name') }}:
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="name" class="form-control fs-14 text-gray br-10"
                                                   value="{{ old('name') }}" placeholder="{{ __('web.consultation.name_placeholder') }}" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group">
                                            <label class="fs-16 text-secondary mb-2">{{ __('web.consultation.phone') }}:
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="phone" class="form-control fs-14 text-gray br-10"
                                                   value="{{ old('phone') }}" placeholder="{{ __('web.consultation.phone_placeholder') }}" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group">
                                            <label class="fs-16 text-secondary mb-2">{{ __('web.consultation.email') }}:
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" name="email" class="form-control fs-14 text-gray br-10"
                                                   value="{{ old('email') }}" placeholder="{{ __('web.consultation.email_placeholder') }}" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group">
                                            <label class="fs-16 text-secondary mb-2">{{ __('web.consultation.company_name') }}:</label>
                                            <input type="text" name="company_name" class="form-control fs-14 text-gray br-10"
                                                   value="{{ old('company_name') }}" placeholder="{{ __('web.consultation.company_name_placeholder') }}" autocomplete="off">
                                        </div>
                                    </div>
                                   
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group">
                                            <label class="fs-16 text-secondary mb-2">{{ __('web.consultation.consultation_type') }}:
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="consultation_type" class="form-select fs-14 text-gray br-10 consultation-type-select" required>
                                                <option value="">{{ __('web.consultation.select_consultation_type') }}</option>
                                                @foreach ($consultationTypes as $value => $label)
                                                    <option value="{{ $value }}" {{ old('consultation_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group">
                                            <label class="fs-16 text-secondary mb-2">{{ __('web.consultation.company_size') }}:
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="company_size_id" class="form-select fs-14 text-gray br-10"
                                                    id="consultationCompanySize" required>
                                                <option value="">{{ __('web.consultation.select_company_size') }}</option>
                                                @foreach ($companySizes as $companySize)
                                                    <option value="{{ $companySize->id }}"
                                                        {{ (string) old('company_size_id') === (string) $companySize->id ? 'selected' : '' }}>
                                                        {{ $companySize->size }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group">
                                            <label class="fs-16 text-secondary mb-2">{{ __('web.consultation.preferred_contact_method') }}:</label>
                                            <select name="preferred_contact_method" class="form-select fs-14 text-gray br-10">
                                                <option value="">{{ __('web.consultation.select_contact_method') }}</option>
                                                @foreach ($contactMethods as $value => $label)
                                                    <option value="{{ $value }}" {{ old('preferred_contact_method') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group">
                                            <label class="fs-16 text-secondary mb-2">{{ __('web.consultation.preferred_contact_time') }}:</label>
                                            <input type="text" name="preferred_contact_time"
                                                   id="consultationPreferredContactTime"
                                                   class="form-control fs-14 text-gray br-10"
                                                   value="{{ old('preferred_contact_time') }}"
                                                   placeholder="{{ __('web.consultation.select_date_time') }}" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-12 mb-4">
                                        <div class="form-group">
                                            <label class="fs-16 text-secondary mb-2">{{ __('web.consultation.requirement') }}:</label>
                                            <textarea name="message" rows="3" class="form-control fs-14 text-gray br-10"
                                                      placeholder="{{ __('web.consultation.requirement_placeholder') }}">{{ old('message') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-12 text-center text-md-start">
                                        <button type="submit" class="btn btn-primary px-5 w-100 w-md-auto">{{ __('web.consultation.submit_request') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const consultationType = $('.consultation-type-select');
            if (consultationType.length && typeof consultationType.select2 === 'function') {
                consultationType.select2({
                    width: '100%',
                    minimumResultsForSearch: Infinity,
                    dropdownCssClass: 'consultation-type-dropdown',
                });
            }

            if (typeof flatpickr !== 'undefined') {
                flatpickr('#consultationPreferredContactTime', {
                    enableTime: true,
                    dateFormat: 'Y-m-d h:i K',
                    minDate: 'today',
                    minuteIncrement: 15,
                });
            }
        });
    </script>
@endsection
