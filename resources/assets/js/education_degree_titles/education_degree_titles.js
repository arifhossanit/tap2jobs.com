document.addEventListener('DOMContentLoaded', loadEducationDegreeTitleData);

function loadEducationDegreeTitleData() {
    listenClick('.addEducationDegreeTitleModal', function () {
        $('#addEducationDegreeTitleModal').appendTo('body').modal('show');
    });

    listenClick('.education-degree-title-edit-btn', function (event) {
        let titleId = $(event.currentTarget).attr('data-id');
        $.ajax({
            url: route('educationDegreeTitles.edit', titleId),
            type: 'GET',
            success: function (result) {
                if (result.success) {
                    $('#editEducationDegreeTitleId').val(result.data.id);
                    $('#editDegreeLevelId').val(result.data.required_degree_level_id);
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
        resetModalForm('#addEducationDegreeTitleForm', '#educationDegreeTitleValidationErrorsBox');
    });

    listenHiddenBsModal('#editEducationDegreeTitleModal', function () {
        resetModalForm('#editEducationDegreeTitleForm', '#editEducationDegreeTitleValidationErrorsBox');
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
