document.addEventListener('DOMContentLoaded', loadEducationDegreeTitleData);

function loadEducationDegreeTitleData() {
    $('#degreeLevelId').select2({
        width: '100%',
        dropdownParent: $('#addEducationDegreeTitleModal'),
    });

    $('#editDegreeLevelId').select2({
        width: '100%',
        dropdownParent: $('#editEducationDegreeTitleModal'),
    });

    $('#importDegreeLevelId').select2({
        width: '100%',
        dropdownParent: $('#importEducationDegreeTitleModal'),
    });

    listenClick('.addEducationDegreeTitleModal', function () {
        $('#addEducationDegreeTitleModal').appendTo('body').modal('show');
    });

    listenClick('.importEducationDegreeTitleModal', function () {
        $('#importEducationDegreeTitleModal').appendTo('body').modal('show');
    });

    listenClick('.education-degree-title-edit-btn', function (event) {
        let titleId = $(event.currentTarget).attr('data-id');
        $.ajax({
            url: route('educationDegreeTitles.edit', titleId),
            type: 'GET',
            success: function (result) {
                if (result.success) {
                    $('#editEducationDegreeTitleId').val(result.data.id);
                    $('#editDegreeLevelId').val(result.data.required_degree_level_id).trigger('change');
                    $('#editDegreeTitleName').val(result.data.name);
                    $('#editEducationDegreeTitleModal').appendTo('body').modal('show');
                }
            },
            error: function (result) {
                displayErrorMessage(result.responseJSON.message);
            },
        });
    });

    listenClick('.education-degree-title-delete-btn', function (event) {
        let titleId = $(event.currentTarget).attr('data-id');
        deleteItem(route('educationDegreeTitles.destroy', titleId), 'Degree Title');
    });

    listenHiddenBsModal('#addEducationDegreeTitleModal', function () {
        $('#degreeLevelId').val('').trigger('change');
        resetModalForm('#addEducationDegreeTitleForm', '#educationDegreeTitleValidationErrorsBox');
    });

    listenHiddenBsModal('#editEducationDegreeTitleModal', function () {
        resetModalForm('#editEducationDegreeTitleForm', '#editEducationDegreeTitleValidationErrorsBox');
    });

    listenHiddenBsModal('#importEducationDegreeTitleModal', function () {
        $('#importDegreeLevelId').val('').trigger('change');
        resetModalForm('#importEducationDegreeTitleForm', '#importEducationDegreeTitleValidationErrorsBox');
    });
}

listenSubmit('#addEducationDegreeTitleForm', function (e) {
    e.preventDefault();
    processingBtn('#addEducationDegreeTitleForm', '#educationDegreeTitleBtnSave', 'loading');
    $.ajax({
        url: route('educationDegreeTitles.store'),
        type: 'POST',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#addEducationDegreeTitleModal').modal('hide');
                Livewire.dispatch('refreshDatatable');
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            processingBtn('#addEducationDegreeTitleForm', '#educationDegreeTitleBtnSave');
        },
    });
});

listenSubmit('#editEducationDegreeTitleForm', function (event) {
    event.preventDefault();
    processingBtn('#editEducationDegreeTitleForm', '#editEducationDegreeTitleBtnSave', 'loading');
    const id = $('#editEducationDegreeTitleId').val();
    $.ajax({
        url: route('educationDegreeTitles.update', id),
        type: 'PUT',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#editEducationDegreeTitleModal').modal('hide');
                Livewire.dispatch('refreshDatatable');
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            processingBtn('#editEducationDegreeTitleForm', '#editEducationDegreeTitleBtnSave');
        },
    });
});

listenSubmit('#importEducationDegreeTitleForm', function (e) {
    e.preventDefault();
    processingBtn('#importEducationDegreeTitleForm', '#importEducationDegreeTitleBtnSave', 'loading');
    let formData = new FormData(this);
    $.ajax({
        url: route('educationDegreeTitles.import'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (result) {
            if (result.message || result.success) {
                displaySuccessMessage(result.message || 'Degree Titles imported successfully.');
                $('#importEducationDegreeTitleModal').modal('hide');
                Livewire.dispatch('refreshDatatable');
            }
        },
        error: function (result) {
            let message = result.responseJSON?.message || 'Unable to import file.';
            if (result.responseJSON?.errors) {
                let firstError = Object.values(result.responseJSON.errors)[0];
                if (Array.isArray(firstError)) {
                    message = firstError[0];
                }
            }
            displayErrorMessage(message);
        },
        complete: function () {
            processingBtn('#importEducationDegreeTitleForm', '#importEducationDegreeTitleBtnSave');
        },
    });
});
