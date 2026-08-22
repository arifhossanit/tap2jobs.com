document.addEventListener('DOMContentLoaded', loadCityData);

function loadCityData() {
    if (!$('#indexCitiesData').length) {
        return;
    }
    $('#selectState').select2();

    $('#filter_state').select2();

    $('#stateID').select2({
        'width': '100%',
        dropdownParent: $('#addCityModal'),
    });

    $('#editStateId').select2({
        'width': '100%',
        dropdownParent: $('#editCityModal'),
    });

    $('#importStateId').select2({
        'width': '100%',
        dropdownParent: $('#importCityModal'),
    });

}

listenClick('.addCityModal', function () {
    $('#addCityModal').appendTo('body').modal('show');
});

listenClick('.importCityModal', function () {
    $('#importCityModal').appendTo('body').modal('show');
});

listenClick('.cities-edit-btn', function (event) {
    let cityId = $(event.currentTarget).attr('data-id');
    $.ajax({
        url: route('cities.edit', cityId),
        type: 'GET',
        success: function (result) {
            if (result.success) {
                $('#cityId').val(result.data.id);
                $('#editName').val(result.data.name);
                $('#editStateId').
                    val(result.data.state_id).
                    trigger('change');
                $('#editCityModal').appendTo('body').modal('show');
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
});

listenClick('.cities-delete-btn', function (event) {
    let deleteCityId = $(event.currentTarget).attr('data-id');
    deleteItem(route('cities.destroy', deleteCityId),
        Lang.get('js.city'));
});

listenHiddenBsModal('#addCityModal', function () {
    $('#stateID').val('').trigger('change');
    resetModalForm('#addCityForm', '#cityValidationErrorsBox');
});

listenHiddenBsModal('#editCityModal', function () {
    resetModalForm('#editCityForm', '#editValidationErrorsBox');
});

listenHiddenBsModal('#importCityModal', function () {
    $('#importStateId').val('').trigger('change');
    resetModalForm('#importCityForm', '#importCityValidationErrorsBox');
});

listenClick('#resetFilter', function () {
    $('#filter_state').val('').trigger('change');
});

listenSubmit('#addCityForm', function (e) {
    e.preventDefault();
    processingBtn('#addCityForm', '#cityBtnSave', 'loading');
    $.ajax({
        url: route('cities.store'),
        type: 'POST',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#addCityModal').modal('hide');
                Livewire.dispatch('refreshDatatable');
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            processingBtn('#addCityForm', '#cityBtnSave');
        },
    });
});

listenSubmit('#editCityForm', function (event) {
    event.preventDefault();
    processingBtn('#editCityForm', '#btnEditSave', 'loading');
    const updateCityId = $('#cityId').val();
    $.ajax({
        url: route('cities.update', updateCityId),
        type: 'put',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#editCityModal').modal('hide');
                Livewire.dispatch('refreshDatatable');
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            processingBtn('#editCityForm', '#btnEditSave');
        },
    });
});

listenSubmit('#importCityForm', function (e) {
    e.preventDefault();
    processingBtn('#importCityForm', '#importCityBtnSave', 'loading');
    let formData = new FormData(this);
    $.ajax({
        url: route('cities.import'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (result) {
            if (result.message || result.success) {
                displaySuccessMessage(result.message || 'Cities imported successfully.');
                $('#importCityModal').modal('hide');
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
            processingBtn('#importCityForm', '#importCityBtnSave');
        },
    });
});
listenChange("#selectState", function() {
         Livewire.dispatch("changeStateFilter", { state: $(this).val() });
     });

listenClick("#state-ResetFilter", function() {
         $("#selectState").val(0).change();
         hideDropdownManually($('#selectStateBtn'), $('.dropdown-menu'));
});
function hideDropdownManually(button, menu) {
    button.dropdown('toggle');
}
