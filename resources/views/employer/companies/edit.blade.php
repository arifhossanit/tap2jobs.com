@extends('employer.layouts.app')
@section('title')
    {{ __('messages.company.edit_company') }}
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
                <div class="employer-account-sidebar__inner">
                    <h2>{{ __('messages.settings') }}</h2>
                    <nav class="employer-account-nav">
                        <button type="button" class="active employer-account-nav-toggle" aria-expanded="true"
                                aria-controls="employerProfileSubnav">
                            <i class="fa-regular fa-user"></i>
                            <span>{{ __('messages.user.edit_profile') }}</span>
                            <i class="fa-solid fa-chevron-up"></i>
                        </button>
                        <div class="employer-account-subnav" id="employerProfileSubnav">
                            <button type="button" class="employer-account-section-link active"
                                    data-account-section="companyDetailsPanel">
                                {{ __('messages.company.company_details') }}
                            </button>
                            <button type="button" class="employer-account-section-link"
                                    data-account-section="contactDetailsPanel">
                                {{ __('messages.company.contact_details') }}
                            </button>
                            <button type="button" class="employer-account-section-link"
                                    data-account-section="billingAddressPanel">
                                Billing Address
                            </button>
                        </div>
                        <button type="button" class="employer-account-nav-action changePasswordModal" data-id="{{ getLoggedInUserId() }}">
                            <i class="fa-solid fa-key"></i>
                            <span>{{ __('messages.user.change_password') }}</span>
                        </button>
                        <button type="button" class="employer-account-nav-action" aria-label="User Management">
                            <span class="employer-account-user-icon" aria-hidden="true">
                                <i class="fa-regular fa-user"></i>
                                <i class="fa-solid fa-gear"></i>
                            </span>
                            <span>User Management</span>
                        </button>
                    </nav>
                </div>
            </aside>

            <main class="employer-account-panel">
                {{ Form::model($user, ['route' => ['company.update.form', $company->id], 'method' => 'put', 'id' => 'editCompanyForm', 'files' => true]) }}
                <div class="employer-account-panel__head">
                    <div>
                        <h1>{{ __('messages.company.edit_company') }}</h1>
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

                @include('employer.companies.edit_fields')
                {{ Form::close() }}

                <div class="modal fade employer-add-industry-modal" id="employerAddIndustryModal" tabindex="-1"
                     aria-labelledby="employerAddIndustryModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="employer-add-industry-modal__heading">
                                    <span class="employer-add-industry-modal__icon"><i class="fa-solid fa-plus"></i></span>
                                    <div>
                                        <h2 class="modal-title" id="employerAddIndustryModalLabel">Add New Industry</h2>
                                        <p>Please specify your industry</p>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger d-none" id="employerIndustryModalError"></div>
                                <div class="mb-5">
                                    <label for="employerModalIndustryType" class="form-label">Industry Type</label>
                                    <select class="form-select" id="employerModalIndustryType">
                                        @foreach ($data['industryTypes'] as $industryTypeId => $industryTypeName)
                                            <option value="{{ $industryTypeId }}">{{ $industryTypeName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="employerModalIndustryName" class="form-label">Your Industry Name</label>
                                    <input type="text" class="form-control" id="employerModalIndustryName"
                                           maxlength="150" placeholder="Type industry name">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success" id="employerAddIndustryButton">Add</button>
                            </div>
                        </div>
                    </div>
                </div>
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

        function setActiveAccountSection(sectionId) {
            document.querySelectorAll('.employer-account-section-link').forEach(function (link) {
                link.classList.toggle('active', link.dataset.accountSection === sectionId);
            });
        }

        document.addEventListener('change', function (event) {
            if (event.target && event.target.id === 'employerCompanyLogo' && event.target.files[0]) {
                const preview = document.querySelector('.employer-account-logo-picker img');

                if (preview) {
                    preview.src = URL.createObjectURL(event.target.files[0]);
                }
            }
        });

        document.addEventListener('click', function (event) {
            const toggle = event.target.closest('.employer-account-nav-toggle');

            if (toggle) {
                const subnav = document.getElementById(toggle.getAttribute('aria-controls'));

                if (subnav) {
                    const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
                    toggle.setAttribute('aria-expanded', String(!isExpanded));
                    subnav.classList.toggle('is-collapsed', isExpanded);
                }
            }

            const sectionLink = event.target.closest('.employer-account-section-link');

            if (!sectionLink) {
                return;
            }

            const targetPanel = document.getElementById(sectionLink.dataset.accountSection);

            if (!targetPanel) {
                return;
            }

            setActiveAccountSection(targetPanel.id);

            const profileToggle = document.querySelector('.employer-account-nav-toggle');
            const profileSubnav = document.getElementById('employerProfileSubnav');

            if (profileToggle && profileSubnav) {
                profileToggle.setAttribute('aria-expanded', 'true');
                profileSubnav.classList.remove('is-collapsed');
            }

            const targetPosition = targetPanel.getBoundingClientRect().top + window.pageYOffset - 82;
            window.scrollTo({ top: targetPosition, behavior: 'smooth' });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const accountPanels = Array.from(document.querySelectorAll('.employer-account-content-panel'));

            if (!accountPanels.length) {
                return;
            }

            const updateActiveSection = function () {
                let currentPanel = accountPanels[0];

                accountPanels.forEach(function (panel) {
                    if (panel.getBoundingClientRect().top <= 140) {
                        currentPanel = panel;
                    }
                });

                setActiveAccountSection(currentPanel.id);
            };

            window.addEventListener('scroll', updateActiveSection, { passive: true });
            updateActiveSection();

            const primaryIndustryInput = document.getElementById('primaryIndustryId');
            const industryType = document.getElementById('employerIndustryType');
            const industryOptions = document.getElementById('employerIndustryOptions');
            const industrySearch = document.getElementById('employerIndustrySearch');
            const industryMore = document.getElementById('employerIndustryMore');
            const industryTags = document.getElementById('employerIndustryTags');
            const industryEmpty = document.getElementById('employerIndustryEmpty');
            const addIndustryTrigger = document.getElementById('employerAddIndustryTrigger');
            const modalIndustryType = document.getElementById('employerModalIndustryType');
            const modalIndustryName = document.getElementById('employerModalIndustryName');
            const modalIndustryError = document.getElementById('employerIndustryModalError');
            const addIndustryButton = document.getElementById('employerAddIndustryButton');

            const refreshIndustryOptions = function (resetExpansion) {
                if (!industryOptions || !industryType) {
                    return;
                }

                if (resetExpansion) {
                    industryOptions.classList.remove('is-expanded');
                }

                const selectedType = industryType.value;
                const query = industrySearch ? industrySearch.value.trim().toLowerCase() : '';
                let matchedCount = 0;

                industryOptions.querySelectorAll('.employer-industry-option').forEach(function (option) {
                    const isMatch = (selectedType === 'all' || option.dataset.industryTypeId === selectedType) &&
                        (!query || option.dataset.industryName.includes(query));

                    option.classList.toggle('is-filtered-out', !isMatch);
                    option.classList.remove('is-extra');

                    if (isMatch) {
                        option.classList.toggle('is-extra', matchedCount >= 9);
                        matchedCount += 1;
                    }
                });

                if (industryEmpty) {
                    industryEmpty.classList.toggle('d-none', matchedCount !== 0);
                }

                if (industryMore) {
                    industryMore.classList.toggle('d-none', matchedCount <= 9);
                    industryMore.textContent = industryOptions.classList.contains('is-expanded') ? 'See less' : 'See more';
                }
            };

            const updateIndustryPicker = function () {
                if (!primaryIndustryInput || !industryOptions || !industryTags) {
                    return;
                }

                const selectedCheckboxes = Array.from(
                    industryOptions.querySelectorAll('input[type="checkbox"]:checked')
                );
                primaryIndustryInput.value = selectedCheckboxes.length ? selectedCheckboxes[0].value : '';

                industryTags.innerHTML = '';
                selectedCheckboxes.forEach(function (checkbox) {
                    const optionLabel = checkbox.closest('.employer-industry-option');
                    const optionText = optionLabel ? optionLabel.querySelector('span').textContent.trim() : '';
                    const tag = document.createElement('span');
                    tag.dataset.industryId = checkbox.value;
                    tag.append(document.createTextNode(optionText + ' '));
                    const removeIcon = document.createElement('i');
                    removeIcon.className = 'fa-solid fa-xmark';
                    tag.append(removeIcon);
                    industryTags.append(tag);
                });
            };

            if (industryOptions && primaryIndustryInput) {
                industryOptions.addEventListener('change', function (event) {
                    if (event.target.matches('input[type="checkbox"]')) {
                        updateIndustryPicker();
                    }
                });
            }

            if (industrySearch && industryOptions) {
                industrySearch.addEventListener('input', function () {
                    refreshIndustryOptions(true);
                });
            }

            if (industryType) {
                industryType.addEventListener('change', function () {
                    if (industrySearch) {
                        industrySearch.value = '';
                    }
                    refreshIndustryOptions(true);
                });
            }

            if (industryMore && industryOptions) {
                industryMore.addEventListener('click', function () {
                    const isExpanded = industryOptions.classList.toggle('is-expanded');
                    this.textContent = isExpanded ? 'See less' : 'See more';
                });
            }

            if (industryTags && industryOptions) {
                industryTags.addEventListener('click', function (event) {
                    if (event.target.matches('.fa-xmark')) {
                        const tag = event.target.closest('[data-industry-id]');
                        const checkbox = tag
                            ? industryOptions.querySelector('input[value="' + tag.dataset.industryId + '"]')
                            : null;
                        if (checkbox) {
                            checkbox.checked = false;
                            updateIndustryPicker();
                        }
                    }
                });
            }

            if (addIndustryTrigger && modalIndustryType) {
                addIndustryTrigger.addEventListener('click', function () {
                    const selectedType = industryType ? industryType.value : '';
                    const selectedTypeExists = Array.from(modalIndustryType.options).some(function (option) {
                        return option.value === selectedType;
                    });
                    modalIndustryType.value = selectedTypeExists ? selectedType : modalIndustryType.options[0].value;
                    modalIndustryName.value = '';
                    modalIndustryError.classList.add('d-none');
                    modalIndustryError.textContent = '';
                    setTimeout(function () {
                        modalIndustryName.focus();
                    }, 350);
                });
            }

            const addEmployerIndustry = async function () {
                if (addIndustryButton.disabled) {
                    return;
                }

                const industryName = modalIndustryName.value.trim();

                modalIndustryError.classList.add('d-none');
                modalIndustryError.textContent = '';

                if (!industryName) {
                    modalIndustryError.textContent = 'Please enter your industry name.';
                    modalIndustryError.classList.remove('d-none');
                    modalIndustryName.focus();
                    return;
                }

                addIndustryButton.disabled = true;
                addIndustryButton.textContent = 'Adding...';

                try {
                    const response = await fetch("{{ route('employer.industry.store') }}", {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            industry_type_id: modalIndustryType.value,
                            name: industryName
                        })
                    });
                    const result = await response.json();

                    if (!response.ok || !result.success) {
                        const validationMessage = result.errors
                            ? Object.values(result.errors).flat()[0]
                            : result.message;
                        throw new Error(validationMessage || 'Unable to add the industry.');
                    }

                    const industry = result.data;
                    const option = document.createElement('label');
                    option.className = 'employer-industry-option';
                    option.dataset.industryName = industry.name.toLowerCase();
                    option.dataset.industryTypeId = String(industry.industry_type_id);

                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.name = 'industry_ids[]';
                    checkbox.value = industry.id;
                    checkbox.checked = true;

                    const labelText = document.createElement('span');
                    labelText.textContent = industry.name;
                    option.append(checkbox, labelText);
                    industryOptions.append(option);

                    industryType.value = String(industry.industry_type_id);
                    industrySearch.value = '';
                    updateIndustryPicker();
                    refreshIndustryOptions(true);

                    const modalElement = document.getElementById('employerAddIndustryModal');
                    const modalInstance = window.bootstrap ? window.bootstrap.Modal.getInstance(modalElement) : null;
                    if (modalInstance) {
                        modalInstance.hide();
                    } else {
                        modalElement.querySelector('[data-bs-dismiss="modal"]').click();
                    }
                } catch (error) {
                    modalIndustryError.textContent = error.message;
                    modalIndustryError.classList.remove('d-none');
                } finally {
                    addIndustryButton.disabled = false;
                    addIndustryButton.textContent = 'Add';
                }
            };

            if (addIndustryButton) {
                addIndustryButton.addEventListener('click', addEmployerIndustry);
                modalIndustryName.addEventListener('keydown', function (event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        addEmployerIndustry();
                    }
                });
            }

            refreshIndustryOptions(true);

            const editContactButton = document.getElementById('employerEditContactPersonButton');
            const editableContactFields = [
                document.getElementById('employerContactDesignation'),
                document.getElementById('email'),
                document.getElementById('phoneNumber')
            ].filter(Boolean);

            if (editContactButton && editableContactFields.length) {
                editContactButton.addEventListener('click', function () {
                    const isEditing = this.classList.toggle('is-editing');

                    editableContactFields.forEach(function (field) {
                        field.readOnly = !isEditing;
                        field.classList.toggle('employer-contact-readonly', !isEditing);
                    });

                    this.textContent = isEditing ? 'Done' : 'Add/Edit Contact Person';
                    if (isEditing) {
                        editableContactFields[0].focus();
                    }
                });
            }

            const billingPhoneInput = document.getElementById('billingPhoneNumber');
            const billingPrefixCode = document.getElementById('billingPrefixCode');

            if (billingPhoneInput && billingPrefixCode && window.intlTelInput) {
                const billingPhone = window.intlTelInput(billingPhoneInput, {
                    initialCountry: 'bd',
                    separateDialCode: true,
                    utilsScript: "{{ asset('assets/js/inttel/js/utils.min.js') }}"
                });
                const existingBillingNumber = @json(($company->billing_region_code ?: $user->region_code ?: '880').($company->billing_phone ?: $user->phone ?: ''));

                if (existingBillingNumber) {
                    billingPhone.setNumber('+' + String(existingBillingNumber).replace(/\D/g, ''));
                }

                const updateBillingPrefix = function () {
                    billingPrefixCode.value = billingPhone.getSelectedCountryData().dialCode || '880';
                };

                billingPhoneInput.addEventListener('input', function () {
                    this.value = this.value.replace(/\D/g, '');
                });
                billingPhoneInput.addEventListener('countrychange', updateBillingPrefix);
                updateBillingPrefix();
            }

            const facilitiesToggleInputs = document.querySelectorAll('[data-facilities-toggle]');
            const disabilityDetails = document.getElementById('employerDisabilityDetails');

            const updateDisabilityDetails = function () {
                if (!disabilityDetails) {
                    return;
                }

                const selectedFacilitiesOption = document.querySelector('[data-facilities-toggle]:checked');
                const showDetails = selectedFacilitiesOption && selectedFacilitiesOption.value === '1';
                disabilityDetails.classList.toggle('d-none', !showDetails);

                disabilityDetails.querySelectorAll('input').forEach(function (input) {
                    input.disabled = !showDetails;
                });
                disabilityDetails.querySelectorAll('input[name="disability_inclusion_policy"], input[name="disability_inclusion_training"]').forEach(function (input) {
                    input.required = Boolean(showDetails);
                });
            };

            facilitiesToggleInputs.forEach(function (input) {
                input.addEventListener('change', updateDisabilityDetails);
            });
            updateDisabilityDetails();

            const businessDescription = document.querySelector('#companyDetailsPanel .ql-editor');
            if (businessDescription) {
                businessDescription.setAttribute('data-placeholder', 'Write Business Description');
            }
        });
    </script>
    {{--    <script src="{{mix('assets/js/companies/create-edit.js')}}"></script> --}}
    {{--    <script src="{{ asset('assets/js/companies/companies_stripe_payment.js') }}"></script> --}}
    {{--    <script src="{{ mix('assets/js/custom/phone-number-country-code.js') }}"></script> --}}
@endpush
