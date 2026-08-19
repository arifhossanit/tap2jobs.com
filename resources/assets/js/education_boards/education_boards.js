document.addEventListener('DOMContentLoaded', loadEducationBoardData);

function loadEducationBoardData() {
    listenClick('.addEducationBoardModal', function () {
        $('#addEducationBoardModal').appendTo('body').modal('show');
    });

    listenClick('.education-board-edit-btn', function (event) {
        let boardId = $(event.currentTarget).attr('data-id');
        $.ajax({
            url: route('educationBoards.edit', boardId),
            type: 'GET',
            success: function (result) {
                if (result.success) {
                    $('#editEducationBoardId').val(result.data.id);
                    $('#editEducationBoardName').val(result.data.name);
                    $('#editEducationBoardModal').appendTo('body').modal('show');
                }
            },
            error: function (result) {
                displayErrorMessage(result.responseJSON.message);
            },
        });
    });

    listenClick('.education-board-delete-btn', function (event) {
        let boardId = $(event.currentTarget).attr('data-id');
        deleteItem(route('educationBoards.destroy', boardId), 'Education Board');
    });

    listenHiddenBsModal('#addEducationBoardModal', function () {
        resetModalForm('#addEducationBoardForm', '#educationBoardValidationErrorsBox');
    });

    listenHiddenBsModal('#editEducationBoardModal', function () {
        resetModalForm('#editEducationBoardForm', '#editEducationBoardValidationErrorsBox');
    });
}

listenSubmit('#addEducationBoardForm', function (e) {
    e.preventDefault();
    processingBtn('#addEducationBoardForm', '#educationBoardBtnSave', 'loading');
    $.ajax({
        url: route('educationBoards.store'),
        type: 'POST',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#addEducationBoardModal').modal('hide');
                Livewire.dispatch('refreshDatatable');
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            processingBtn('#addEducationBoardForm', '#educationBoardBtnSave');
        },
    });
});

listenSubmit('#editEducationBoardForm', function (event) {
    event.preventDefault();
    processingBtn('#editEducationBoardForm', '#editEducationBoardBtnSave', 'loading');
    const id = $('#editEducationBoardId').val();
    $.ajax({
        url: route('educationBoards.update', id),
        type: 'PUT',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#editEducationBoardModal').modal('hide');
                Livewire.dispatch('refreshDatatable');
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            processingBtn('#editEducationBoardForm', '#editEducationBoardBtnSave');
        },
    });
});
