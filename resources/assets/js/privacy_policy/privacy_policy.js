document.addEventListener('DOMContentLoaded', loadPrivacyPolicy);

function loadPrivacyPolicy() {
    const hasTermsEditor = $('#addTermConditionDescriptionQuillData').length;
    const hasPrivacyEditor = $('#addPrivacyPolicyDescriptionQuillData').length;

    if (!hasTermsEditor && !hasPrivacyEditor) {
        return
    }

    let termConditionData = $('#termConditionData').val() || '';
    let privacyPolicyData = $('#privacyPolicyData').val() || '';
    // $('#descriptionTerms').summernote({
    //     minHeight: 200,
    //     height: 200,
    //     toolbar: [
    //         // [groupName, [list of button]]
    //         ['style', ['bold', 'italic', 'underline', 'clear']],
    //         ['font', ['strikethrough']],
    //         ['para', ['paragraph']],
    //     ],
    // });

    if (hasTermsEditor) {
        window.addTermConditionDescriptionQuill = new AppTextEditor('#addTermConditionDescriptionQuillData', {
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                ['clean']
            ],
            keyboard: {
                bindings: {
                    tab: 'disabled',
                }
            }
        },
        placeholder: Lang.get('js.terms_conditions'),
        theme: 'snow', // or 'bubble'
    });
        addTermConditionDescriptionQuill.on('text-change', function () {
            if (addTermConditionDescriptionQuill.getText().trim().length === 0) {
                addTermConditionDescriptionQuill.setContents([{ insert: '' }]);
            }
        });

        let termElement = document.createElement('textarea');
        termElement.innerHTML = termConditionData;
        addTermConditionDescriptionQuill.root.innerHTML = termElement.value;
    }

    if (hasPrivacyEditor) {
        window.addPrivacyPolicyDescriptionQuill = new AppTextEditor('#addPrivacyPolicyDescriptionQuillData', {
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                ['clean']
            ],
            keyboard: {
                bindings: {
                    tab: 'disabled',
                }
            }
        },
        placeholder: Lang.get('js.privacy_policy'),
        theme: 'snow', // or 'bubble'
    });
        addPrivacyPolicyDescriptionQuill.on('text-change', function () {
            if (addPrivacyPolicyDescriptionQuill.getText().trim().length === 0) {
                addPrivacyPolicyDescriptionQuill.setContents([{ insert: '' }]);
            }
        });

        let privacyElement = document.createElement('textarea');
        privacyElement.innerHTML = privacyPolicyData;
        addPrivacyPolicyDescriptionQuill.root.innerHTML = privacyElement.value;
    }

    // $('#privacyPolicy').submit(function (e) {
    //     if (!checkSummerNoteEmpty('#description',
    //         'Privacy Policy field is required.', 1)) {
    //         e.preventDefault();
    //
    //         return true;
    //     }
    // });
    //
    // $('#termsConditions').submit(function (e) {
    //     if (!checkSummerNoteEmpty('#description',
    //         'Terms Conditions field is required.', 1)) {
    //         e.preventDefault();
    //
    //         return true;
    //     }
    // });

    // $('#policyTerms').submit(function (e) {
    //     if (!checkSummerNoteEmpty('#descriptionPolicy',
    //         'Privacy Policy field is required.', 1)) {
    //         e.preventDefault();
    //
    //         return true;
    //     }
    //
    //     if (!checkSummerNoteEmpty('#descriptionTerms',
    //         'Terms Conditions field is required.', 1)) {
    //         e.preventDefault();
    //
    //         return true;
    //     }
    // });
}

listenSubmit('#policyTerms', function () {
    if (window.tinymce) {
        tinymce.triggerSave();
    }

    if ($('#addTermConditionDescriptionQuillData').length) {
        if (addTermConditionDescriptionQuill.getText().trim().length === 0) {
            displayErrorMessage(Lang.get('js.terms_conditions_required'));
            return false;
        }

        $('#termData').val(addTermConditionDescriptionQuill.root.innerHTML);
        $('#addTermConditionDescriptionQuillData').val(addTermConditionDescriptionQuill.root.innerHTML);
    }

    if ($('#addPrivacyPolicyDescriptionQuillData').length) {
        if (addPrivacyPolicyDescriptionQuill.getText().trim().length === 0) {
            displayErrorMessage(Lang.get('js.privacy_policy_required'));
            return false;
        }

        $('#privacyData').val(addPrivacyPolicyDescriptionQuill.root.innerHTML);
        $('#addPrivacyPolicyDescriptionQuillData').val(addPrivacyPolicyDescriptionQuill.root.innerHTML);
    }
});
