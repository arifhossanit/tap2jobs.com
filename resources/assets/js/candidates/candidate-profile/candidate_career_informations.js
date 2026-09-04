'use strict';

import "flatpickr/dist/l10n";

function getCandidateProfileAccordionToggle(accordion, sectionId) {
    return Array.from(accordion.querySelectorAll('[data-bs-target]')).find(function (toggle) {
        return toggle.getAttribute('data-bs-target') === '#' + sectionId;
    });
}

function initCandidateProfileAccordion(options) {
    const accordion = document.getElementById(options.accordionId);

    if (!accordion || accordion.dataset.profileAccordionReady === 'true') {
        return;
    }

    accordion.dataset.profileAccordionReady = 'true';

    const sectionBodies = Array.from(accordion.querySelectorAll('.candidate-profile-section__collapse'));
    const menuLinks = options.menuSelector
        ? Array.from(document.querySelectorAll(options.menuSelector))
        : [];

    const setActiveSection = function (panelId, sectionId) {
        if (!options.menuDatasetKey) {
            return;
        }

        menuLinks.forEach(function (link) {
            const linkedSectionId = link.dataset[options.menuDatasetKey];
            link.classList.toggle('active', linkedSectionId === panelId || linkedSectionId === sectionId);
        });
    };

    const setPanelToggleState = function (section, expanded) {
        if (section.classList.contains('collapsing')) {
            return;
        }

        const toggle = getCandidateProfileAccordionToggle(accordion, section.id);
        if (!toggle) {
            return;
        }

        const label = toggle.querySelector('span');
        const header = toggle.closest('.candidate-profile-section__header');
        const panel = section.closest('.candidate-profile-section, .candidate-education-panel');

        toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');

        if (label) {
            label.textContent = expanded
                ? (toggle.dataset.collapseLabel || 'Collapse')
                : (toggle.dataset.expandLabel || 'Expand');
        }

        if (header) {
            header.classList.toggle('collapsed', !expanded);
        }

        if (typeof options.syncHeaderActions === 'function') {
            options.syncHeaderActions(header, expanded, section, panel);
        } else if (header && options.headerActionsSelector) {
            header.querySelectorAll(options.headerActionsSelector).forEach(function (action) {
                action.classList.toggle('d-none', !expanded);
            });
        }

        if (expanded) {
            setActiveSection(panel ? panel.id : null, section.id);
        }
    };

    const refreshSection = function (section) {
        setPanelToggleState(section, section.classList.contains('show'));
    };

    sectionBodies.forEach(function (section) {
        const toggle = getCandidateProfileAccordionToggle(accordion, section.id);
        const header = toggle ? toggle.closest('.candidate-profile-section__header') : null;

        section.addEventListener('show.bs.collapse', function () {
            sectionBodies.forEach(function (otherSection) {
                if (otherSection !== section && otherSection.classList.contains('show') && typeof bootstrap !== 'undefined') {
                    bootstrap.Collapse.getOrCreateInstance(otherSection, { toggle: false }).hide();
                }
            });
            setPanelToggleState(section, true);
        });

        section.addEventListener('shown.bs.collapse', function () {
            setPanelToggleState(section, true);
        });

        section.addEventListener('hide.bs.collapse', function () {
            setPanelToggleState(section, false);
        });

        section.addEventListener('hidden.bs.collapse', function () {
            setPanelToggleState(section, false);
        });

        if (header && toggle) {
            header.addEventListener('click', function (event) {
                if (event.target.closest('button, a, input, select, textarea, label, .ql-toolbar, .ql-container')) {
                    return;
                }

                toggle.click();
            });
        }

        refreshSection(section);

        if (typeof MutationObserver !== 'undefined') {
            const observer = new MutationObserver(function () {
                refreshSection(section);
            });

            observer.observe(section, {
                attributes: true,
                attributeFilter: ['class'],
            });
        }
    });

    const openSection = function (targetId, scrollToSection = true) {
        const target = document.getElementById(targetId);
        const section = target
            ? (target.classList.contains('candidate-profile-section__collapse')
                ? target
                : target.querySelector('.candidate-profile-section__collapse'))
            : null;
        const panel = section
            ? section.closest('.candidate-profile-section, .candidate-education-panel')
            : target;

        if (!target || !section || typeof bootstrap === 'undefined') {
            return;
        }

        bootstrap.Collapse.getOrCreateInstance(section, { toggle: false }).show();
        setActiveSection(panel ? panel.id : null, section.id);

        if (!scrollToSection) {
            return;
        }

        if (typeof window.scrollCandidateProfileSection === 'function') {
            window.scrollCandidateProfileSection(panel || section);
        } else {
            (panel || section).scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    };

    const hashTargetId = window.location.hash ? window.location.hash.substring(1) : '';
    if (hashTargetId) {
        setTimeout(function () {
            openSection(hashTargetId, true);
        }, 0);
    }

    menuLinks.forEach(function (link) {
        link.addEventListener('click', function (event) {
            event.preventDefault();
            openSection(link.dataset[options.menuDatasetKey], true);
        });
    });
}

function initCandidateEducationAccordion() {
    initCandidateProfileAccordion({
        accordionId: 'candidateEducationAccordion',
        menuSelector: '[data-career-section-link]',
        menuDatasetKey: 'careerSectionLink',
        headerActionsSelector: '[data-panel-add-action], [data-certification-add-action]',
    });
}

function initCandidateEmploymentAccordion() {
    initCandidateProfileAccordion({
        accordionId: 'candidateEmploymentAccordion',
        menuSelector: '[data-employment-section-link]',
        menuDatasetKey: 'employmentSectionLink',
        syncHeaderActions: function (header, expanded) {
            if (!header) {
                return;
            }

            header.querySelectorAll('[data-employment-add-action]').forEach(function (action) {
                action.classList.toggle('d-none', !expanded);
            });

            const retiredArmyAddAction = header.querySelector('[data-retired-army-add-action]');
            if (retiredArmyAddAction) {
                retiredArmyAddAction.classList.toggle('d-none', !expanded);
            }
        },
    });
}

function initCandidateOtherInformationAccordion() {
    initCandidateProfileAccordion({
        accordionId: 'candidateOtherInformationAccordion',
        menuSelector: '[data-other-section-link]',
        menuDatasetKey: 'otherSectionLink',
        headerActionsSelector: '[data-skill-add-action], [data-activity-add-action], [data-language-edit-action], [data-reference-add-action]',
        syncHeaderActions: function (header, expanded) {
            if (!header) {
                return;
            }

            header.querySelectorAll('[data-skill-add-action], [data-activity-add-action], [data-language-edit-action], [data-reference-add-action]').forEach(function (action) {
                action.classList.toggle('d-none', !expanded);
            });

            const linkAddAction = header.querySelector('[data-link-add-action]');
            if (linkAddAction) {
                linkAddAction.dataset.sectionOpen = expanded ? 'true' : 'false';
                linkAddAction.classList.toggle('d-none', !expanded || document.querySelectorAll('[data-link-item]').length >= 5);
            }
        },
    });
}

function initCandidateAccomplishmentAccordion() {
    const itemLimits = [
        { action: '[data-portfolio-add-action]', item: '[data-portfolio-item]', max: 2 },
        { action: '[data-publication-add-action]', item: '[data-publication-item]', max: 5 },
        { action: '[data-award-add-action]', item: '[data-award-item]', max: 5 },
        { action: '[data-project-add-action]', item: '[data-project-item]', max: 5 },
        { action: '[data-other-add-action]', item: '[data-other-item]', max: 5 },
    ];

    initCandidateProfileAccordion({
        accordionId: 'candidateAccomplishmentAccordion',
        menuSelector: '[data-accomplishment-section-link]',
        menuDatasetKey: 'accomplishmentSectionLink',
        syncHeaderActions: function (header, expanded) {
            if (!header) {
                return;
            }

            itemLimits.forEach(function (limit) {
                const action = header.querySelector(limit.action);
                if (!action) {
                    return;
                }

                action.classList.toggle('d-none', !expanded || document.querySelectorAll(limit.item).length >= limit.max);
            });
        },
    });
}

function initCandidatePersonalInformationAccordion() {
    initCandidateProfileAccordion({
        accordionId: 'candidateProfileAccordion',
        menuSelector: '[data-profile-section-link]',
        menuDatasetKey: 'profileSectionLink',
        syncHeaderActions: function (header, expanded) {
            if (!header) {
                return;
            }

            const editAction = header.querySelector('.candidate-section-edit-action, .candidate-personal-edit-action, .candidate-address-edit-action');
            if (editAction) {
                editAction.classList.toggle('d-none', !expanded || header.classList.contains('candidate-profile-section__header--editing'));
            }
        },
    });
}

function bootCandidateProfileActionScroll() {
    if (document.body.dataset.candidateProfileActionScrollReady === 'true') {
        return;
    }

    document.body.dataset.candidateProfileActionScrollReady = 'true';

    const actionSelector = [
        '[data-personal-edit-toggle]',
        '[data-address-edit-toggle]',
        '[data-career-edit-toggle]',
        '[data-preferred-edit-toggle]',
        '[data-relevant-edit-toggle]',
        '[data-disability-edit-toggle]',
        '[data-inline-education-add]',
        '[data-panel-add-action]',
        '[data-certification-add-action]',
        '[data-employment-add-trigger]',
        '.candidate-employment-edit-trigger',
        '[data-retired-army-add-trigger]',
        '[data-retired-army-edit]',
        '[data-skill-add-action]',
        '[data-skill-edit]',
        '[data-activity-add-action]',
        '[data-activity-edit]',
        '[data-language-edit-action]',
        '[data-language-item-edit]',
        '[data-link-add-action]',
        '[data-link-edit]',
        '[data-reference-add-action]',
        '[data-reference-edit]',
        '[data-portfolio-add-action]',
        '[data-portfolio-edit]',
        '[data-publication-add-action]',
        '[data-publication-edit]',
        '[data-award-add-action]',
        '[data-award-edit]',
        '[data-project-add-action]',
        '[data-project-edit]',
        '[data-other-add-action]',
        '[data-other-edit]',
    ].join(',');

    const formSelector = [
        '.candidate-personal-form',
        '.candidate-address-form',
        '.candidate-career-form',
        '.candidate-preferred-form',
        '.candidate-relevant-form',
        '.candidate-disability-form',
        '.candidate-education-inline-form',
        '.candidate-employment-form',
        '.candidate-retired-army-form',
        '.candidate-skill-form',
        '.candidate-link-form',
        '.candidate-reference-form',
        '.candidate-activity-form',
        '.candidate-language-form',
        '.candidate-portfolio-form',
        '.candidate-publication-form',
        '.candidate-award-form',
        '.candidate-project-form',
        '.candidate-other-form',
        'form',
    ].join(',');

    const isVisible = function (element) {
        return element && element.offsetParent !== null && !element.classList.contains('d-none') && !element.closest('.d-none');
    };

    document.addEventListener('click', function (event) {
        const trigger = event.target.closest(actionSelector);
        if (!trigger) {
            return;
        }

        window.setTimeout(function () {
            const section = trigger.closest('.candidate-profile-section, .candidate-education-panel');
            if (!section) {
                return;
            }

            const visibleForm = Array.from(section.querySelectorAll(formSelector)).find(isVisible);
            if (!visibleForm) {
                return;
            }

            if (typeof window.scrollCandidateProfileSection === 'function') {
                window.scrollCandidateProfileSection(visibleForm);
                return;
            }

            visibleForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 120);
    });
}
function bootCandidateCareerInformationData() {
    bootCandidateProfileActionScroll();
    initCandidateEducationAccordion();
    initCandidateEmploymentAccordion();
    initCandidateOtherInformationAccordion();
    initCandidateAccomplishmentAccordion();
    initCandidatePersonalInformationAccordion();

    const pageMarker = document.getElementById('indexCareerInfoData');

    if (!pageMarker || pageMarker.dataset.educationUiInitialized === 'true') {
        return;
    }

    pageMarker.dataset.educationUiInitialized = 'true';
    loadCandidateCareerInformationData();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bootCandidateCareerInformationData, { once: true });
} else {
    bootCandidateCareerInformationData();
}

document.addEventListener('turbo:load', bootCandidateCareerInformationData);

function loadCandidateCareerInformationData() {

    if (!$('#indexCareerInfoData').length) {
        return
    }
    function scrollToEducationInlineForm(targetElement) {
        const formElement = targetElement || document.querySelector('[data-education-add-form]') || document.querySelector('[data-training-add-form]');
        if (!formElement) return;

        if (typeof window.scrollCandidateProfileSection === 'function') {
            window.scrollCandidateProfileSection(formElement);
            return;
        }

        formElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function reloadCandidateProfileSection(section, panelId) {
        window.location.href = route('candidate.profile', { section: section }) + (panelId ? '#' + panelId : '');
    }

    const educationCustomSelectSelector = '.candidate-education-form-grid select.form-select:not([data-education-major-select])';

    function closeEducationCustomSelects($except = $()) {
        $('.candidate-education-custom-select.is-open').not($except).removeClass('is-open');
    }

    function refreshEducationCustomSelect($select) {
        const $customSelect = $select.next('.candidate-education-custom-select');

        if (!$customSelect.length) {
            return;
        }

        const selectedText = $select.find('option:selected').text() || $select.find('option:first').text() || '';
        const $menu = $customSelect.find('[data-education-custom-select-menu]');

        $customSelect
            .toggleClass('is-disabled', $select.prop('disabled'))
            .find('[data-education-custom-select-value]')
            .text(selectedText);
        $customSelect.find('[data-education-custom-select-toggle]').prop('disabled', $select.prop('disabled'));
        $menu.empty();

        $select.find('option').each(function () {
            const $option = $(this);
            const isSelected = $option.val() === $select.val();
            const $item = $('<button type="button" class="candidate-education-custom-select__option"></button>');

            $item
                .attr('data-education-custom-select-option', $option.val())
                .toggleClass('is-selected', isSelected)
                .prop('disabled', $option.prop('disabled'))
                .text($option.text());
            $menu.append($item);
        });
    }

    function initEducationCustomSelects($scope) {
        $scope.find(educationCustomSelectSelector).each(function () {
            const $select = $(this);

            if ($select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }

            $select.addClass('candidate-education-native-select');

            if (!$select.next('.candidate-education-custom-select').length) {
                $select.after(
                    '<div class="candidate-education-custom-select">' +
                        '<button type="button" class="candidate-education-custom-select__toggle" data-education-custom-select-toggle>' +
                            '<span data-education-custom-select-value></span>' +
                            '<i class="fa-solid fa-chevron-down"></i>' +
                        '</button>' +
                        '<div class="candidate-education-custom-select__menu" data-education-custom-select-menu></div>' +
                    '</div>'
                );
            }

            refreshEducationCustomSelect($select);
        });
    }

    if ($('#countryId').length) {
        $('#countryId').select2({
            dropdownParent: $('#addExperienceModal')
        });
    }
    // $('#educationCountryId').select2({
    //     dropdownParent: $('#addEducationModal')
    // });
    if ($('#addEducationModal').length) {
        $('#educationYearId,#degreeLevelId,#educationCountryId').select2({
            dropdownParent: $('#addEducationModal')
        });

        $('#educationStateId').select2({
            'width': '100%',
            'placeholder': Lang.get('js.select_state'),
            dropdownParent: $('#addEducationModal')
        });
        $('#educationCityId').select2({
            'width': '100%',
            'placeholder': Lang.get('js.select_city'),
            dropdownParent: $('#addEducationModal')
        });
    }
    if ($('#editEducationModal').length) {
        $('#editYear,#editDegreeLevel,#editEducationCountry, #editEducationState, #editEducationCity').select2({
            dropdownParent: $('#editEducationModal')
        });
    }
    initEducationCustomSelects($(document));

    if ($('#editExperienceModal').length) {
        $('#editCountry, #editState, #editCity').select2({
            dropdownParent: $('#editExperienceModal')
        });
    }

    if ($('#addExperienceModal').length) {
        $('#stateId').select2({
            'width': '100%',
            'placeholder': Lang.get('js.select_state'),
            dropdownParent: $('#addExperienceModal')
        });
        $('#cityId').select2({ 'width': '100%', 'placeholder': Lang.get('js.select_city'), dropdownParent: $('#addExperienceModal') });
    }
    // $('#editEducationCountry, #editEducationState, #editEducationCity').select2({
    //     dropdownParent: $('#editEducationModal')
    // });

    // $('#degreeLevelId').select2({
    //     'width': '100%',
    // });

    listenShowBsModal('#editExperienceModal', function () {
        let minDate = $('#editStartDate').val();
        setDatePicker('#editStartDate', '#editEndDate', minDate);
    });

    window.setDatePicker = function (
        startDateExperience, endDateExperience, minDate = null) {
        let startpicker = $(startDateExperience).flatpickr({
            format: 'YYYY-MM-DD',
            useCurrent: true,
            sideBySide: true,
            "locale": getLoggedInUserLang,
            maxDate: new Date(),
            onChange: function (selectedDates, dateStr, instance) {
                endpicker.clear();
                endpicker.set('minDate', dateStr);
            },
        });
        let endpicker = $(endDateExperience).flatpickr({
            format: 'YYYY-MM-DD',
            sideBySide: true,
            maxDate: new Date(),
            useCurrent: false,
            "locale": getLoggedInUserLang,
            minDate: minDate,
        });
    };
    //
    // window.setExperienceSelect2 = function () {
    //     $('#stateId').select2({
    //         'width': '100%',
    //         'placeholder': 'Select State',
    //         dropdownParent: $('#addExperienceModal')
    //     });
    //     $('#cityId').select2({'width': '100%', 'placeholder': 'Select District', dropdownParent: $('#addExperienceModal')});
    // };
    //
    // window.setEducationSelect2 = function () {
    //     $('#educationStateId').select2({
    //         'width': '100%',
    //         'placeholder': 'Select State',
    //         dropdownParent: $('#addEducationModal')
    //     });
    //     $('#educationCityId').select2({
    //         'width': '100%',
    //         'placeholder': 'Select District',
    //         dropdownParent: $('#addEducationModal')
    //     });
    // };

    $('#default').on('click', function () {
        if ($(this).prop('checked') == true) {
            $('#endDateExperience').prop('disabled', true);
            $('#endDateExperience').val('');
            $('#endDateExperience').val('').removeAttr('required', false);
            $('#requiredText').addClass('d-none');
        } else if ($(this).prop('checked') == false) {
            $('#endDateExperience').val('').attr('required', true);
            $('#requiredText').removeClass('d-none');
            $('#endDateExperience').prop('disabled', false);
        }
    });
    $('#editWorking').on('click', function () {
        if ($(this).prop('checked') == true) {
            $('#editEndDate').prop('disabled', true);
            $('#editEndDate').val('');
            $('#editEndDate').val('').removeAttr('required', false);
            $('#editRequiredText').addClass('d-none');
        } else if ($(this).prop('checked') == false) {
            $('#editEndDate').val('').attr('required', true);
            $('#editRequiredText').removeClass('d-none');
            $('#editEndDate').prop('disabled', false);
        }
    });

    listenClick('.addExperienceModal', function () {
        // setExperienceSelect2();
        $('#addExperienceModal').appendTo('body').modal('show');
    });

    listenClick('.addEducationModal', function () {
        // setEducationSelect2();
        $('#addEducationModal').appendTo('body').modal('show');
        initEducationCustomSelects($('#addEducationModal'));
    });

    listenClick('[data-education-custom-select-toggle]', function (event) {
        const $customSelect = $(event.currentTarget).closest('.candidate-education-custom-select');
        const $select = $customSelect.prev('select');

        if ($select.prop('disabled')) {
            return;
        }

        refreshEducationCustomSelect($select);
        closeEducationCustomSelects($customSelect);
        $customSelect.toggleClass('is-open');
    });

    listenClick('[data-education-custom-select-option]', function (event) {
        const $option = $(event.currentTarget);
        const $customSelect = $option.closest('.candidate-education-custom-select');
        const $select = $customSelect.prev('select');

        $select.val($option.attr('data-education-custom-select-option')).trigger('change');
        refreshEducationCustomSelect($select);
        $customSelect.removeClass('is-open');
    });

    $(document).on('click', function (event) {
        if (!$(event.target).closest('.candidate-education-custom-select').length) {
            closeEducationCustomSelects();
        }
    });

    function getCandidateProfileNumber(number) {
        const value = String(number);

        if (!window.candidateProfileUseBanglaNumber) {
            return value;
        }

        const banglaNumbers = {
            0: '০',
            1: '১',
            2: '২',
            3: '৩',
            4: '৪',
            5: '৫',
            6: '৬',
            7: '৭',
            8: '৮',
            9: '৯',
        };

        return value.replace(/[0-9]/g, function (digit) {
            return banglaNumbers[digit];
        });
    }

    function setEducationFormTitle(formSelector, number) {
        const educationLabel = window.candidateProfileEducationLabel || 'Education';
        $(formSelector).find('[data-education-form-title]').text(educationLabel + ' ' + getCandidateProfileNumber(number));
    }

    let activeEducationItem = null;

    function restoreEducationActiveItem() {
        if (!activeEducationItem || !activeEducationItem.length) {
            return;
        }

        activeEducationItem.find('.candidate-education-detail-grid, .candidate-education-detail--full').removeClass('d-none');
        activeEducationItem.find('.candidate-education-item__actions').removeClass('d-none');
        activeEducationItem = null;
    }

    function closeEducationInlineForms() {
        restoreEducationActiveItem();
        $('[data-education-add-form], [data-education-edit-form]').addClass('d-none');
        $('[data-education-add-form], [data-education-edit-form]').removeClass('candidate-training-form--add candidate-training-form--edit');
        $('[data-education-form-title]').removeClass('d-none');
        $('.candidate-education-container').removeClass('d-none');
    }

    function scrollToEducationInlineForm() {
        const form = document.querySelector('[data-education-add-form]') || document.querySelector('[data-education-edit-form]:not(.d-none)');

        if (!form) {
            return;
        }

        window.setTimeout(function () {
            if (typeof window.scrollCandidateProfileSection === 'function') {
                window.scrollCandidateProfileSection(form);
                return;
            }

            form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 50);
    }

    listenClick('[data-inline-education-add]', function () {
        closeEducationInlineForms();
        $('[data-education-edit-form]').addClass('d-none');
        $('.candidate-education-container').after($('[data-education-add-form]'));
        $('[data-education-add-form]').addClass('candidate-training-form--add').removeClass('d-none');
        setEducationFormTitle('[data-education-add-form]', $('.candidate-education-container .candidate-education').length + 1);
        updateEducationFormLayout('#addNewEducationForm');
        initEducationQuillEditors();
        scrollToEducationInlineForm();
    });

    listenClick('[data-education-add-close], [data-education-edit-close]', function () {
        closeEducationInlineForms();
    });

    const educationQuillEditors = [];

    function initEducationQuillEditors() {
        if (typeof Quill === 'undefined') {
            return;
        }

        document.querySelectorAll('[data-quill-editor]').forEach(function (element) {
            if (element.dataset.quillReady === 'true') {
                return;
            }

            const input = element.closest('.candidate-education-editor').querySelector('[data-quill-input]');
            const quill = new Quill(element, {
                modules: {
                    toolbar: [
                        ['bold', 'italic'],
                        [{ list: 'bullet' }],
                    ],
                    keyboard: {
                        bindings: {
                            tab: 'disabled',
                        },
                    },
                },
                placeholder: element.dataset.placeholder || '',
                theme: 'snow',
            });

            if (input && input.value) {
                quill.root.innerHTML = input.value;
            }

            quill.on('text-change', function () {
                if (input) {
                    input.value = quill.getText().trim().length ? quill.root.innerHTML : '';
                }
            });

            element.dataset.quillReady = 'true';
            educationQuillEditors.push({ quill, input });
        });
    }

    function syncEducationQuillEditors() {
        educationQuillEditors.forEach(function (editor) {
            if (editor.input) {
                editor.input.value = editor.quill.getText().trim().length ? editor.quill.root.innerHTML : '';
            }
        });
    }
    window.syncEducationQuillEditors = syncEducationQuillEditors;

    function setEducationQuillValue(formSelector, value) {
        const input = document.querySelector(formSelector + ' [name="achievement"]');

        if (!input) {
            return;
        }

        input.value = value || '';
        initEducationQuillEditors();

        const editor = educationQuillEditors.find(function (educationEditor) {
            return educationEditor.input === input;
        });

        if (editor) {
            editor.quill.root.innerHTML = value || '';
        }
    }

    initEducationQuillEditors();

    const educationExamTitleOptions = window.candidateEducationExamTitleOptions || {};
    const educationMajorGroupOptions = window.candidateEducationMajorGroupOptions || {};
    const educationLevelMeta = window.candidateEducationLevelMeta || {};
    const hiddenEducationFieldSelectors = [
        '[data-education-marks-field]',
        '[data-education-cgpa-field]',
        '[data-education-scale-field]',
    ];
    const educationMarksResults = ['First Division/Class', 'Second Division/Class', 'Third Division/Class'];

    function getEducationLevelType(levelText) {
        const value = (levelText || '').toLowerCase();

        if (value.includes('psc') || value.includes('5 pass')) {
            return 'psc';
        }

        if (value.includes('jsc') || value.includes('jdc') || value.includes('8 pass')) {
            return 'jsc';
        }

        if (value.includes('higher secondary') || value.includes('hsc')) {
            return 'higher_secondary';
        }

        if (value.includes('secondary') || value.includes('ssc')) {
            return 'secondary';
        }

        if (value.includes('diploma')) {
            return 'diploma';
        }

        if (value.includes('bachelor') || value.includes('honors')) {
            return 'bachelor';
        }

        if (value.includes('master')) {
            return 'masters';
        }

        if (value.includes('phd') || value.includes('ph.d')) {
            return 'phd';
        }

        return 'default';
    }

    function setEducationFieldVisibility($field, isVisible) {
        $field.toggleClass('d-none', !isVisible);
        $field.find('input, select, textarea').prop('disabled', !isVisible);
    }

    function updateEducationOtherTitleLayout(form, customValue) {
        const $form = $(form);
        const $titleSelect = $form.find('[data-education-title-select]');
        const $otherField = $form.find('[data-education-other-title-field]');
        const $otherInput = $form.find('[data-education-other-title-input]');
        const selectedVal = ($titleSelect.val() || '').trim();
        const isOther = selectedVal === 'Others' || selectedVal === 'Other';

        if (isOther) {
            $otherField.removeClass('d-none');
            $otherInput.prop('disabled', false).prop('required', true);
            if (typeof customValue !== 'undefined' && customValue !== 'Others' && customValue !== 'Other') {
                $otherInput.val(customValue || '');
            }
        } else {
            $otherField.addClass('d-none');
            $otherInput.prop('disabled', true).prop('required', false).val('');
        }
    }

    function setEducationSelectOptions($select, options, placeholder, selectedValue) {
        $select.empty();
        $select.append($('<option></option>').attr('value', '').text(placeholder));

        const hasOthersOption = options.some(function (opt) {
            return opt.toLowerCase() === 'others' || opt.toLowerCase() === 'other';
        });

        const listOptions = hasOthersOption ? options : options.concat(['Others']);

        listOptions.forEach(function (option) {
            $select.append($('<option></option>').attr('value', option).text(option));
        });

        const isExactMatch = listOptions.some(function (opt) {
            return opt === selectedValue;
        });

        let targetVal = selectedValue || '';
        let customVal = '';

        if (selectedValue && !isExactMatch) {
            targetVal = 'Others';
            customVal = selectedValue;
        }

        $select.val(targetVal);

        if ($select.hasClass('select2-hidden-accessible')) {
            $select.trigger('change.select2');
        }

        refreshEducationCustomSelect($select);

        const $form = $select.closest('form');
        updateEducationOtherTitleLayout($form, customVal);
    }

    function setEducationTitleOptions($form, levelType, selectedTitle) {
        const $titleSelect = $form.find('[data-education-title-select]');
        const options = educationExamTitleOptions[levelType] || educationExamTitleOptions.default || [];

        setEducationSelectOptions($titleSelect, options, $titleSelect.data('placeholder') || 'Exam/Degree Title', selectedTitle);
    }

    function getEducationMajorOptions(levelType) {
        return educationMajorGroupOptions[levelType] || educationMajorGroupOptions.default || [];
    }

    function setEducationMajorOptions($form, levelType, selectedMajor) {
        const options = getEducationMajorOptions(levelType);
        $form.find('[data-education-major-input]').data('major-options', options);

        if (selectedMajor) {
            $form.find('[data-education-major-input]').val(selectedMajor);
        }
    }

    function hideEducationMajorMenu($form) {
        $form.find('[data-education-major-menu]').addClass('d-none').empty();
    }

    function renderEducationMajorMenu($input) {
        const $form = $input.closest('form');
        const $menu = $form.find('[data-education-major-menu]');
        const value = ($input.val() || '').trim();
        const options = $input.data('major-options') || [];

        if (!$input.is(':visible') || $input.prop('disabled')) {
            hideEducationMajorMenu($form);
            return;
        }

        const valueLower = value.toLowerCase();
        const matchedOptions = value
            ? options.filter(function (option) {
                return option.toLowerCase().includes(valueLower);
            })
            : options;
        $menu.empty();
        matchedOptions.forEach(function (option) {
            $menu.append(
                $('<button type="button" class="candidate-education-major-option"></button>')
                    .attr('data-education-major-value', option)
                    .text(option)
            );
        });

        if (value && !matchedOptions.length) {
            $menu.append(
                $('<button type="button" class="candidate-education-major-option"></button>')
                    .attr('data-education-major-value', value)
                    .html('Add &quot;' + $('<div></div>').text(value).html() + '&quot;')
            );
        }

        $menu.toggleClass('d-none', !$menu.children().length);
    }

    function toggleEducationMajorControl($form, selectedMajor, isVisible = true) {
        const $majorInput = $form.find('[data-education-major-input]');
        const $majorSelectRow = $form.find('[data-education-major-select-row]');
        const $majorSelect = $form.find('[data-education-major-select]');

        if (!isVisible) {
            $majorInput.addClass('d-none').prop('disabled', true);
            $majorSelectRow.addClass('d-none');
            $majorSelect.prop('disabled', true);
            hideEducationMajorMenu($form);
            return;
        }

        $majorInput.removeClass('d-none').prop('disabled', false);
        $majorSelectRow.addClass('d-none');
        $majorSelect.prop('disabled', true);

        if (selectedMajor) {
            $majorInput.val(selectedMajor);
        }
    }

    function updateEducationResultLayout(form) {
        const $form = $(form);
        const result = $form.find('[data-education-result-select]').val();
        const showMarks = educationMarksResults.includes(result);
        const showGrade = result === 'Grade';
        const isAppeared = result === 'Appeared';
        const passingLabel = isAppeared ? 'Expected year of passing' : 'Year of Passing';
        const currentYear = new Date().getFullYear();
        const $yearSelect = $form.find('[name="year"]');

        setEducationFieldVisibility($form.find('[data-education-marks-field]'), showMarks);
        setEducationFieldVisibility($form.find('[data-education-cgpa-field]'), showGrade);
        setEducationFieldVisibility($form.find('[data-education-scale-field]'), showGrade);

        $form.find('[name="marks_percentage"]').prop('required', showMarks);
        $form.find('[name="cgpa"]').prop('required', showGrade);
        $form.find('[name="scale"]').prop('required', showGrade);
        $form.find('[data-education-year-label]').text(passingLabel);
        $yearSelect.find('option').each(function () {
            const optionYear = Number($(this).val());

            if (!optionYear) {
                return;
            }

            $(this).prop('disabled', !isAppeared && optionYear > currentYear);
        });

        if (!isAppeared && Number($yearSelect.val()) > currentYear) {
            $yearSelect.val('');
        }

        if (!showMarks) {
            $form.find('[name="marks_percentage"]').val('');
        }
        if (!showGrade) {
            $form.find('[name="cgpa"], [name="scale"]').val('');
        }
        refreshEducationCustomSelect($yearSelect);
    }

    function updateEducationForeignInstituteLayout(form) {
        const $form = $(form);
        const isForeignInstitute = $form.find('[name="foreign_institute"]').prop('checked');
        const $field = $form.find('[data-education-foreign-country-field]');

        setEducationFieldVisibility($field, isForeignInstitute);
        $field.find('[name="foreign_university_country"]').prop('required', isForeignInstitute);

        if (!isForeignInstitute) {
            $field.find('[name="foreign_university_country"]').val('');
        }
    }

    function updateEducationFormLayout(form, selectedTitle, selectedMajor) {
        const $form = $(form);
        const $levelSelect = $form.find('[name="degree_level_id"]');
        const levelId = $levelSelect.val();
        const meta = educationLevelMeta[levelId] || {};
        const levelType = meta.code || getEducationLevelType($levelSelect.find('option:selected').text());
        const hasLevel = !!$levelSelect.val();
        const hasLevelMeta = Object.prototype.hasOwnProperty.call(meta, 'show_board');
        const showBoard = hasLevel ? (hasLevelMeta ? !!meta.show_board : ['psc', 'jsc', 'secondary', 'higher_secondary'].includes(levelType)) : true;
        const showMajor = hasLevel ? (hasLevelMeta ? !!meta.show_major : !['psc', 'jsc'].includes(levelType)) : true;
        const showSummary = hasLevel ? (hasLevelMeta ? !!meta.show_summary_checkbox : ['diploma', 'bachelor', 'masters'].includes(levelType)) : false;

        setEducationTitleOptions($form, levelType, selectedTitle);
        setEducationMajorOptions($form, levelType, selectedMajor);
        setEducationFieldVisibility($form.find('[data-education-board-field]'), showBoard);
        setEducationFieldVisibility($form.find('[data-education-major-field]'), showMajor);
        setEducationFieldVisibility($form.find('[data-education-summary-row]'), showSummary);
        toggleEducationMajorControl($form, selectedMajor, showMajor);

        hiddenEducationFieldSelectors.forEach(function (selector) {
            setEducationFieldVisibility($form.find(selector), false);
        });
        updateEducationResultLayout($form);
        updateEducationForeignInstituteLayout($form);

        $form.find('[name="board"]').prop('required', hasLevel && showBoard);
        $form.find('[name="major"]').prop('required', hasLevel && showMajor);
        initEducationCustomSelects($form);
    }

    $('[data-education-add-form] form, [data-education-edit-form] form').each(function () {
        updateEducationFormLayout(this);
    });

    listenChange('#degreeLevelId, #editDegreeLevel', function (event) {
        updateEducationFormLayout($(event.currentTarget).closest('form'));
        refreshEducationCustomSelect($(event.currentTarget));
    });

    listenChange('[data-education-title-select]', function (event) {
        const $select = $(event.currentTarget);
        const $form = $select.closest('form');
        updateEducationOtherTitleLayout($form);
        refreshEducationCustomSelect($select);
    });

    listenChange('[data-education-result-select]', function (event) {
        updateEducationResultLayout($(event.currentTarget).closest('form'));
        refreshEducationCustomSelect($(event.currentTarget));
    });

    listenChange('[name="foreign_institute"]', function (event) {
        updateEducationForeignInstituteLayout($(event.currentTarget).closest('form'));
    });

    $(document).on('input', '[data-education-decimal-input]', function () {
        this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
    });

    $(document).on('input', '[data-education-integer-input]', function () {
        this.value = this.value.replace(/\D/g, '');
    });

    $(document).on('input focus', '[data-education-major-input]', function () {
        renderEducationMajorMenu($(this));
    });

    $(document).on('mousedown', '[data-education-major-value]', function (event) {
        event.preventDefault();
        const $button = $(event.currentTarget);
        const $form = $button.closest('form');
        $form.find('[data-education-major-input]').val($button.attr('data-education-major-value'));
        hideEducationMajorMenu($form);
    });

    $(document).on('mousedown', function (event) {
        if (!$(event.target).closest('[data-education-major-field]').length) {
            $('[data-education-major-menu]').addClass('d-none').empty();
        }
    });

    listenShowBsModal('#addEducationModal', function () {
        $(this).find('input:text').first().blur();
    });

    window.renderExperienceTemplate = function (experienceArray) {
        let candidateExperienceCount =
            $('.candidate-experience-container .candidate-experience:last').
                data('experience-id') != undefined ?
                $('.candidate-experience-container .candidate-experience:last').
                    data('experience-id') + 1 : 0;
        let template = $.templates('#candidateExperienceTemplate');
        let endDateExperience = experienceArray.currently_working == 1
            ? $('#candidatePresentMsg').val()
            : moment(experienceArray.end_date, 'YYYY-MM-DD').
                format('Do MMM, YYYY');
        let data = {
            candidateExperienceNumber: candidateExperienceCount,
            id: experienceArray.id,
            title: experienceArray.experience_title,
            company: experienceArray.company,
            startDateExperience: moment(experienceArray.start_date,
                'YYYY-MM-DD').
                format('Do MMM, YYYY'),
            endDateExperience: endDateExperience,
            description: experienceArray.description,
            country: experienceArray.country,
        };
        let stageTemplateHtml = template.render(data);
        $('.candidate-experience-container').prepend(stageTemplateHtml);
        $('#notfoundExperience').addClass('d-none');
    };


    listenClick('.edit-candidate-experience', function (event) {
        let experienceId = $(event.currentTarget).data('id');
        renderCandidateExperienceData(experienceId);
    });

    function renderCandidateExperienceData(experienceId) {
        $.ajax({
            url: route('candidate.edit-experience', experienceId),
            type: 'GET',
            success: function (result) {
                if (result.success) {
                    $('#experienceId').val(result.data.id);
                    $('#editTitle').val(result.data.experience_title);
                    $('#editCompany').val(result.data.company);
                    $('#editCountry').val(result.data.country_id).trigger('change');
                    setTimeout(function () {
                        $("#editState").val(result.data.state_id).trigger('change');
                    }, 1000);
                    // $("#editState").val(result.data.state_id).trigger('change');
                    $('#editStartDate').
                        val(moment(result.data.start_date).
                            format('YYYY-MM-DD'));
                    $('#editDescription').val(result.data.description);
                    if (result.data.currently_working == 1) {
                        $('#editWorking').
                            prop('checked', true);
                        $('#editEndDate').val('');
                    } else {
                        $('#editWorking').
                            prop('checked', false);
                        $('#editEndDate').
                            val(moment(result.data.end_date).
                                format('YYYY-MM-DD'));
                        $('#editRequiredText').removeClass('d-none');
                    }
                    if (result.data.currently_working == 1) {
                        $('#editEndDate').prop('disabled', true);
                    }

                    setTimeout(function () {
                        $("#editCity").val(result.data.city_id).trigger('change');
                    }, 2000);
                    // $("#editCity").val(result.data.city_id).trigger('change');
                    $('#editExperienceModal').appendTo('body').modal('show');
                }
            },
            error: function (result) {
                displayErrorMessage(result.responseJSON.message);
            },
        });
    };

    listenHiddenBsModal('#addEducationModal', function () {
        resetModalForm('#addNewEducationForm', '#validationErrorsBox');
        $('#degreeLevelId').val('');
        $('#educationYearId').val('');
        initEducationCustomSelects($('#addEducationModal'));
        $('#educationCountryId, #educationStateId, #educationCityId').val('');
        $('#educationStateId, #educationCityId').empty();
        $('#educationCountryId').trigger('change.select2');
    });

    listenClick('.delete-experience', function (event) {
        let experienceId = $(event.currentTarget).data('id');
        deleteItem(route('experience.destroy', experienceId), Lang.get('js.experience'),
            '.candidate-experience-container', '.candidate-experience',
            '#notfoundExperience');
    });

    listenClick('.edit-candidate-education', function (event) {
        let educationId = $(event.currentTarget).data('id');
        const educationNumber = $(event.currentTarget).closest('.candidate-education').data('education-id') + 1;
        renderCandidateEducationData(educationId, educationNumber);
    });

    function renderCandidateEducationData(educationId, educationNumber) {
        $.ajax({
            url: route('candidate.edit-education', educationId),
            type: 'GET',
            success: function (result) {
                if (result.success) {
                    const $editForm = $('#editCareerEducationForm');
                    $('#educationId').val(result.data.id);
                    $('#editDegreeLevel').val(result.data.degree_level ? result.data.degree_level.id : '');
                    updateEducationFormLayout($editForm, result.data.degree_title, result.data.major);
                    $('#editInstitute').val(result.data.institute);
                    $editForm.find('[name="board"]').val(result.data.board || '');
                    $editForm.find('[name="show_summary"]').prop('checked', !!result.data.show_summary);
                    $editForm.find('[name="foreign_institute"]').prop('checked', !!result.data.foreign_institute);
                    $editForm.find('[name="foreign_university_country"]').val(result.data.foreign_university_country || '');
                    updateEducationForeignInstituteLayout($editForm);
                    $editForm.find('[name="marks_percentage"]').val(result.data.marks_percentage || '');
                    $editForm.find('[name="cgpa"]').val(result.data.cgpa || '');
                    $editForm.find('[name="scale"]').val(result.data.scale || '');
                    $editForm.find('[name="duration"]').val(result.data.duration || '');

                    $('#editEducationCountry').
                        val(result.data.country_id).trigger('change');
                    setTimeout(function () {
                        $("#editEducationState").val(result.data.state_id).trigger('change');
                    }, 1000);
                    setTimeout(function () {
                        $("#editEducationCity").val(result.data.city_id).trigger('change');
                    }, 2000);
                    $('#editResult').val(result.data.result);
                    updateEducationResultLayout($editForm);
                    $('#editYear').val(result.data.year).trigger('change');
                    initEducationCustomSelects($editForm);
                    if ($('[data-education-edit-form]').length) {
                        closeEducationInlineForms();
                        activeEducationItem = $('.candidate-education-container .candidate-education[data-id="' + educationId + '"]');
                        $('[data-education-add-form]').addClass('d-none');
                        if (activeEducationItem.length) {
                            activeEducationItem.find('.candidate-education-detail-grid, .candidate-education-detail--full').addClass('d-none');
                            activeEducationItem.find('.candidate-education-item__actions').addClass('d-none');
                            activeEducationItem.find('.candidate-education-item__head').after($('[data-education-edit-form]'));
                        }
                        $('[data-education-edit-form]').addClass('candidate-training-form--edit').removeClass('d-none');
                        $('[data-education-edit-form] [data-education-form-title]').addClass('d-none');
                        initEducationQuillEditors();
                        setEducationQuillValue('#editCareerEducationForm', result.data.achievement);
                    } else {
                        $('#editEducationModal').appendTo('body').modal('show');
                        setEducationQuillValue('#editCareerEducationForm', result.data.achievement);
                    }
                }
            },
            error: function (result) {
                displayErrorMessage(result.responseJSON.message);
            },
        });
    }

    listenChange('#educationStateId', function () {
        changeState('#educationCountryId', '#educationStateId', '#educationCityId');
    })
    listenChange('#editState', function () {
        changeState('#editCountry', '#editState', '#editCity');
    })
    listenChange('#editEducationState', function () {
        changeState('#editEducationCountry', '#editEducationState', '#editEducationCity');
    })

    listenClick('.delete-education', function (event) {
        let educationId = $(event.currentTarget).data('id');
        deleteItem(route('education.destroy', educationId), Lang.get('js.education'),
            '.candidate-education-container', '.candidate-education',
            '#notfoundEducation');
    });
    window.deleteItem = function (url, header, parent, child, selector) {
        swal({
            title: Lang.get('js.delete') + ' !',
            text: Lang.get('js.are_you_sure') + ' "' + header + '" ?',
            buttons: {
                confirm: Lang.get('js.yes_delete'),
                cancel: Lang.get('js.no_cancel'),
            },
            reverseButtons: true,
            icon: 'warning',
        }).then(function (willDelete) {
            if (willDelete) {
                deleteItemAjax(url, header, parent, child, selector);
            }
        });
    };
    //  function deleteItem(url, header, parent, child, selector) {
    //     const swalWithBootstrapButtons = Swal.mixin({
    //         customClass: {
    //             confirmButton: 'swal2-confirm btn fw-bold btn-danger mt-0',
    //             cancelButton: 'swal2-cancel btn fw-bold btn-bg-light btn-color-primary mt-0'
    //         },
    //         buttonsStyling: false
    //     })
    //     swalWithBootstrapButtons.fire({
    //         title: Lang.get('messages.common.delete') + ' !',
    //         text: Lang.get('messages.common.are_you_sure_want_to_delete') +
    //             '"' + header + '" ?',
    //         icon: 'warning',
    //         showCancelButton: true,
    //         closeOnConfirm: false,
    //         showLoaderOnConfirm: true,
    //         confirmButtonColor: '#6777ef',
    //         cancelButtonColor: '#d33',
    //         cancelButtonText: Lang.get('messages.common.no'),
    //         confirmButtonText: Lang.get('messages.common.yes'),
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             deleteItemAjax(url, header, parent, child, selector);
    //         }
    //     });
    // };

    function deleteItemAjax(url, header, parent, child, selector) {
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            success: function (obj) {
                if (obj.success) {
                    $(parent).children(child).each(function () {
                        let templateId = $(this).attr('data-id');
                        if (templateId == obj.data) {
                            $(this).remove();
                        }
                    });
                    if ($(parent).children(child).length <= 0) {
                        $(selector).removeClass('d-none');
                    }
                }
                swal({
                    icon: 'success',
                    title: Lang.get('js.deleted') + ' !',
                    text: header + Lang.get('js.has_been_deleted'),
                    type: 'success',
                    buttons: {
                        confirm: Lang.get('js.ok'),
                    },
                    reverseButtons: true,
                    confirmButtonColor: '#F62947',
                    timer: 2000,
                });
                // if (callFunction) {
                //     eval(callFunction);
                // }
            },
            error: function (data) {
                swal({
                    icon: 'error',
                    title: Lang.get('js.error'),
                    text: data.responseJSON.message,
                    type: 'error',
                    buttons: {
                        confirm: Lang.get('js.ok'),
                    },
                    reverseButtons: true,
                    confirmButtonColor: '#F62947',
                    timer: 5000,
                });
            },
        });
    }

    listenChange('#educationCountryId', function () {
        changeCountry('#educationCountryId', '#educationStateId');
    })
    listenChange('#editCountry', function () {
        changeCountry('#editCountry', '#editState');
    })
    listenChange('#editEducationCountry', function () {
        changeCountry('#editEducationCountry', '#editEducationState');
    })

    listenChange('#countryId', function () {
        changeCountry('#countryId', '#stateId');
    })

}

listenChange('#editCountry', function () {
    changeCountry('#editCountry', '#editState');
})

window.changeCountry = function (country, state) {
    $.ajax({
        url: route('states-list'),
        type: 'get',
        dataType: 'json',
        data: { postal: $(country).val() },
        success: function (data) {
            $(state).empty();
            if (data.data.length != 0) {
                $.each(data.data, function (i, v) {
                    $(state).append($('<option></option>').attr('value', i).text(v));
                });
            } else {
                $(state).append(
                    $('<option value=""></option>').text(Lang.get('js.select_state')));
            }
            $(state).trigger('change');
        },
    });
}
window.changeState = function (country, state, city) {
    $.ajax({
        url: route('cities-list'),
        type: 'get',
        dataType: 'json',
        data: {
            state: $(state).val(),
            country: $(country).val(),
        },
        success: function (data) {
            $(city).empty();
            if (data.data.length != 0) {
                $.each(data.data, function (i, v) {
                    $(city).append($('<option></option>').attr('value', i).text(v));
                });
            } else {
                $(city).append(
                    $('<option value=""></option>').text(Lang.get('js.select_city')));
            }
        },
    });
}

function renderEducationTemplate(educationArray) {
    let candidateEducationCount =
        $('.candidate-education-container .candidate-education:last').data('education-id') != undefined ?
            $('.candidate-education-container .candidate-education:last').data('experience-id') + 1 : 0;
    let template = $.templates('#candidateEducationTemplate');
    let data = {
        candidateEducationNumber: candidateEducationCount,
        id: educationArray.id,
        degreeLevel: educationArray.degree_level.name,
        degreeTitle: educationArray.degree_title,
        year: educationArray.year,
        country: educationArray.country,
        institute: educationArray.institute,
    };
    let stageTemplateHtml = template.render(data);
    $('.candidate-education-container').prepend(stageTemplateHtml);
    $('#notfoundEducation').addClass('d-none');
};

    function getEducationFormData($form) {
        let formData = $form.serializeArray();
        const $titleSelect = $form.find('[data-education-title-select]');
        const selectedVal = ($titleSelect.val() || '').trim();
        const isOther = selectedVal === 'Others' || selectedVal === 'Other';

        if (isOther) {
            const customTitle = ($form.find('[data-education-other-title-input]').val() || '').trim();
            if (!customTitle) {
                return null;
            }
            formData = formData.map(function (item) {
                if (item.name === 'degree_title') {
                    return { name: 'degree_title', value: customTitle };
                }
                return item;
            });
        }

        return $.param(formData);
    }

listenSubmit('#addNewEducationForm', function (e) {
    e.preventDefault();
    if (typeof window.syncEducationQuillEditors === 'function') {
        window.syncEducationQuillEditors();
    }
    const dataToSend = getEducationFormData($(this));
    if (dataToSend === null) {
        displayErrorMessage('Please enter your Exam/Degree Title.');
        return false;
    }
    processingBtn('#addNewEducationForm', '#btnEducationSave', 'loading');
    $.ajax({
        url: route('candidate.create-education'),
        type: 'POST',
        data: dataToSend,
        success: function (result) {
            if (result.success) {
                $('#notfoundEducation').addClass('d-none');
                displaySuccessMessage(result.message);
                if ($('[data-education-add-form]').length) {
                    reloadCandidateProfileSection('education-training', 'candidateEducationPanelBody');
                    return;
                }
                $('#addEducationModal').modal('hide');
                renderEducationTemplate(result.data);
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            processingBtn('#addNewEducationForm', '#btnEducationSave');
        },
    });
});

listenSubmit('#editCareerEducationForm', function (event) {
    event.preventDefault();
    if (typeof window.syncEducationQuillEditors === 'function') {
        window.syncEducationQuillEditors();
    }
    const dataToSend = getEducationFormData($(this));
    if (dataToSend === null) {
        displayErrorMessage('Please enter your Exam/Degree Title.');
        return false;
    }
    processingBtn('#editCareerEducationForm', '#editEducationSave',
        'loading');
    const educationId = $('#educationId').val();
    $.ajax({
        url: route('candidate.update-education', educationId),
        type: 'put',
        data: dataToSend,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                if ($('[data-education-edit-form]').length) {
                    reloadCandidateProfileSection('education-training', 'candidateEducationPanelBody');
                    return;
                }
                $('#editEducationModal').modal('hide');
                $('.candidate-education-container').load(location.href + " .candidate-education-container");
                $('.candidate-education-container').children('.candidate-education').each(function () {
                    let candidateEducationId = $(this).attr('data-id');
                    if (candidateEducationId == result.data.id) {
                        $(this).remove();
                    }
                });
                renderEducationTemplate(result.data.candidateEducation);
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            processingBtn('#editCareerEducationForm', '#editEducationSave');
        },
    });
});

listenSubmit('#addNewExperienceForm', function (e) {
    e.preventDefault();
    let startDateExperience = new Date($('#startDateExperience').val());
    let endDateExperience = new Date($('#endDateExperience').val());
    if (endDateExperience < startDateExperience) {
        displayErrorMessage(
            'The start date must be a date before end date.');
        return false;
    }
    processingBtn('#addNewExperienceForm', '#btnExperienceSave', 'loading');
    $.ajax({
        url: route('candidate.create-experience'),
        type: 'POST',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                $('#notfoundExperience').addClass('d-none');
                displaySuccessMessage(result.message);
                setTimeout(function () {
                    reloadCandidateProfileSection('employment', 'candidateExperiencePanelBody');

                }, 3000);
                $('#addExperienceModal').modal('hide');
                renderExperienceTemplate(result.data);

            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            processingBtn('#addNewExperienceForm', '#btnExperienceSave');
        },
    });
});

listenSubmit('#editExperienceForm', function (event) {
    event.preventDefault();
    let startDateExperience = new Date($('#editStartDate').val());
    let endDateExperience = new Date($('#editEndDate').val());
    if (endDateExperience < startDateExperience) {
        displayErrorMessage(
            'The start date must be a date before end date.');
        return false;
    }
    processingBtn('#editExperienceForm', '#btnExperienceSave',
        'loading');
    const id = $('#experienceId').val();
    $.ajax({
        url: route('candidate.update-experience', id),
        type: 'put',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                setTimeout(function () {
                    reloadCandidateProfileSection('employment', 'candidateExperiencePanelBody');

                }, 3000);
                $('#editExperienceModal').modal('hide');

                // $('.candidate-experience-container').load(location.href + ' .candidate-experience-container');
                $('.candidate-experience-container').children('.candidate-experience').each(function () {
                    let candidateExperienceId = $(this).attr('data-id');
                    if (candidateExperienceId == result.data.id) {
                        $(this).remove();
                    }
                });

                renderExperienceTemplate(result.data.candidateExperience);
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            processingBtn('#editExperienceForm', '#btnExperienceSave');
        },
    });
});

listenHiddenBsModal('#editEducationModal', function () {
    resetModalForm('#editCareerEducationForm', '#validationErrorsBox');
});

listenHiddenBsModal('#addExperienceModal', function () {
    resetModalForm('#addNewExperienceForm', '#validationErrorsBox');
    $('#countryId, #stateId, #cityId').val('');
    $('#stateId, #cityId').empty();
    $('#countryId').trigger('change.select2');
});

listenShowBsModal('#addExperienceModal', function () {
    $('#endDateExperience').prop('disabled', false);
    setDatePicker('#startDateExperience', '#endDateExperience');
});

