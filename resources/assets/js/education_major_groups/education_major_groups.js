document.addEventListener('DOMContentLoaded', loadEducationMajorGroupData);

function loadEducationMajorGroupData() {
    listenClick('.addEducationMajorGroupModal', function () {
        $('#addEducationMajorGroupModal').appendTo('body').modal('show');
    });

    listenClick('.education-major-group-edit-btn', function (event) {
        let majorId = $(event.currentTarget).attr('data-id');
        $.ajax({
            url: route('educationMajorGroups.edit', majorId),
            type: 'GET',
            success: function (result) {
                if (result.success) {
                    $('#editEducationMajorGroupId').val(result.data.id);
                    $('#editMajorDegreeLevelId').val(result.data.required_degree_level_id);
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
        resetModalForm('#addEducationMajorGroupForm', '#educationMajorGroupValidationErrorsBox');
    });

    listenHiddenBsModal('#editEducationMajorGroupModal', function () {
        resetModalForm('#editEducationMajorGroupForm', '#editEducationMajorGroupValidationErrorsBox');
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
