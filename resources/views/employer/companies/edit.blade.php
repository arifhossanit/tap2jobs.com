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
                                {{ __('messages.employer_account.billing_address') }}
                            </button>
                        </div>
                        <button type="button" class="employer-account-nav-action employer-account-password-link"
                                data-account-password-panel="employerPasswordPanel">
                            <i class="fa-solid fa-key"></i>
                            <span>{{ __('messages.user.change_password') }}</span>
                        </button>
                        {{-- <button type="button" class="employer-account-nav-action" aria-label="User Management">
                            <span class="employer-account-user-icon" aria-hidden="true">
                                <i class="fa-regular fa-user"></i>
                                <i class="fa-solid fa-gear"></i>
                            </span>
                            <span>User Management</span>
                        </button> --}}
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

                <section class="employer-account-password-panel d-none" id="employerPasswordPanel">
                    <div class="employer-account-panel__head employer-account-password-head">
                        <div>
                            <h1>{{ __('messages.user.change_password') }}</h1>
                            <p>{{ __('messages.employer_account.change_password_help') }}</p>
                        </div>
                    </div>

                    {{ Form::open(['id' => 'employerAccountPasswordForm', 'class' => 'employer-account-password-form']) }}
                        <div class="alert alert-danger d-none" id="employerAccountPasswordErrors"></div>

                        <div class="employer-account-password-field">
                            <label for="employerAccountCurrentPassword">{{ __('messages.employer_account.old_password') }} <span class="text-danger">*</span></label>
                            <div class="employer-account-password-input">
                                <input type="password" name="password_current" id="employerAccountCurrentPassword"
                                       class="form-control" placeholder="{{ __('messages.employer_account.enter_old_password') }}" required autocomplete="current-password">
                                <button type="button" class="employer-account-password-visibility"
                                        data-password-target="employerAccountCurrentPassword" aria-label="{{ __('messages.employer_account.show_old_password') }}">
                                    <i class="fa-regular fa-eye-slash"></i>
                                </button>
                            </div>
                        </div>

                        <div class="employer-account-password-field">
                            <label for="employerAccountNewPassword">{{ __('messages.employer_account.new_password') }} <span class="text-danger">*</span></label>
                            <div class="employer-account-password-input">
                                <input type="password" name="password" id="employerAccountNewPassword"
                                       class="form-control" minlength="6" maxlength="20"
                                       placeholder="{{ __('messages.employer_account.maximum_20_characters') }}" required autocomplete="new-password">
                                <button type="button" class="employer-account-password-visibility"
                                        data-password-target="employerAccountNewPassword" aria-label="{{ __('messages.employer_account.show_new_password') }}">
                                    <i class="fa-regular fa-eye-slash"></i>
                                </button>
                            </div>
                        </div>

                        <div class="employer-account-password-field">
                            <label for="employerAccountConfirmPassword">{{ __('messages.employer_account.confirm_password') }} <span class="text-danger">*</span></label>
                            <div class="employer-account-password-input">
                                <input type="password" name="password_confirmation" id="employerAccountConfirmPassword"
                                       class="form-control" minlength="6" maxlength="20"
                                       placeholder="{{ __('messages.employer_account.maximum_20_characters') }}" required autocomplete="new-password">
                                <button type="button" class="employer-account-password-visibility"
                                        data-password-target="employerAccountConfirmPassword" aria-label="{{ __('messages.employer_account.show_confirmed_password') }}">
                                    <i class="fa-regular fa-eye-slash"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="employer-account-password-submit" id="employerAccountPasswordSubmit"
                                data-loading-text="<span class='spinner-border spinner-border-sm'></span> {{ __('messages.common.process') }}">
                            {{ __('messages.employer_account.update_password') }}
                        </button>
                    {{ Form::close() }}
                </section>

                <div class="modal fade employer-add-industry-modal" id="employerAddIndustryModal" tabindex="-1"
                     aria-labelledby="employerAddIndustryModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="employer-add-industry-modal__heading">
                                    <span class="employer-add-industry-modal__icon"><i class="fa-solid fa-plus"></i></span>
                                    <div>
                                        <h2 class="modal-title" id="employerAddIndustryModalLabel">{{ __('messages.employer_account.add_new_industry_title') }}</h2>
                                        <p>{{ __('messages.employer_account.specify_industry') }}</p>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger d-none" id="employerIndustryModalError"></div>
                                <div class="mb-5">
                                    <label for="employerModalIndustryType" class="form-label">{{ __('messages.employer_account.industry_type') }}</label>
                                    <select class="form-select" id="employerModalIndustryType">
                                        @foreach ($data['industryTypes'] as $industryTypeId => $industryTypeName)
                                            <option value="{{ $industryTypeId }}">{{ $industryTypeName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="employerModalIndustryName" class="form-label">{{ __('messages.employer_account.your_industry_name') }}</label>
                                    <input type="text" class="form-control" id="employerModalIndustryName"
                                           maxlength="150" placeholder="{{ __('messages.employer_account.type_industry_name') }}">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success" id="employerAddIndustryButton">{{ __('messages.employer_account.add') }}</button>
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

        $(document).off('submit.employerAccountSave', '#editCompanyForm')
            .on('submit.employerAccountSave', '#editCompanyForm', function (event) {
                event.preventDefault();

                const form = this;
                const errorBox = document.getElementById('editValidationErrorsBox');

                if (window.editEmployeeDetail && window.editEmployeeDetail.getText().trim().length === 0) {
                    return;
                }

                if (document.getElementById('error-msg')?.textContent.trim()) {
                    document.getElementById('phoneNumber')?.focus();
                    return;
                }

                if (form.dataset.saving === 'true') {
                    return;
                }

                form.dataset.saving = 'true';
                errorBox?.classList.add('d-none');
                processingBtn('#editCompanyForm', '#employerSaveChanges', 'loading');

                $.ajax({
                    url: form.action,
                    type: 'POST',
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    headers: {
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function (result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                        }
                    },
                    error: function (result) {
                        const response = result.responseJSON || {};
                        const errors = response.errors || {};
                        const firstError = Object.keys(errors).length
                            ? errors[Object.keys(errors)[0]][0]
                            : response.message;

                        displayErrorMessage(firstError || '{{ __('messages.common.save_failed') }}');
                    },
                    complete: function () {
                        form.dataset.saving = 'false';
                        processingBtn('#editCompanyForm', '#employerSaveChanges');
                    }
                });
            });

        function setActiveAccountSection(sectionId) {
            document.querySelectorAll('.employer-account-section-link').forEach(function (link) {
                link.classList.toggle('active', link.dataset.accountSection === sectionId);
            });
        }

        function setEmployerPasswordView(showPassword) {
            const profileForm = document.getElementById('editCompanyForm');
            const passwordPanel = document.getElementById('employerPasswordPanel');
            const passwordLink = document.querySelector('.employer-account-password-link');
            const profileToggle = document.querySelector('.employer-account-nav-toggle');

            if (!profileForm || !passwordPanel) {
                return;
            }

            profileForm.classList.toggle('d-none', showPassword);
            passwordPanel.classList.toggle('d-none', !showPassword);
            passwordLink?.classList.toggle('active', showPassword);
            profileToggle?.classList.toggle('active', !showPassword);

            if (showPassword) {
                document.querySelectorAll('.employer-account-section-link').forEach(function (link) {
                    link.classList.remove('active');
                });
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
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
            const passwordVisibilityButton = event.target.closest('.employer-account-password-visibility');

            if (passwordVisibilityButton) {
                const input = document.getElementById(passwordVisibilityButton.dataset.passwordTarget);
                const icon = passwordVisibilityButton.querySelector('i');

                if (input) {
                    const showPassword = input.type === 'password';
                    input.type = showPassword ? 'text' : 'password';
                    icon?.classList.toggle('fa-eye', showPassword);
                    icon?.classList.toggle('fa-eye-slash', !showPassword);
                    passwordVisibilityButton.setAttribute(
                        'aria-label',
                        showPassword ? 'Hide password' : 'Show password'
                    );
                }

                return;
            }

            const toggle = event.target.closest('.employer-account-nav-toggle');

            if (toggle) {
                const subnav = document.getElementById(toggle.getAttribute('aria-controls'));
                const passwordPanel = document.getElementById('employerPasswordPanel');

                if (passwordPanel && !passwordPanel.classList.contains('d-none')) {
                    setEmployerPasswordView(false);
                    setActiveAccountSection('companyDetailsPanel');
                    toggle.setAttribute('aria-expanded', 'true');
                    subnav?.classList.remove('is-collapsed');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    return;
                }

                if (subnav) {
                    const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
                    toggle.setAttribute('aria-expanded', String(!isExpanded));
                    subnav.classList.toggle('is-collapsed', isExpanded);
                }
            }

            const sectionLink = event.target.closest('.employer-account-section-link');

            const passwordLink = event.target.closest('.employer-account-password-link');
            if (passwordLink) {
                setEmployerPasswordView(true);
                return;
            }

            if (!sectionLink) {
                return;
            }

            const targetPanel = document.getElementById(sectionLink.dataset.accountSection);

            if (!targetPanel) {
                return;
            }

            setActiveAccountSection(targetPanel.id);
            setEmployerPasswordView(false);

            const profileToggle = document.querySelector('.employer-account-nav-toggle');
            const profileSubnav = document.getElementById('employerProfileSubnav');

            if (profileToggle && profileSubnav) {
                profileToggle.setAttribute('aria-expanded', 'true');
                profileSubnav.classList.remove('is-collapsed');
            }

            updateEmployerAccountHash(employerAccountSectionHashes[targetPanel.id]);
            scrollToEmployerAccountPanel(targetPanel);
        });

        document.addEventListener('DOMContentLoaded', function () {
            const accountPanels = Array.from(document.querySelectorAll('.employer-account-content-panel'));

            if (!accountPanels.length) {
                return;
            }

            const updateActiveSection = function () {
                if (!document.getElementById('employerPasswordPanel').classList.contains('d-none')) {
                    return;
                }

                let currentPanel = accountPanels[0];
                const activeThreshold = getEmployerAccountScrollOffset() + 8;

                accountPanels.forEach(function (panel) {
                    if (panel.getBoundingClientRect().top <= activeThreshold) {
                        currentPanel = panel;
                    }
                });

                setActiveAccountSection(currentPanel.id);
            };

            window.addEventListener('scroll', updateActiveSection, { passive: true });
            window.addEventListener('resize', syncEmployerAccountScrollOffset, { passive: true });
            syncEmployerAccountScrollOffset();
            updateActiveSection();

            const applyAccountHash = function () {
                if (window.location.hash === '#change-password') {
                    setEmployerPasswordView(true);
                    return;
                }

                if (window.location.hash === '#company-details') {
                    setEmployerPasswordView(false);
                    setActiveAccountSection('companyDetailsPanel');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            };

            window.addEventListener('hashchange', applyAccountHash);
            applyAccountHash();

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

                    this.textContent = isEditing ? 'Done' : 'Edit Contact Person';
                    if (isEditing) {
                        editableContactFields[0].focus();
                    }
                });
            }

            const billingPhoneInput = document.getElementById('billingPhoneNumber');
            const billingPrefixCode = document.getElementById('billingPrefixCode');
            const contactPhoneInput = document.getElementById('phoneNumber');
            const normalizePhoneDigits = function (input) {
                if (input) {
                    input.value = input.value.replace(/\D/g, '');
                }
            };

            if (contactPhoneInput) {
                ['input', 'change', 'blur', 'countrychange'].forEach(function (eventName) {
                    contactPhoneInput.addEventListener(eventName, function () {
                        normalizePhoneDigits(contactPhoneInput);
                    });
                });
                setTimeout(function () {
                    normalizePhoneDigits(contactPhoneInput);
                }, 0);
            }

            if (billingPhoneInput && billingPrefixCode && window.intlTelInput) {
                const billingPhone = window.intlTelInput(billingPhoneInput, {
                    initialCountry: 'bd',
                    separateDialCode: true,
                    formatOnDisplay: false,
                    utilsScript: "{{ asset('assets/js/inttel/js/utils.min.js') }}"
                });
                const existingBillingNumber = @json(($company->billing_region_code ?: $user->region_code ?: '880').($company->billing_phone ?: $user->phone ?: ''));

                if (existingBillingNumber) {
                    billingPhone.setNumber('+' + String(existingBillingNumber).replace(/\D/g, ''));
                    normalizePhoneDigits(billingPhoneInput);
                }

                const updateBillingPrefix = function () {
                    billingPrefixCode.value = billingPhone.getSelectedCountryData().dialCode || '880';
                    normalizePhoneDigits(billingPhoneInput);
                };

                billingPhoneInput.addEventListener('input', function () {
                    normalizePhoneDigits(this);
                });
                billingPhoneInput.addEventListener('countrychange', updateBillingPrefix);
                updateBillingPrefix();
            }

            const facilitiesToggleInputs = document.querySelectorAll('[data-facilities-toggle]');
            const disabilityDetails = document.getElementById('employerDisabilityDetails');
            const disabilitySupportQuestion = document.getElementById('employerDisabilitySupportQuestion');
            const disabilityPolicyInputs = document.querySelectorAll('input[name="disability_inclusion_policy"]');

            const updateDisabilitySupportQuestion = function () {
                if (!disabilitySupportQuestion) {
                    return;
                }

                const selectedFacilitiesOption = document.querySelector('[data-facilities-toggle]:checked');
                const selectedPolicy = document.querySelector('input[name="disability_inclusion_policy"]:checked');
                const showSupportQuestion = Boolean(
                    selectedFacilitiesOption && selectedFacilitiesOption.value === '1' &&
                    selectedPolicy && selectedPolicy.value === '0'
                );

                disabilitySupportQuestion.classList.toggle('d-none', !showSupportQuestion);
                disabilitySupportQuestion.querySelectorAll('input').forEach(function (input) {
                    input.disabled = !showSupportQuestion;
                    input.required = showSupportQuestion;
                });
            };

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
                updateDisabilitySupportQuestion();
            };

            facilitiesToggleInputs.forEach(function (input) {
                input.addEventListener('change', updateDisabilityDetails);
            });
            disabilityPolicyInputs.forEach(function (input) {
                input.addEventListener('change', updateDisabilitySupportQuestion);
            });
            updateDisabilityDetails();

            const businessDescription = document.querySelector('#companyDetailsPanel .ql-editor');
            if (businessDescription) {
                businessDescription.setAttribute('data-placeholder', '{{ __('messages.employer_account.business_description') }}');
            }
        });
    </script>
    {{--    <script src="{{mix('assets/js/companies/create-edit.js')}}"></script> --}}
    {{--    <script src="{{ asset('assets/js/companies/companies_stripe_payment.js') }}"></script> --}}
    {{--    <script src="{{ mix('assets/js/custom/phone-number-country-code.js') }}"></script> --}}
@endpush
