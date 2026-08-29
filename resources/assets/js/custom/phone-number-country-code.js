document.addEventListener('DOMContentLoaded', loadPhoneNumberCountry);
// document.addEventListener('DOMContentLoaded', loadPhoneNumberCountry);

function loadPhoneNumberCountry() {
    if(!$('#phoneNumber').length && !$('#prefix_code').length){
        return false
    }

    let input = document.querySelector('#phoneNumber'),
        errorMsg = document.querySelector('#error-msg'),
        validMsg = document.querySelector('#valid-msg');
    let normalizePhoneNumber = function () {
        input.value = input.value.replace(/\D/g, '').slice(0, 11);
        return input.value;
    };
    let preserveLocalPhoneNumber = function (callback) {
        let localPhoneNumber = normalizePhoneNumber();
        callback();
        if (localPhoneNumber !== '') {
            input.value = localPhoneNumber;
        }
        normalizePhoneNumber();
    };
    let errorMap = [
        Lang.get('js.invalid_number'),
        Lang.get('js.invalid_country_code'),
        Lang.get('js.too_short'),
        Lang.get('js.too_long'),
        Lang.get('js.invalid_number'),
    ]

    // initialise plugin
    let intl = window.intlTelInput(input, {
        initialCountry: defaultCountryCodeValue,
        separateDialCode: true,
        geoIpLookup: function (success, failure) {
            $.get('https://ipinfo.io', function () {}, 'jsonp').always(function (resp) {
                var countryCode = (resp && resp.country)
                    ? resp.country
                    : '';
                success(countryCode);
            });
        },
        utilsScript: '../../public/assets/js/inttel/js/utils.min.js',
    });

    if (typeof phoneNo != 'undefined' && phoneNo !== '') {
        setTimeout(function () {
            $('#phoneNumber').trigger('change');
        }, 500);
    }

    // if (isEdit) {
    let getCode = intl.selectedCountryData['dialCode'];
    $('#prefix_code').val(getCode);
    // }

    normalizePhoneNumber();


    let reset = function () {
        input.classList.remove('error');
        errorMsg.innerHTML = '';
        errorMsg.classList.add('d-none');
        validMsg.classList.add('d-none');
    };

    input.addEventListener('blur', function () {
        normalizePhoneNumber();
        reset()
        if (input.value.trim()) {
            if (intl.isValidNumber()) {
                validMsg.classList.remove('d-none')
            } else {
                input.classList.add('error')
                var errorCode = intl.getValidationError()
                errorMsg.innerHTML = errorMap[errorCode]
                errorMsg.classList.remove('d-none')
            }
        }
    })

// on keyup / change flag: reset
    input.addEventListener('change', function () {
        normalizePhoneNumber();
        reset();
    });
    input.addEventListener('keyup', function () {
        normalizePhoneNumber();
        reset();
    });
    input.addEventListener('input', normalizePhoneNumber);

    if (typeof phoneNo != 'undefined' && phoneNo !== '') {
        setTimeout(function () {
            $('#phoneNumber').trigger('change')
        }, 500)
    } else {
        let flagClassLocal = window.localStorage.getItem('flagClassLocal')
        let dialCodeValLocal = window.localStorage.getItem('dialCodeValLocal')
        if (dialCodeValLocal) {
            $('.iti__selected-flag>.iti__flag').addClass(flagClassLocal)
            $('.iti__selected-dial-code').text(dialCodeValLocal)
            let phoneEleVal = $('#phoneNumber').val()
            preserveLocalPhoneNumber(function () {
                intl.setNumber(dialCodeValLocal + phoneEleVal)
            })
        }
    }

    $('#phoneNumber').on('blur keyup change countrychange', function () {
        if (typeof phoneNo != 'undefined' && phoneNo !== '') {
            preserveLocalPhoneNumber(function () {
                intl.setNumber('+' + phoneNo);
            });
            phoneNo = '';
        }
        normalizePhoneNumber();
        let getCode = intl.selectedCountryData['dialCode'];
        $('#prefix_code').val(getCode);
    });
}
