document.addEventListener('DOMContentLoaded', loadEducationMajorGroupData);

function loadEducationMajorGroupData() {
    $('#majorDegreeLevelId').select2({
        width: '100%',
        dropdownParent: $('#addEducationMajorGroupModal'),
    });

    $('#editMajorDegreeLevelId').select2({
        width: '100%',
        dropdownParent: $('#editEducationMajorGroupModal'),
    });

    $('#importMajorDegreeLevelId').select2({
        width: '100%',
        dropdownParent: $('#importEducationMajorGroupModal'),
    });

    listenClick('.addEducationMajorGroupModal', function () {
        $('#addEducationMajorGroupModal').appendTo('body').modal('show');
    });

    listenClick('.importEducationMajorGroupModal', function () {
        $('#importEducationMajorGroupModal').appendTo('body').modal('show');
    });

    listenClick('.education-major-group-edit-btn', function (event) {
        let majorId = $(event.currentTarget).attr('data-id');
        $.ajax({
            url: route('educationMajorGroups.edit', majorId),
            type: 'GET',
            success: function (result) {
                if (result.success) {
                    $('#editEducationMajorGroupId').val(result.data.id);
                    $('#editMajorDegreeLevelId').val(result.data.required_degree_level_id).trigger('change');
                    $('#editMajorGroupName').val(result.data.name);
                    $('#editEducationMajorGroupModal').appendTo('body').modal('show');
                }
            },
            error: function (result) {
                displayErrorMessage(result.responseJSON.message);
            },
        });
    });

    listenClick('.education-major-group-delete-btn', function (event) {
        let majorId = $(event.currentTarget).attr('data-id');
        deleteItem(route('educationMajorGroups.destroy', majorId), 'Major / Group');
    });

    listenHiddenBsModal('#addEducationMajorGroupModal', function () {
        $('#majorDegreeLevelId').val('').trigger('change');
        resetModalForm('#addEducationMajorGroupForm', '#educationMajorGroupValidationErrorsBox');
    });

    listenHiddenBsModal('#editEducationMajorGroupModal', function () {
        resetModalForm('#editEducationMajorGroupForm', '#editEducationMajorGroupValidationErrorsBox');
    });

    listenHiddenBsModal('#importEducationMajorGroupModal', function () {
        $('#importMajorDegreeLevelId').val('').trigger('change');
        resetModalForm('#importEducationMajorGroupForm', '#importEducationMajorGroupValidationErrorsBox');
    });
}

listenSubmit('#addEducationMajorGroupForm', function (e) {
    e.preventDefault();
    processingBtn('#addEducationMajorGroupForm', '#educationMajorGroupBtnSave', 'loading');
    $.ajax({
        url: route('educationMajorGroups.store'),
        type: 'POST',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#addEducationMajorGroupModal').modal('hide');
                Livewire.dispatch('refreshDatatable');
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            processingBtn('#addEducationMajorGroupForm', '#educationMajorGroupBtnSave');
        },
    });
});

listenSubmit('#editEducationMajorGroupForm', function (event) {
    event.preventDefault();
    processingBtn('#editEducationMajorGroupForm', '#editEducationMajorGroupBtnSave', 'loading');
    const id = $('#editEducationMajorGroupId').val();
    $.ajax({
        url: route('educationMajorGroups.update', id),
        type: 'PUT',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#editEducationMajorGroupModal').modal('hide');
                Livewire.dispatch('refreshDatatable');
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            processingBtn('#editEducationMajorGroupForm', '#editEducationMajorGroupBtnSave');
        },
    });
});

listenSubmit('#importEducationMajorGroupForm', function (e) {
    e.preventDefault();
    processingBtn('#importEducationMajorGroupForm', '#importEducationMajorGroupBtnSave', 'loading');
    let formData = new FormData(this);
    $.ajax({
        url: route('educationMajorGroups.import'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (result) {
            if (result.message || result.success) {
                displaySuccessMessage(result.message || 'Major / Groups imported successfully.');
                $('#importEducationMajorGroupModal').modal('hide');
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
            processingBtn('#importEducationMajorGroupForm', '#importEducationMajorGroupBtnSave');
        },
    });
});
