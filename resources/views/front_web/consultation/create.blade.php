@extends('front_web.layouts.app')

@section('title')
    Consultation
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
                            <h1 class="text-secondary mb-2">Consultation</h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb justify-content-center mb-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('front.home') }}" class="fs-18 text-gray">{{ __('web.home') }}</a>
                                    </li>
                                    <li class="breadcrumb-item text-primary fs-18" aria-current="page">
                                        Consultation
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
                                <img src="{{ asset('img_template/consult-1.png') }}" class="img-fluid" alt="Consultation">
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <form method="POST" action="{{ route('consultation.store') }}" id="consultationLeadForm"
                                  class="py-40 px-lg-4 px-30">
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
                                            <label class="fs-16 text-secondary mb-2">Name:
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="name" class="form-control fs-14 text-gray br-10"
                                                   value="{{ old('name') }}" placeholder="Name" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group">
                                            <label class="fs-16 text-secondary mb-2">Phone:
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="phone" class="form-control fs-14 text-gray br-10"
                                                   value="{{ old('phone') }}" placeholder="Phone" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group">
                                            <label class="fs-16 text-secondary mb-2">Email:
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" name="email" class="form-control fs-14 text-gray br-10"
                                                   value="{{ old('email') }}" placeholder="Email" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group">
                                            <label class="fs-16 text-secondary mb-2">Company Name:</label>
                                            <input type="text" name="company_name" class="form-control fs-14 text-gray br-10"
                                                   value="{{ old('company_name') }}" placeholder="Company Name" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group">
                                            <label class="fs-16 text-secondary mb-2">Designation:</label>
                                            <input type="text" name="designation" class="form-control fs-14 text-gray br-10"
                                                   value="{{ old('designation') }}" placeholder="Designation" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group">
                                            <label class="fs-16 text-secondary mb-2">Company Website:</label>
                                            <input type="text" name="company_website" class="form-control fs-14 text-gray br-10"
                                                   value="{{ old('company_website') }}" placeholder="Company Website" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group">
                                            <label class="fs-16 text-secondary mb-2">Consultation Type:
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="consultation_type" class="form-select fs-14 text-gray br-10" required>
                                                @foreach ($consultationTypes as $value => $label)
                                                    <option value="{{ $value }}" {{ old('consultation_type', 'job_posting') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group">
                                            <label class="fs-16 text-secondary mb-2">Company Size:
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="company_size_id" class="form-select fs-14 text-gray br-10"
                                                    id="consultationCompanySize" required>
                                                <option value="">Select Company Size</option>
                                                @foreach ($companySizes as $companySize)
                                                    <option value="{{ $companySize->id }}"
                                                            data-category-name="{{ $companySize->companyCategory?->name ?? '' }}"
                                                        {{ (string) old('company_size_id') === (string) $companySize->id ? 'selected' : '' }}>
                                                        {{ $companySize->size }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="fs-14 text-gray mt-2">
                                                Category: <span class="text-primary" id="consultationCategoryPreview">Not selected</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group">
                                            <label class="fs-16 text-secondary mb-2">Preferred Contact Method:</label>
                                            <select name="preferred_contact_method" class="form-select fs-14 text-gray br-10">
                                                <option value="">Select Contact Method</option>
                                                @foreach ($contactMethods as $value => $label)
                                                    <option value="{{ $value }}" {{ old('preferred_contact_method') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group">
                                            <label class="fs-16 text-secondary mb-2">Preferred Contact Time:</label>
                                            <input type="text" name="preferred_contact_time"
                                                   id="consultationPreferredContactTime"
                                                   class="form-control fs-14 text-gray br-10"
                                                   value="{{ old('preferred_contact_time') }}"
                                                   placeholder="Select date and time" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-12 mb-4">
                                        <div class="form-group">
                                            <label class="fs-16 text-secondary mb-2">Requirement:</label>
                                            <textarea name="message" rows="3" class="form-control fs-14 text-gray br-10"
                                                      placeholder="Write your requirement">{{ old('message') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary px-5">Submit Request</button>
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
            var sizeSelect = document.getElementById('consultationCompanySize');
            var categoryPreview = document.getElementById('consultationCategoryPreview');

            function syncCategoryPreview() {
                var selectedOption = sizeSelect.options[sizeSelect.selectedIndex];
                var categoryName = selectedOption ? selectedOption.getAttribute('data-category-name') : '';
                categoryPreview.textContent = categoryName || 'Not mapped';
            }

            if (sizeSelect && categoryPreview) {
                sizeSelect.addEventListener('change', syncCategoryPreview);
                syncCategoryPreview();
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
