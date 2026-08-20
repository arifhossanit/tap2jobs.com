
 listenClick('.uploadResumeModal', function () {
        $('#candidateResumeModal').appendTo('body').modal('show');
    });

 listenClick('.preview-resume', function (event) {
     event.preventDefault();

     const button = $(event.currentTarget);
     const modal = $('#candidateResumePreviewModal');
     const frame = $('#candidateResumePreviewFrame');
     const loading = modal.find('.candidate-resume-preview-loading');
     const unavailable = modal.find('.candidate-resume-preview-unavailable');

     modal.appendTo('body');
     modal.find('#candidateResumePreviewTitle').text(button.data('title'));
     frame.addClass('d-none').attr('src', '');
     unavailable.addClass('d-none');

     if (String(button.data('previewable')) === '1') {
         loading.removeClass('d-none');
         frame.removeClass('d-none').attr('src', button.data('url'));
         window.setTimeout(function () {
             loading.addClass('d-none');
         }, 1200);
     } else {
         loading.addClass('d-none');
         unavailable.removeClass('d-none');
     }

     modal.modal('show');
 });

 listen('load', '#candidateResumePreviewFrame', function () {
     $('.candidate-resume-preview-loading').addClass('d-none');
 });

 listen('hidden.bs.modal', '#candidateResumePreviewModal', function () {
     $('#candidateResumePreviewFrame').attr('src', '').addClass('d-none');
     $(this).find('.candidate-resume-preview-loading').removeClass('d-none');
 });

 listenSubmit('#addCandidateResumeForm', function (e) {
        let empty = $('#uploadResumeTitle').val().trim().replace(/ \r\n\t/g, '') === '';
        if (empty) {
            displayErrorMessage('The title field is not contain only white space');
            return false;
        }
        e.preventDefault();
        processingBtn('#addCandidateResumeForm', '#candidateSaveBtn', 'loading');
        $.ajax({
            url: route('candidate.resumes'),
            type: 'post',
            data: new FormData(this),
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function (result) {
                if (result.success) {
                    displaySuccessMessage(result.message);
                    resetModalForm('#addCandidateResumeForm', '#validationErrorsBox');
                    $('#candidateResumeModal').modal('hide');
                    setTimeout(function () {
                        processingBtn('#addCandidateResumeForm', '#candidateSaveBtn', 'reset');
                    }, 1000);
                    Livewire.dispatch('refreshDatatable');
                }
            },
            error: function (result) {
                displayErrorMessage(result.responseJSON.message);
                setTimeout(function () {
                    processingBtn('#addCandidateResumeForm', '#candidateSaveBtn', 'reset');
                }, 1000);
            },
            complete: function () {
                setTimeout(function () {
                    processingBtn('#addCandidateResumeForm', '#candidateSaveBtn');
                }, 1000);
            },
        });
    });

 listenChange('.candidate-default-resume-select', function () {
     const select = $(this);
     const previousValue = select.attr('data-current-value');

     select.prop('disabled', true);
     $.ajax({
         url: select.data('url'),
         type: 'PUT',
         data: { resume_id: select.val() },
         success: function (result) {
             select.attr('data-current-value', select.val());
             displaySuccessMessage(result.message);
             Livewire.dispatch('refreshDatatable');
         },
         error: function (result) {
             select.val(previousValue);
             displayErrorMessage(result.responseJSON && result.responseJSON.message
                 ? result.responseJSON.message
                 : Lang.get('js.something_went_wrong'));
         },
         complete: function () {
             select.prop('disabled', false);
         },
     });
 });

   listenChange('#customFile', function () {
        let extension = isValidDocument($(this), '#validationErrorsBox');
        if (!isEmpty(extension) && extension != false) {
            $('#validationErrorsBox').html('').hide();
        }
    });

    window.isValidDocument = function (
        inputSelector, validationMessageSelector) {
        let ext = $(inputSelector).val().split('.').pop().toLowerCase();
        if ($.inArray(ext, ['jpg', 'jpeg', 'pdf', 'doc', 'docx']) ==
            -1) {
            $(inputSelector).val('');
            $(validationMessageSelector).removeClass('d-none');
            $(validationMessageSelector).
                html(
                    Lang.get('js.file_type')).
                show();
            $(validationMessageSelector).delay(5000).slideUp(300);

            return false;
        }
        $(validationMessageSelector).hide();

        return ext;
    };

    $('.custom-file-input').on('change', function () {
        var fileName = $(this).val().split('\\').pop();
        $(this).
            siblings('.custom-file-label').
            addClass('selected').
            html(fileName);
    });

  listenClick('.delete-resume', function (event) {

        let resumeId = $(event.currentTarget).attr('data-id');
        deleteItem(route('download.destroy', resumeId),
            Lang.get('js.resume'));
  });

 listen('hide.bs.modal', '#candidateResumeModal', function () {
     $('#customFile').siblings('.custom-file-label').addClass('selected').html('Choose file');
     resetModalForm('#addCandidateResumeForm', '#validationErrorsBox');
 });

listenSubmit('#candidateCvPrivacyForm', function (e) {
    e.preventDefault();
    const form = $(this);
    const btn = form.find('#saveCvPrivacyBtn');
    btn.prop('disabled', true);
    btn.find('.btn-spinner').removeClass('d-none');
    btn.find('.btn-icon').addClass('d-none');

    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
            } else {
                displayErrorMessage(result.message || (typeof Lang !== 'undefined' ? Lang.get('js.something_went_wrong') : 'Something went wrong'));
            }
        },
        error: function (result) {
            let errorMsg = result.responseJSON && result.responseJSON.message
                ? result.responseJSON.message
                : (typeof Lang !== 'undefined' ? Lang.get('js.something_went_wrong') : 'Something went wrong');
            displayErrorMessage(errorMsg);
        },
        complete: function () {
            btn.prop('disabled', false);
            btn.find('.btn-spinner').addClass('d-none');
            btn.find('.btn-icon').removeClass('d-none');
        }
    });
});
