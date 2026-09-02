Livewire.hook('element.init', ({ component, el }) => {
    setTimeout(function () {
        loadSelect2();

        if (!$('#createJobNotificationForm').length) {
            return;
        }
    },500)
})

function loadSelect2() {
    if(!$('#candidateId').length) {
        return false;
    }

    $('#candidateId').select2();

    if(!$('#filter_employers').length) {
        return false;
    }

    $('#filter_employers').select2();
}


listenSubmit('#createJobNotificationForm', function (e) {
    e.preventDefault();
    if ($('.jobCheck:checked').length === 0) {
        displayErrorMessage(Lang.get('js.select_job'));
        return false;
    }
    
    let submitBtn = $(this).find('button[type="submit"], input[type="submit"]');
    submitBtn.prop('disabled', true);
    
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#candidateId').val(null).trigger('change');
                $('.jobCheck').prop('checked', false);
                $('#ckbCheckAll').prop('checked', false);
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            submitBtn.prop('disabled', false);
        }
    });
});

listenClick('#resetJobNotificationFilter', function () {
    $('#filter_employers').val('').trigger('change');
    const url = $('#indexGetEmployerJobs').val();

    $.ajax({
        url: url,
        type: 'get',
        success: function (result) {
            if (result.success) {
                let noJobsAvailable = `<li class="no-job-available"><h4 class="text-center mt-9">${Lang.get('js.no_jobs_available')}</h4></li>`;
                if (result.data && result.data.html) {
                    $('.job-notification-ul').html(result.data.html);
                } else {
                    $('.job-notification-ul').html(noJobsAvailable);
                }
                $('#ckbCheckAll').prop('checked', false);
            }
        },
        error: function (result) {
            manageAjaxErrors(result);
        },
    });
});

//employer
listenChange('#filter_employers', function () {
    $('.job-notification-ul').empty();
    $('#ckbCheckAll').prop('checked', false);
    let url = '';

    let employerId = $(this).val();
    if (!isEmpty(employerId)) {
        url = $('#indexGetEmployerJobs').val() + '/' + employerId;
    } else {
        url = $('#indexJobNotification').val();
    }
    $.ajax({
        url: url,
        type: 'get',
        success: function (result) {
            if (result.success) {
                let noJobsAvailable = `<li class="no-job-available"><h4 class="text-center mt-9">${Lang.get('js.no_jobs_available')}</h4></li>`;
                if (result.data && result.data.html) {
                    $('.job-notification-ul').html(result.data.html);
                } else {
                    $('.job-notification-ul').html(noJobsAvailable);
                }
                $('#ckbCheckAll').prop('checked', false);
            }
        },
        error: function (result) {
            manageAjaxErrors(result);
        },
    });
});

// Handle AJAX pagination clicks
$(document).on('click', '.job-notification-ul .pagination a', function (e) {
    e.preventDefault();
    let url = $(this).attr('href');
    let employerId = $('#filter_employers').val();

    if (!url.includes('employer-jobs') && !isEmpty(employerId)) {
        // If pagination URL doesn't have employer-jobs route but filter is applied
        url = $('#indexGetEmployerJobs').val() + '/' + employerId + '?page=' + new URL(url).searchParams.get('page');
    } else if (isEmpty(employerId) && !url.includes('employer-jobs')) {
        url = $('#indexGetEmployerJobs').val() + '?page=' + new URL(url).searchParams.get('page');
    }

    $.ajax({
        url: url,
        type: 'get',
        success: function (result) {
            if (result.success && result.data && result.data.html) {
                $('.job-notification-ul').html(result.data.html);
                $('#ckbCheckAll').prop('checked', false);
            }
        },
        error: function (result) {
            manageAjaxErrors(result);
        },
    });
});

function humanReadableFormatDate (date) {
    return moment(date).fromNow();
};

//select all checkbox
$(document).on('click', '#ckbCheckAll', function () {
    $('.jobCheck').prop('checked', $(this).prop('checked'));
});

$(document).on('click', '.jobCheck', function () {
    if ($('.jobCheck:checked').length === $('.jobCheck').length && $('.jobCheck').length > 0) {
        $('#ckbCheckAll').prop('checked', true);
    } else {
        $('#ckbCheckAll').prop('checked', false);
    }
});

