document.addEventListener('DOMContentLoaded', loadFrontRegisterData);

function visitRegisterRedirect (url) {
    if (window.Turbo && typeof window.Turbo.visit === 'function') {
        window.Turbo.visit(url);
        return;
    }

    window.location.href = url;
}

function loadFrontRegisterData () {
    if (!$('#addEmployerNewForm').length && !$('#addCandidateNewForm').length) {
        return;
    }

    $('#loginTab a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
// store the currently selected tab in the hash value
    $('ul.nav-tabs > li > a').on('shown.bs.tab', function (e) {
        var id = $(e.target).attr('href').substr(1);
        window.location.hash = id;
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    });
// on load of the page: switch to the currently selected tab
    var hash = window.location.hash;
    // $('#loginTab a[href="' + hash + '"]').tab('show');

    $('#candidate').on('hidden.bs.tab', function () {
        resetModalForm('#candidateForm', '#candidateValidationErrBox');
    });
    $('#employer').on('hidden.bs.tab', function () {
        resetModalForm('#employeeForm', '#employerValidationErrBox');
    });

    loadEmployerRegistrationForm();
    loadCandidateRegistrationForm();
}

function loadCandidateRegistrationForm () {
    const form = document.getElementById('addCandidateNewForm');
    if (!form) {
        return;
    }

    form.addEventListener('blur', function (e) {
        if (e.target && e.target.hasAttribute('required')) {
            if (!e.target.value || !e.target.value.trim() || !e.target.checkValidity()) {
                e.target.classList.add('is-invalid');
            } else {
                e.target.classList.remove('is-invalid');
            }
        }
    }, true);

    form.addEventListener('input', function (e) {
        if (e.target && e.target.classList.contains('is-invalid')) {
            if (e.target.value && e.target.value.trim() && e.target.checkValidity()) {
                e.target.classList.remove('is-invalid');
            }
        }
    });

    form.addEventListener('change', function (e) {
        if (e.target && e.target.classList.contains('is-invalid')) {
            if (e.target.checkValidity()) {
                e.target.classList.remove('is-invalid');
            }
        }
    });
}

function loadEmployerRegistrationForm () {
    const form = document.getElementById('addEmployerNewForm');
    if (!form) {
        return;
    }

    form.addEventListener('blur', function (e) {
        if (e.target && e.target.hasAttribute('required')) {
            if (!e.target.value || !e.target.value.trim() || !e.target.checkValidity()) {
                e.target.classList.add('is-invalid');
            } else {
                e.target.classList.remove('is-invalid');
            }
        }
    }, true);

    form.addEventListener('input', function (e) {
        if (e.target && e.target.classList.contains('is-invalid')) {
            if (e.target.value && e.target.value.trim() && e.target.checkValidity()) {
                e.target.classList.remove('is-invalid');
            }
        }
    });

    form.addEventListener('change', function (e) {
        if (e.target && e.target.classList.contains('is-invalid')) {
            if (e.target.checkValidity()) {
                e.target.classList.remove('is-invalid');
            }
        }
        if (e.target && e.target.name === 'employee_range') {
            const options = form.querySelector('.employer-company-employee-options');
            if (options) options.classList.remove('is-invalid');
        }
        if (e.target && e.target.closest('#registerIndustryOptions')) {
            const options = document.getElementById('registerIndustryOptions');
            if (options) options.classList.remove('is-invalid');
        }
    });

    const username = document.getElementById('employerUsername');
    const usernameFeedback = document.getElementById('employerUsernameFeedback');
    const password = document.getElementById('employerPassword');
    const confirmPassword = document.getElementById('employerConfirmPassword');
    const confirmPasswordFeedback = document.getElementById('employerConfirmPasswordFeedback');
    let usernameTimer = null;
    let usernameRequest = null;

    const showLiveError = function (input, feedback, message) {
        input.classList.toggle('is-invalid', Boolean(message));
        input.setCustomValidity(message || '');
        feedback.textContent = message || '';
    };

    const validatePasswordMatch = function () {
        if (!password || !confirmPassword || !confirmPasswordFeedback) {
            return true;
        }

        const mismatch = confirmPassword.value !== '' && password.value !== confirmPassword.value;
        showLiveError(
            confirmPassword,
            confirmPasswordFeedback,
            mismatch ? 'Passwords do not match' : ''
        );

        return !mismatch;
    };

    if (password && confirmPassword && confirmPasswordFeedback) {
        password.addEventListener('input', validatePasswordMatch);
        confirmPassword.addEventListener('input', validatePasswordMatch);
    }

    if (username && usernameFeedback) {
        form.dataset.usernameAvailable = 'unchecked';

        username.addEventListener('input', function () {
            const value = this.value.trim();
            window.clearTimeout(usernameTimer);
            if (usernameRequest) {
                usernameRequest.abort();
                usernameRequest = null;
            }

            form.dataset.usernameAvailable = 'unchecked';
            showLiveError(username, usernameFeedback, '');

            if (!value || !/^[\p{L}\p{M}\p{N}._-]+$/u.test(value)) {
            if (!value || !/^[\p{L}\p{M}\p{N}._-]+$/u.test(value)) {
                return;
            }

            usernameTimer = window.setTimeout(function () {
                usernameRequest = new AbortController();
                form.dataset.usernameAvailable = 'checking';

                fetch(route('register.username-availability') + '?username=' + encodeURIComponent(value), {
                    headers: { 'Accept': 'application/json' },
                    signal: usernameRequest.signal
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Username availability check failed.');
                        }
                        return response.json();
                    })
                    .then(result => {
                        if (username.value.trim() !== value) {
                            return;
                        }

                        const available = Boolean(result.available);
                        form.dataset.usernameAvailable = available ? 'true' : 'false';
                        showLiveError(
                            username,
                            usernameFeedback,
                            available ? '' : (result.message || 'This Username already exists. Try another.')
                        );
                    })
                    .catch(error => {
                        if (error.name !== 'AbortError' && username.value.trim() === value) {
                            form.dataset.usernameAvailable = 'unchecked';
                            showLiveError(username, usernameFeedback, '');
                        }
                    })
                    .finally(() => {
                        usernameRequest = null;
                    });
            }, 450);
        });

        if (username.value.trim()) {
            username.dispatchEvent(new Event('input'));
        }
    }

    const country = document.getElementById('registerCountryId');
    const state = document.getElementById('registerStateId');
    const city = document.getElementById('registerCityId');
    const countryFlag = document.querySelector('.employer-register-bd-flag');

    const fillSelect = function (select, items, placeholder, selectedValue) {
        select.innerHTML = '';
        select.append(new Option(placeholder, ''));
        Object.entries(items || {}).forEach(function ([value, text]) {
            select.append(new Option(text, value, false, String(value) === String(selectedValue || '')));
        });
        select.disabled = false;
    };

    const loadCities = function (stateId, selectedCity) {
        if (!stateId) {
            fillSelect(city, {}, 'Select Thana');
            city.disabled = true;
            return;
        }

        city.disabled = true;
        fetch(route('register.cities') + '?state_id=' + encodeURIComponent(stateId), {
            headers: { 'Accept': 'application/json' }
        })
            .then(response => response.json())
            .then(result => fillSelect(city, result.data, 'Select Thana', selectedCity))
            .catch(() => fillSelect(city, {}, 'Select Thana'));
    };

    if (country && state && city) {
        const updateCountryFlag = function () {
            if (countryFlag) {
                countryFlag.classList.toggle('d-none', country.value !== country.dataset.bangladeshId);
            }
        };

        country.addEventListener('change', function () {
            updateCountryFlag();
            state.disabled = true;
            city.disabled = true;
            fetch(route('register.states') + '?country_id=' + encodeURIComponent(this.value), {
                headers: { 'Accept': 'application/json' }
            })
                .then(response => response.json())
                .then(result => {
                    fillSelect(state, result.data, 'Select District');
                    fillSelect(city, {}, 'Select Thana');
                    city.disabled = true;
                })
                .catch(() => fillSelect(state, {}, 'Select District'));
        });

        state.addEventListener('change', function () {
            loadCities(this.value);
        });

        updateCountryFlag();
        if (state.value) {
            loadCities(state.value, city.dataset.oldCityId);
        }
    }

    const industryType = document.getElementById('registerIndustryType');
    const industrySearch = document.getElementById('registerIndustrySearch');
    const industryOptions = document.getElementById('registerIndustryOptions');
    const industryMore = document.getElementById('registerIndustryMore');
    const industryEmpty = document.getElementById('registerIndustryEmpty');
    const industryTags = document.getElementById('registerIndustryTags');
    const customIndustryInputs = document.getElementById('registerCustomIndustryInputs');
    const addIndustryTrigger = document.getElementById('registerAddIndustryTrigger');
    const modalIndustryType = document.getElementById('registerModalIndustryType');
    const modalIndustryName = document.getElementById('registerModalIndustryName');
    const modalIndustryError = document.getElementById('registerIndustryModalError');
    const addIndustryButton = document.getElementById('registerAddIndustryButton');
    const addIndustryModal = document.getElementById('registerAddIndustryModal');
    let customIndustrySequence = 0;

    if (addIndustryModal && addIndustryModal.parentElement !== document.body) {
        document.body.appendChild(addIndustryModal);
    }

    if (addIndustryModal) {
        addIndustryModal.addEventListener('show.bs.modal', function () {
            document.body.classList.add('employer-register-industry-modal-open');
        });
        addIndustryModal.addEventListener('hidden.bs.modal', function () {
            document.body.classList.remove('employer-register-industry-modal-open');
        });
    }

    const refreshIndustries = function (resetExpansion) {
        if (!industryType || !industryOptions) {
            return;
        }

        if (resetExpansion) {
            industryOptions.classList.remove('is-expanded');
        }

        const typeId = industryType.value;
        const query = industrySearch.value.trim().toLowerCase();
        let matched = 0;

        industryOptions.querySelectorAll('label[data-industry-name]').forEach(function (option) {
            const isMatch = (typeId === 'all' || option.dataset.industryTypeId === typeId) &&
                (!query || option.dataset.industryName.includes(query));
            option.classList.toggle('is-filtered-out', !isMatch);
            option.classList.remove('is-extra');
            if (isMatch) {
                option.classList.toggle('is-extra', matched >= 9);
                matched += 1;
            }
        });

        industryEmpty.classList.toggle('d-none', matched !== 0);
        industryMore.classList.toggle('d-none', matched <= 9);
        industryMore.textContent = industryOptions.classList.contains('is-expanded') ? 'See less' : 'See more';
    };

    const updateIndustryPicker = function () {
        if (!industryOptions || !industryTags || !customIndustryInputs) {
            return;
        }

        const selectedCheckboxes = Array.from(
            industryOptions.querySelectorAll('input[type="checkbox"]:checked')
        );

        industryTags.innerHTML = '';
        customIndustryInputs.innerHTML = '';
        let customInputIndex = 0;

        selectedCheckboxes.forEach(function (checkbox) {
            const option = checkbox.closest('label[data-industry-name]');
            if (!option) {
                return;
            }

            if (!checkbox.dataset.optionKey) {
                checkbox.dataset.optionKey = 'existing-' + checkbox.value;
            }

            const tag = document.createElement('span');
            tag.dataset.optionKey = checkbox.dataset.optionKey;
            tag.append(document.createTextNode(option.querySelector('span').textContent.trim() + ' '));
            const removeIcon = document.createElement('i');
            removeIcon.className = 'fa-solid fa-xmark';
            tag.append(removeIcon);
            industryTags.append(tag);

            if (checkbox.dataset.customIndustry === 'true') {
                const typeInput = document.createElement('input');
                typeInput.type = 'hidden';
                typeInput.name = 'custom_industries[' + customInputIndex + '][industry_type_id]';
                typeInput.value = option.dataset.industryTypeId;

                const nameInput = document.createElement('input');
                nameInput.type = 'hidden';
                nameInput.name = 'custom_industries[' + customInputIndex + '][name]';
                nameInput.value = option.querySelector('span').textContent.trim();

                customIndustryInputs.append(typeInput, nameInput);
                customInputIndex += 1;
            }
        });
    };

    if (industryType && industrySearch && industryOptions) {
        industryOptions.addEventListener('change', function (event) {
            if (event.target.matches('input[type="checkbox"]')) {
                updateIndustryPicker();
            }
        });
        industryType.addEventListener('change', function () {
            industrySearch.value = '';
            refreshIndustries(true);
        });
        industrySearch.addEventListener('input', function () {
            refreshIndustries(true);
        });
        industryMore.addEventListener('click', function () {
            industryOptions.classList.toggle('is-expanded');
            this.textContent = industryOptions.classList.contains('is-expanded') ? 'See less' : 'See more';
        });
        refreshIndustries(true);
        updateIndustryPicker();
    }

    if (industryTags && industryOptions) {
        industryTags.addEventListener('click', function (event) {
            const removeIcon = event.target.closest('.fa-xmark');
            if (!removeIcon) {
                return;
            }

            const tag = removeIcon.closest('[data-option-key]');
            const checkbox = tag
                ? Array.from(industryOptions.querySelectorAll('input[type="checkbox"]'))
                    .find(input => input.dataset.optionKey === tag.dataset.optionKey)
                : null;

            if (checkbox) {
                checkbox.checked = false;
                updateIndustryPicker();
            }
        });
    }

    if (addIndustryTrigger && modalIndustryType && modalIndustryName && modalIndustryError) {
        addIndustryTrigger.addEventListener('click', function () {
            const selectedType = industryType ? industryType.value : '';
            const selectedTypeExists = Array.from(modalIndustryType.options)
                .some(option => option.value === selectedType);
            modalIndustryType.value = selectedTypeExists
                ? selectedType
                : (modalIndustryType.options[0] ? modalIndustryType.options[0].value : '');
            modalIndustryName.value = '';
            modalIndustryError.classList.add('d-none');
            modalIndustryError.textContent = '';
            setTimeout(function () {
                modalIndustryName.focus();
            }, 350);
        });
    }

    const addRegistrationIndustry = function () {
        if (!addIndustryButton || !industryOptions || !modalIndustryType || !modalIndustryName) {
            return;
        }

        const industryName = modalIndustryName.value.trim();
        const normalizedName = industryName.toLowerCase();
        modalIndustryError.classList.add('d-none');
        modalIndustryError.textContent = '';

        if (!modalIndustryType.value) {
            modalIndustryError.textContent = 'Please select an industry type.';
            modalIndustryError.classList.remove('d-none');
            return;
        }

        if (!industryName) {
            modalIndustryError.textContent = 'Please enter your industry name.';
            modalIndustryError.classList.remove('d-none');
            modalIndustryName.focus();
            return;
        }

        const duplicateOption = Array.from(industryOptions.querySelectorAll('label[data-industry-name]'))
            .find(option => option.dataset.industryName === normalizedName);
        if (duplicateOption) {
            modalIndustryError.textContent = 'This industry already exists.';
            modalIndustryError.classList.remove('d-none');
            return;
        }

        customIndustrySequence += 1;
        const option = document.createElement('label');
        option.dataset.industryName = normalizedName;
        option.dataset.industryTypeId = modalIndustryType.value;

        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.checked = true;
        checkbox.dataset.customIndustry = 'true';
        checkbox.dataset.optionKey = 'custom-' + customIndustrySequence;

        const labelText = document.createElement('span');
        labelText.textContent = industryName;
        option.append(checkbox, labelText);
        industryOptions.append(option);

        industryType.value = modalIndustryType.value;
        industrySearch.value = '';
        updateIndustryPicker();
        refreshIndustries(true);

        const modalInstance = window.bootstrap && addIndustryModal
            ? window.bootstrap.Modal.getInstance(addIndustryModal)
            : null;
        if (modalInstance) {
            modalInstance.hide();
        } else if (addIndustryModal) {
            const closeButton = addIndustryModal.querySelector('[data-bs-dismiss="modal"]');
            if (closeButton) {
                closeButton.click();
            }
        }
    };

    if (addIndustryButton && modalIndustryName) {
        addIndustryButton.addEventListener('click', addRegistrationIndustry);
        modalIndustryName.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                addRegistrationIndustry();
            }
        });
    }

    const phoneInput = document.getElementById('employerRegisterPhone');
    const regionCode = document.getElementById('employerRegisterRegionCode');
    if (phoneInput && regionCode && window.intlTelInput) {
        const phone = window.intlTelInput(phoneInput, {
            initialCountry: 'bd',
            separateDialCode: true,
            utilsScript: '/assets/js/inttel/js/utils.min.js'
        });
        const existingNumber = String(regionCode.value || '880') + String(phoneInput.value || '');
        if (phoneInput.value) {
            phone.setNumber('+' + existingNumber.replace(/\D/g, ''));
        }
        const syncRegionCode = function () {
            regionCode.value = phone.getSelectedCountryData().dialCode || '880';
        };
        phoneInput.addEventListener('countrychange', syncRegionCode);
        phoneInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '');
        });
        syncRegionCode();
    }

    const disabilityFacilitiesToggle = document.querySelector('[data-register-facilities-toggle]');
    const disabilityDetails = document.getElementById('registerDisabilityDetails');
    const disabilitySupportQuestion = document.getElementById('registerDisabilitySupportQuestion');
    const disabilityPolicyInputs = document.querySelectorAll('input[name="disability_inclusion_policy"]');
    const updateDisabilitySupportQuestion = function () {
        if (!disabilitySupportQuestion) {
            return;
        }

        const selectedPolicy = document.querySelector('input[name="disability_inclusion_policy"]:checked');
        const showSupportQuestion = Boolean(
            disabilityFacilitiesToggle && disabilityFacilitiesToggle.checked &&
            selectedPolicy && selectedPolicy.value === '0'
        );
        disabilitySupportQuestion.classList.toggle('d-none', !showSupportQuestion);
        disabilitySupportQuestion.querySelectorAll('input').forEach(function (input) {
            input.disabled = !showSupportQuestion;
            input.required = showSupportQuestion;
        });
    };
    const updateDisabilityDetails = function () {
        if (!disabilityFacilitiesToggle || !disabilityDetails) {
            return;
        }

        const showDetails = disabilityFacilitiesToggle.checked;
        disabilityDetails.classList.toggle('d-none', !showDetails);
        disabilityDetails.querySelectorAll('input').forEach(function (input) {
            input.disabled = !showDetails;
        });
        disabilityDetails.querySelectorAll(
            'input[name="disability_inclusion_policy"], input[name="disability_inclusion_training"]'
        ).forEach(function (input) {
            input.required = showDetails;
        });
        updateDisabilitySupportQuestion();
    };

    if (disabilityFacilitiesToggle && disabilityDetails) {
        disabilityFacilitiesToggle.addEventListener('change', updateDisabilityDetails);
        disabilityPolicyInputs.forEach(function (input) {
            input.addEventListener('change', updateDisabilitySupportQuestion);
        });
        updateDisabilityDetails();
    }

    const pricingPolicyCard = document.getElementById('employerPricingPolicyCard');
    const pricingPolicyToggle = document.getElementById('employerPricingPolicyToggle');
    const pricingPolicyContent = document.getElementById('employerPricingPolicyContent');
    if (pricingPolicyCard && pricingPolicyToggle && pricingPolicyContent) {
        pricingPolicyToggle.addEventListener('click', function () {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', String(!isExpanded));
            pricingPolicyContent.hidden = isExpanded;
            pricingPolicyCard.classList.toggle('is-collapsed', isExpanded);
        });
    }
}

listenSubmit('#addCandidateNewForm', function (e) {
    e.preventDefault();

    const candidateForm = this;
    candidateForm.querySelectorAll('.is-invalid').forEach(function (input) {
        input.classList.remove('is-invalid');
    });

    let isValid = true;
    let firstInvalidElement = null;

    const requiredControls = candidateForm.querySelectorAll('input[required], select[required], textarea[required]');
    requiredControls.forEach(function (control) {
        if (control.disabled) return;

        if (control.type === 'checkbox') {
            if (!control.checked) {
                isValid = false;
                control.classList.add('is-invalid');
                if (!firstInvalidElement) firstInvalidElement = control;
            }
        } else {
            if (!control.value || !control.value.trim() || !control.checkValidity()) {
                isValid = false;
                control.classList.add('is-invalid');
                if (!firstInvalidElement) firstInvalidElement = control;
            }
        }
    });

    const password = document.getElementById('candidatePassword');
    const confirmPassword = document.getElementById('candidateConfirmPassword');
    if (password && confirmPassword && confirmPassword.value !== password.value) {
        isValid = false;
        confirmPassword.classList.add('is-invalid');
        if (!firstInvalidElement) firstInvalidElement = confirmPassword;
    }

    if (!isValid) {
        displayErrorMessage('Please fill in all required fields.');
        if (firstInvalidElement) {
            firstInvalidElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(function () {
                firstInvalidElement.focus();
            }, 100);
        }
        return;
    }

    processingBtn('#addCandidateNewForm', '#btnCandidateSave', 'loading');

    $.ajax({
        url: route('front.save.register'),
        type: 'POST',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                setTimeout(function () {
                    window.location.href = result.data.redirectUrl;
                }, 1500);
            }
        },
        error: function (result) {
            const response = result.responseJSON || {};
            const errors = response.errors || {};
            const firstErrorKey = Object.keys(errors)[0];
            const firstMessage = firstErrorKey && errors[firstErrorKey]
                ? errors[firstErrorKey][0]
                : (response.message || 'Registration could not be completed. Please review the form.');

            if (firstErrorKey) {
                const field = candidateForm.querySelector('[name="' + firstErrorKey + '"]');
                if (field) {
                    field.classList.add('is-invalid');
                    field.focus();
                    field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }

            displayErrorMessage(firstMessage);
        },
        complete: function () {
            processingBtn('#addCandidateNewForm', '#btnCandidateSave');
        },
    });
});

listenSubmit('#addEmployerNewForm', function (e) {
    e.preventDefault();

    const employerForm = this;
    const usernameInput = document.getElementById('employerUsername');
    const usernameFeedback = document.getElementById('employerUsernameFeedback');
    const confirmPasswordInput = document.getElementById('employerConfirmPassword');

    let isValid = true;
    let firstInvalidElement = null;

    employerForm.querySelectorAll('.employer-server-validation-message').forEach(function (message) {
        message.remove();
    });
    employerForm.querySelectorAll('.is-invalid').forEach(function (input) {
        if (!input.matches('#employerUsername, #employerConfirmPassword')) {
            input.classList.remove('is-invalid');
        }
    });

    const requiredControls = employerForm.querySelectorAll('input[required], select[required], textarea[required]');
    requiredControls.forEach(function (control) {
        if (control.disabled) return;

        if (control.type === 'checkbox') {
            if (!control.checked) {
                isValid = false;
                control.classList.add('is-invalid');
                if (!firstInvalidElement) firstInvalidElement = control;
            }
        } else {
            if (!control.value || !control.value.trim() || !control.checkValidity()) {
                isValid = false;
                control.classList.add('is-invalid');
                if (!firstInvalidElement) firstInvalidElement = control;
            }
        }
    });

    if (usernameInput && employerForm.dataset.usernameAvailable === 'false') {
        isValid = false;
        showLiveRegistrationError(
            usernameInput,
            usernameFeedback,
            'This Username already exists. Try another.'
        );
        usernameInput.classList.add('is-invalid');
        if (!firstInvalidElement) firstInvalidElement = usernameInput;
    }

    if (confirmPasswordInput && !confirmPasswordInput.checkValidity()) {
        isValid = false;
        confirmPasswordInput.classList.add('is-invalid');
        if (!firstInvalidElement) firstInvalidElement = confirmPasswordInput;
    }

    const industryOptions = document.getElementById('registerIndustryOptions');
    if (industryOptions && !industryOptions.querySelector('input[type="checkbox"]:checked')) {
        isValid = false;
        industryOptions.classList.add('is-invalid');
        if (!firstInvalidElement) firstInvalidElement = industryOptions;
    }

    if (!isValid) {
        displayErrorMessage('Please fill in all required fields.');
        if (firstInvalidElement) {
            firstInvalidElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(function () {
                firstInvalidElement.focus();
            }, 100);
        }
        return;
    }

    processingBtn('#addEmployerNewForm', '#btnEmployerSave', 'loading');
    //
    // if ($('#isGoogleReCaptchaEnabled').val()) {
    //     if (!checkGoogleReCaptcha(2))
    //         return true;
    // }

    $.ajax({
        url: route('front.save.register'),
        type: 'POST',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                setTimeout(function () {
                    window.location.href = result.data.redirectUrl;
                }, 1500);
            }
        },
        error: function (result) {
            const response = result.responseJSON || {};
            const errors = response.errors || {};
            const firstErrorKey = Object.keys(errors)[0];
            const firstMessage = firstErrorKey && errors[firstErrorKey]
                ? errors[firstErrorKey][0]
                : (response.message || 'Registration could not be completed. Please review the form.');

            if (firstErrorKey) {
                const baseName = firstErrorKey.split('.')[0];
                const field = employerForm.querySelector('[name="' + baseName + '"]') ||
                    employerForm.querySelector('[name="' + baseName + '[]"]');

                if (field && field.type !== 'hidden') {
                    field.classList.add('is-invalid');
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback d-block employer-server-validation-message';
                    feedback.textContent = firstMessage;
                    const fieldContainer = field.closest('.form-group') || field.parentElement;
                    fieldContainer.append(feedback);
                    field.focus();
                    field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else if (baseName === 'industry_ids' || baseName === 'custom_industries') {
                    document.getElementById('registerIndustryOptions').scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }

            displayErrorMessage(firstMessage);
        },
        complete: function () {
            processingBtn('#addEmployerNewForm', '#btnEmployerSave');
        },
    });
});

function showLiveRegistrationError (input, feedback, message) {
    input.classList.add('is-invalid');
    input.setCustomValidity(message);
    if (feedback) {
        feedback.textContent = message;
    }
}
