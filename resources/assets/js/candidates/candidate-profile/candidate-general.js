document.addEventListener('DOMContentLoaded', loadCandidateGeneralData);
import "flatpickr/dist/l10n";

function loadCandidateGeneralData() {
    if (!$('#birthDate').length && !$('#availableAt').length){
        return
    }
    const $nationalityInput = $('#nationalityInput');
    const $isBangladeshi = $('#isBangladeshi');

    function syncNationalityInput () {
        if (!$nationalityInput.length || !$isBangladeshi.length) {
            return;
        }

        if ($isBangladeshi.prop('checked')) {
            $nationalityInput.val('Bangladeshi').prop('readonly', true).addClass('candidate-readonly-cross');
        } else {
            $nationalityInput.prop('readonly', false).removeClass('candidate-readonly-cross');
        }
    }

    syncNationalityInput();
    $isBangladeshi.on('change', syncNationalityInput);

        $('#birthDate').flatpickr({
            format: 'YYYY-MM-DD',
            useCurrent: true,
            sideBySide: true,
            "locale": getLoggedInUserLang,
            maxDate: new Date(),
        });

        $('#availableAt').flatpickr({
            format: 'YYYY-MM-DD',
            useCurrent: false,
            sideBySide: true,
            "locale": getLoggedInUserLang,
            minDate: new Date(),
        });

        if ($('#passportIssueDate').length) {
            $('#passportIssueDate').flatpickr({
                format: 'YYYY-MM-DD',
                useCurrent: false,
                sideBySide: true,
                "locale": getLoggedInUserLang,
            });
        }


    if ($('#candidateProfileUpdate').length){
        $('#salaryCurrencyId,#countryId,#stateId,#cityId,#industryId,#careerLevelId,#functionalAreaId').
            select2({
                width: '100%',
        });
        $('.candidate-preferred-select').each(function () {
            $(this).select2({
                width: '100%',
                placeholder: $(this).data('placeholder') || '',
                closeOnSelect: false,
            });
        });
    }
    if ($('#skillId').length && $('#languageId').length) {
        $('#skillId').select2({
            width: '100%',
            placeholder: Lang.get('js.select_skill'),
        });
        $('#languageId').select2({
            width: '100%',
            placeholder: Lang.get('js.select_language'),
        });
    }
    $('.form-select').on('select2:open', function () {
        $(this).next('.select2-container').addClass('select2-container--open-chevron');
    }).on('select2:close', function () {
        $(this).next('.select2-container').removeClass('select2-container--open-chevron');
    });
    $('.candidate-profile-accordion .form-select').not('[multiple]').on('mousedown', function () {
        if ($(this).next('.select2-container').length) {
            return;
        }

        $(this).addClass('candidate-select-open');
    }).on('change blur', function () {
        $(this).removeClass('candidate-select-open');
    });
    setTimeout(function () {
        $('input[type=radio][name=immediate_available]').trigger('change');
    }, 300);

    function renderPreferredCheckboxChips (target) {
        const $target = $(target);
        if (!$target.length) {
            return;
        }

        $target.empty();
        $('.candidate-preferred-checkbox[data-chip-target="' + target + '"]:checked').each(function () {
            const $checkbox = $(this);
            const $chip = $('<span class="candidate-preferred-chip"></span>');
            $chip.text($checkbox.data('label'));
            $('<button type="button" aria-label="Remove">&times;</button>').appendTo($chip).on('click', function () {
                $checkbox.prop('checked', false).trigger('change');
            });
            $target.append($chip);
        });
    }

    function renderPreferredSelectChips ($select) {
        const target = $select.data('chip-target');
        const $target = $(target);
        if (!$target.length) {
            return;
        }

        const $container = $select.next('.select2-container');
        $container.find('.select2-selection__choice').remove();
        $container.find('.select2-selection__rendered > li:not(.select2-search--inline)').remove();
        $container.find('.select2-search__field').attr('placeholder', $select.data('placeholder') || '');

        $target.empty();
        $select.find('option:selected').each(function () {
            const $option = $(this);
            const $chip = $('<span class="candidate-preferred-chip"></span>');
            $chip.text($option.text());
            $('<button type="button" aria-label="Remove">&times;</button>').appendTo($chip).on('click', function () {
                $option.prop('selected', false);
                $select.trigger('change');
            });
            $target.append($chip);
        });
    }

    $('.candidate-preferred-checkbox').each(function () {
        renderPreferredCheckboxChips($(this).data('chip-target'));
    }).on('change', function () {
        renderPreferredCheckboxChips($(this).data('chip-target'));
    });

    $('.candidate-preferred-select').each(function () {
        renderPreferredSelectChips($(this));
    }).on('change', function () {
        renderPreferredSelectChips($(this));
    }).on('select2:select select2:unselect select2:open select2:close', function () {
        const select = this;
        setTimeout(function () {
            renderPreferredSelectChips($(select));
        }, 0);
    });

    $('#countryId').on('change', function () {
        $.ajax({
            url: route('states-list'),
            type: 'get',
            dataType: 'json',
            data: { postal: $(this).val() },
            success: function (data) {
                $('#cityId').empty();
                $('#cityId').append(
                    $('<option value=""></option>').text(Lang.get('js.select_city')));
                $('#stateId').empty();
                $('#stateId').append(
                    $('<option value=""></option>').text(Lang.get('js.select_state')));
                $.each(data.data, function (i, v) {
                    $('#stateId').append($('<option></option>').attr('value', i).text(v));
                });
                // if (isEdit && stateId) {
                //     $('#stateId').val(stateId).trigger('change');
                // }
            },
        });
    });

    $('#stateId').on('change', function () {
        $.ajax({
            url: route('cities-list'),
            type: 'get',
            dataType: 'json',
            data: {
                state: $(this).val(),
                country: $('#countryId').val(),
            },
            success: function (data) {
                $('#cityId').empty();
                $('#cityId').append(
                    $('<option value=""></option>').text(Lang.get('js.select_city')));
                $.each(data.data, function (i, v) {
                    $('#cityId').append(
                        $('<option ></option>').attr('value', i).text(v));
                });
                // if (isEdit && cityId) {
                //     $('#cityId').val(cityId).trigger('change');
                // }
            },
        });
    });
    // if (isEdit & countryId) {
    //     $('#countryId').val(countryId).trigger('change');
    // }

    $(document).on('change', '#profile', function () {
        let validFile = isValidFile($(this), '#validationErrors');
        if (validFile) {
            displayPhoto(this, '#profilePreview');
            $('.btnSave').prop('disabled', false);
        } else {
            $('.btnSave').prop('disabled', true);
        }
    });
    $('input[type=radio][name=immediate_available]').change(function () {
        let radioValue = $('input[name=\'immediate_available\']:checked').val();
        if (radioValue == 1) {
            $('.available-at').hide();
        } else {
            $('.available-at').show();
        }
    });

    $('#available').click(function () {
        radio();
    });
    $('#not_available').click(function () {
        radio();
    });

    function radio () {
        let radioValue = $('input[name=\'immediate_available\']:checked').val();
        if (radioValue == '0') {
            $('.available-at').show();
        } else {
            $('.available-at').hide();
        }
    }
}

$(document).on('keyup', '#facebookUrl', function () {
    this.value = this.value.toLowerCase();
});
$(document).on('keyup', '#twitterUrl', function () {
    this.value = this.value.toLowerCase();
});
$(document).on('keyup', '#linkedInUrl', function () {
    this.value = this.value.toLowerCase();
});
$(document).on('keyup', '#googlePlusUrl', function () {
    this.value = this.value.toLowerCase();
});
$(document).on('keyup', '#pinterestUrl', function () {
    this.value = this.value.toLowerCase();
});

$(document).on('submit', '#candidateProfileUpdate', function (e) {
    e.preventDefault();

    if ($('#error-msg').text() !== '') {
        $('#phoneNumber').focus();
        return false;
    }
    $('#candidateProfileUpdate').
        find('input:text:visible:first').
        focus();

    let facebookUrl = $('#facebookUrl').val();
    let twitterUrl = $('#twitterUrl').val();
    let linkedInUrl = $('#linkedInUrl').val();
    let googlePlusUrl = $('#googlePlusUrl').val();
    let pinterestUrl = $('#pinterestUrl').val();

    let facebookExp = new RegExp(
        /^(https?:\/\/)?((m{1}\.)?)?((w{3}\.)?)facebook.[a-z]{2,3}\/?.*/i);
    let twitterExp = new RegExp(
        /^(https?:\/\/)?((m{1}\.)?)?((w{3}\.)?)twitter\.[a-z]{2,3}\/?.*/i);
    let googlePlusExp = new RegExp(
        /^(https?:\/\/)?((w{3}\.)?)?(plus\.)?(google\.[a-z]{2,3})\/?(([a-zA-Z 0-9._])?).*/i);
    let linkedInExp = new RegExp(
        /^(https?:\/\/)?((w{3}\.)?)linkedin\.[a-z]{2,3}\/?.*/i);
    let pinterestExp = new RegExp(
        /^(https?:\/\/)?((w{3}\.)?)pinterest\.[a-z]{2,3}\/?.*/i);

    urlValidation(facebookUrl, facebookExp);
    urlValidation(twitterUrl, twitterExp);
    urlValidation(linkedInUrl, linkedInExp);
    urlValidation(googlePlusUrl, googlePlusExp);
    urlValidation(pinterestUrl, pinterestExp);

    if (!urlValidation(facebookUrl, facebookExp)) {
        displayErrorMessage(Lang.get('js.valid_facebook_url'));
        return false;
    }
    if (!urlValidation(twitterUrl, twitterExp)) {
        displayErrorMessage(Lang.get('js.valid_twitter_url'));
        return false;
    }
    if (!urlValidation(googlePlusUrl, googlePlusExp)) {
        displayErrorMessage(Lang.get('js.valid_google_plus_url'));
        return false;
    }
    if (!urlValidation(linkedInUrl, linkedInExp)) {
        displayErrorMessage(Lang.get('js.valid_linkedin_url'));
        return false;
    }
    if (!urlValidation(pinterestUrl, pinterestExp)) {
        displayErrorMessage(Lang.get('js.valid_pinterest_url'));
        return false;
    }
    $('#candidateProfileUpdate')[0].submit();

    return true;
});
