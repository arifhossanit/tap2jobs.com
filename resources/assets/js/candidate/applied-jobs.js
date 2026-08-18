// document.addEventListener('DOMContentLoaded', loadAppliedJobsData);

Livewire.hook('element.init', () => {
    loadAppliedJobsData();
});

let filterJobId = null;
let selectedAppliedJobId = null;

function loadAppliedJobsData() {
    if (!$('#jobApplicationStatus').length) {
        return;
    }
    $('#jobApplicationStatus').select2();

    $('#jobApplicationStatus').on('change', function () {
        Livewire.dispatch('changeFilter', { value: $(this).val() });
        Livewire.dispatch('refresh');
        Livewire.dispatch('refreshDatatable');
    });

    Livewire.hook('message.processed', () => {
        $('#jobApplicationStatus').select2({
            width: '100%',
        });
        $('#jobApplicationStatus').val(filterJobId).trigger('change.select2');
        setTimeout(function () { $('.alert').fadeOut('fast'); }, 4000);
    });
}

document.addEventListener('deleted', function () {
    swal({
        icon: 'success',
        title: Lang.get('js.deleted') + ' !',
        text: Lang.get('js.applied_jobs') + Lang.get('js.has_been_deleted'),
        type: 'success',
        buttons: {
            confirm: Lang.get('js.ok'),
        },
        reverseButtons: true,
        confirmButtonColor: '#F62947',
        timer: 2000,
    });
});

document.addEventListener('notDeleted', function () {
    swal({
        icon: 'error',
        type: 'error',
        title: Lang.get('js.error'),
        text: Lang.get('js.seems_message'),
        buttons: {
            confirm: Lang.get('js.ok'),
        },
        reverseButtons: true,
        confirmButtonColor: '#F62947',
    });
});

listenClick('.apply-job-note', function (event) {
    let appliedJobId = $(event.currentTarget).attr('data-id');
    $.ajax({
        url: route('candidate.applied.job.show', appliedJobId),
        type: 'GET',
        success: function (result) {
            if (result.success) {
                $('#showNote').html('');
                if (!isEmpty(result.data.notes) ? $('#showNote').append(result.data.notes) : $('#showNote').append('N/A'))
                    $('#showModal').appendTo('body').modal('show');
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
});

listenClick('.remove-applied-jobs', function (event) {
    let jobId = $(event.currentTarget).attr('data-id');
    swal({
        title: Lang.get('js.delete') + ' !',
        text: Lang.get('js.are_you_sure_want_to_delete') + '"' + Lang.get('js.applied_jobs') + '" ?',
        icon: 'warning',
        showCancelButton: true,
        closeOnConfirm: false,
        buttons: {
            confirm: Lang.get('js.yes'),
            cancel: Lang.get('js.no')
        },
    }).then((result) => {
        if (result) {
            Livewire.dispatch('removeAppliedJob', { id: jobId });
        }
    });
});

document.addEventListener('appliedJob:error', function () {
    swal({
        icon: 'error',
        type: 'error',
        title: Lang.get('js.error'),
        text: Lang.get('js.seems_message'),
        buttons: {
            confirm: Lang.get('js.ok'),
        },
        reverseButtons: true,
        confirmButtonColor: '#F62947',
    });
});

function linkifyText(text) {
    if (!text) return '';
    var urlPattern = /(https?:\/\/[^\s<]+)/gi;
    return text.replace(urlPattern, function(url) {
        return '<a href="' + url + '" target="_blank" rel="noopener noreferrer" class="text-primary text-decoration-underline fw-bold">' + url + '</a>';
    });
}

listenClick('.schedule-slot-book', function (event) {
    let appliedJobId = $(event.currentTarget).attr('data-id');
    selectedAppliedJobId = appliedJobId;
    $.ajax({
        url: route('show.schedule.slot', appliedJobId),
        type: 'POST',
        success: function (result) {
            if (result.success && !isEmpty(result.data)) {
                // Check if candidate already selected a slot in the current batch
                if (result.data.selectSlot && result.data.selectSlot.length > 0) {
                    $('#scheduleInterviewBtnSave,#rejectSlotBtnSave').addClass('d-none');
                    $.each(result.data.selectSlot, function (i, v) {
                        let data = {
                            'notes': !isEmpty(v.notes) ? linkifyText(v.notes) : 'New Slot Send.',
                            'schedule_date': v.date,
                            'schedule_time': v.time,
                        };
                        $('.slot-main-div').append(prepareTemplateRender('#selectedSlotBookHtmlTemplate', data));
                    });
                    $('#selectedSlotBookValidationErrorsBox').removeClass('d-none')
                        .html(Lang.get('js.you_have_selected_this_slot'));
                } 
                // Check if candidate rejected all slots in current batch
                else if (result.data.rejectedSlot) {
                    $('#scheduleInterviewBtnSave,#rejectSlotBtnSave').addClass('d-none');
                    if (!isEmpty(result.data.employer_cancel_note)) {
                        $('#scheduleSlotBookValidationErrorsBox').removeClass('d-none')
                            .html(result.data.company_fullName + Lang.get('js.cancel_your_selected_slot') + '<br>' + '<b>Reason</b>:- ' + linkifyText(result.data.employer_cancel_note));
                    } else {
                        $('#scheduleSlotBookValidationErrorsBox').removeClass('d-none')
                            .html(Lang.get('js.you_have_rejected_all_slot'));
                    }
                } 
                // Available new slots to choose from
                else {
                    $('#scheduleInterviewBtnSave,#rejectSlotBtnSave').removeClass('d-none');
                    let index = 0;
                    $.each(result.data, function (i, v) {
                        if (!isEmpty(v.job_Schedule_Id)) {
                            index++;
                            let data = {
                                'index': index,
                                'notes': linkifyText(v.notes),
                                'schedule_date': v.schedule_date,
                                'schedule_time': v.schedule_time,
                                'schedule_id': v.job_Schedule_Id,
                            };
                            $('.slot-main-div').append(prepareTemplateRender('#scheduleSlotBookHtmlTemplate', data));
                            $('.choose-slot-textarea').removeClass('d-none');
                            $('#scheduleSlotBookValidationErrorsBox').addClass('d-none');
                        }
                    });
                }

                // Render History (past stage schedules)
                $('#historyMainDiv').removeClass('d-none');
                $.each(result.data, function (i, v) {
                    if ($.type(v) == 'object' && isEmpty(v.job_Schedule_Id)) {
                        const data = {
                            'notes': linkifyText(v.notes),
                            'companyName': v.company_name,
                            'stageName': v.stage_name || 'Stage',
                            'slotDateTime': v.slot_date_time || '',
                            'schedule_created_at': v.schedule_created_at,
                        };
                        $('#historyDiv').prepend(prepareTemplateRender('#chooseSlotHistoryHtmlTemplate', data));
                    }
                });

                $('#scheduleSlotBookModal').appendTo('body').modal('show');
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
});

listenHiddenBsModal('#scheduleSlotBookModal', function () {
    selectedAppliedJobId = null;
    $('.slot-main-div').html('');
    $('.choose-slot-textarea').addClass('d-none');
    $('#selectedSlotBookValidationErrorsBox').addClass('d-none');
    $('#historyDiv').html('');
    $('#scheduleInterviewBtnSave,#rejectSlotBtnSave').attr('disabled', false);
    $('#rejectSlotBtnSave').val('');
});

listenClick('#rejectSlotBtnSave', function () {
    $(this).val('rejectSlot');
});

listenClick('#scheduleInterviewBtnSave', function () {
    $('#rejectSlotBtnSave').val('');
});

listenSubmit('#scheduleSlotBookForm', function (e) {
    e.preventDefault();
    $('#scheduleInterviewBtnSave,#rejectSlotBtnSave').attr('disabled', true);
    let appliedJobId = selectedAppliedJobId;
    if (isEmpty(appliedJobId)) {
        displayErrorMessage(Lang.get('js.seems_message'));
        $('#scheduleInterviewBtnSave,#rejectSlotBtnSave').attr('disabled', false);
        return;
    }

    let scheduleId;
    let formData = new FormData($(this)[0]);
    $.each($('.slot-book'), function (i) {
        if ($(this).prop('checked')) {
            scheduleId = $(this).data('schedule');
        }
    });
    if (!isEmpty($('#rejectSlotBtnSave').val())) {
        formData.append('rejectSlot', $('#rejectSlotBtnSave').val());
    }
    formData.append('schedule_id', scheduleId);
    $.ajax({
        url: route('choose.preference', appliedJobId),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#scheduleSlotBookModal').modal('hide');
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
            $('#scheduleInterviewBtnSave,#rejectSlotBtnSave').attr('disabled', false);
        },
        complete: function () {
            processingBtn('#scheduleSlotBookForm', '#scheduleInterviewBtnSave');
        },
    });
});
