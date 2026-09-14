document.addEventListener('DOMContentLoaded', loadJobStagesData);
document.addEventListener('livewire:navigated', loadJobStagesData);
document.addEventListener('turbo:load', loadJobStagesData);

let jobStageListenersRegistered = false;

function loadJobStagesData() {
    if (!$('#jobStageDescription').length && !$('#editStageDescription').length) {
        return;
    }
    if (jobStageListenersRegistered) {
        return;
    }
    jobStageListenersRegistered = true;

    // $('#jobStageName').addClass('p-7');

    listenClick('.job-stage-edit-btn', function (event) {
        // if (ajaxCallIsRunning) {
//            return;
//        }
        ajaxCallInProgress();
        let jobStageId = $(event.currentTarget).attr('data-id');

        $.ajax({
            url: route('job.stage.edit', jobStageId),
            type: 'GET',
            success: function (result) {
                if (result.success) {
                    let element = document.createElement('textarea');
                    element.innerHTML = result.data.name;
                    $('#jobStageId').val(result.data.id);
                    $('#editName').val(element.value);
                    element.innerHTML = result.data.description;
                    $('#editStageDescription').val(element.value);
                    $('#editJobStageModal').appendTo('body').modal('show');
                    ajaxCallCompleted();
                }
            },
            error: function (result) {
                displayErrorMessage(result.responseJSON.message);
            },
        });
    });


    listenHiddenBsModal('#addJobStageModal', function () {
        resetModalForm('#addJobStageForm', '#jobStageValidationErrorsBox');
    });

    listenClick('.addJobStageModal', function () {
        $('#addJobStageModal').appendTo('body').modal('show');
    });

    listenClick('.job-stage-show-btn', function (event) {
        // if (ajaxCallIsRunning) {
//            return;
//        }
        ajaxCallInProgress();
        let jobStageId = $(event.currentTarget).attr('data-id');
        $.ajax({
            url: route('job.stage.show', jobStageId),
            type: 'GET',
            success: function (result) {
                if (result.success) {
                    $('#showName').html('');
                    $('#showDescription').html('');
                    $('#showName').append(result.data.name);
                    let element = document.createElement('textarea');
                    element.innerHTML = result.data.description;
                    if (element.value == '') {
                        $('#showDescription').html("N/A");
                    } else {
                        $('#showDescription').html(element.value);
                    }
                    $('#showJobStageModal').appendTo('body').modal('show');
                    ajaxCallCompleted();
                }
            },
            error: function (result) {
                displayErrorMessage(result.responseJSON.message);
            },
        });
    });

    listenClick('.job-stage-delete-btn', function (event) {
        let jobStageId = $(event.currentTarget).attr('data-id');
        deleteItem(route('job.stage.destroy', jobStageId),
            Lang.get('js.job_stage'));
    });

    listenHiddenBsModal('#editJobStageModal', function () {
        resetModalForm('#editJobStageForm', '#editValidationErrorsBox');
    });

}
//     swal({
//         title: Lang.get('messages.common.delete') + ' !',
//         text: Lang.get('messages.common.are_you_sure_want_to_delete') + '"' + Lang.get('messages.job_stage.job_stage') + '" ?',
//         type: 'warning',
//         showCancelButton: true,
//         closeOnConfirm: false,
//         showLoaderOnConfirm: true,
//         confirmButtonColor: '#6777ef',
//         cancelButtonColor: '#d33',
//         cancelButtonText: Lang.get('messages.common.no'),
//         confirmButtonText: Lang.get('messages.common.yes')
//     }, function () {
//         $.ajax({
//             url: jobStageUrl + jobStageId,
//             type: 'DELETE',
//             success: function success(result) {
//                 if (result.success) {
//                    Livewire.dispatch('refresh');
//                     tbl.ajax.reload(null, false);
//                 }
//                 swal({
//                     title: Lang.get('messages.common.delete') + ' !',
//                     text: Lang.get('messages.job_stage.job_stage') + Lang.get('messages.common.has_been_deleted'),
//                     type: 'success',
//                     confirmButtonColor: '#6777ef',
//                     timer: 2000
//                 });
//             },
//             error: function error(data) {
//                 swal({
//                     title: '',
//                     text: data.responseJSON.message,
//                     type: 'error',
//                     confirmButtonColor: '#6777ef',
//                     timer: 2000
//                 });
//             }
//         });
//     });
// });

// $('#addJobStageModal').on('hidden.bs.modal', function () {
//     resetModalForm('#addJobStageForm', '#jobStageValidationErrorsBox');
//     $('#jobStageDescription').summernote('code', '');
// });
//
// $('#editModal').on('hidden.bs.modal', function () {
//     resetModalForm('#editForm', '#editValidationErrorsBox');
// });
//
// $('#jobStageDescription, #editStageDescription').summernote({
//     minHeight: 200,
//     height: 200,
//     toolbar: [
//         ['style', ['bold', 'italic', 'underline', 'clear']],
//         ['font', ['strikethrough']],
//         ['para', ['paragraph']]],
// });
listenSubmit('#addJobStageForm', function (e) {
    e.preventDefault();
    processingBtn('#addJobStageForm', '#jobStageBtnSave', 'loading');
    $.ajax({
        url: route('job.stage.store'),
        type: 'POST',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#addJobStageModal').modal('hide');
                Livewire.dispatch('refreshDatatable');
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            processingBtn('#addJobStageForm', '#jobStageBtnSave');
        },
    });
});

listenSubmit('#editJobStageForm', function (event) {
    event.preventDefault();
    processingBtn('#editJobStageForm', '#jobStageEditSaveBtn', 'loading');
    // if (!checkSummerNoteEmpty('#editStageDescription',
    //     'Description field is required.')) {
    //     processingBtn('#editForm', '#btnEditSave');
    //     return true;
    // }
    const jobStageUpdateId = $('#jobStageId').val();
    $.ajax({
        url: route('job.stage.update', jobStageUpdateId),
        type: 'put',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#editJobStageModal').modal('hide');
                Livewire.dispatch('refreshDatatable');
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            processingBtn('#editJobStageForm', '#jobStageEditSaveBtn');
        },
    });
});
