Livewire.hook('element.init', () => {
    loadAdData();
});

function loadAdData() {
    if (!$('#addAdNewForm').length && !$('#editAdForm').length) {
        return;
    }

    let defaultDocumentImageUrl = $('#defaultDocumentImageUrl').val();

    listenHiddenBsModal('#addAdsModal', function () {
        resetModalForm('#addAdNewForm', '#validationErrorsBox');
        $('#previewImage').css('background-image', 'url("' + defaultDocumentImageUrl + '")');
    });

    listenHiddenBsModal('#editAdsModal', function () {
        resetModalForm('#editAdForm', '#editValidationErrorsBox');
        $('#editPreviewImage').css('background-image', 'url("' + defaultDocumentImageUrl + '")');
    });
}

listenClick('.ad-delete-btn', function (event) {
    let deleteAdId = $(event.currentTarget).attr('data-id');
    deleteItem(route('ads.destroy', deleteAdId), Lang.get('js.ad'));
});

listenClick('.ad-edit-btn', function (event) {
    let editAdId = $(event.currentTarget).attr('data-id');
    adRenderData(editAdId);
});

function adRenderData(editAdId) {
    $.ajax({
        url: route('ads.edit', editAdId),
        type: 'GET',
        success: function (result) {
            if (result.success) {
                let element = document.createElement('textarea');
                element.innerHTML = result.data.title || '';
                $('#adId').val(result.data.id);
                $('#editTitle').val(element.value);
                $('#editPosition').val(result.data.position);
                $('#editDescription').val(result.data.description || '');
                $('#editLinkUrl').val(result.data.link_url || '');
                $('#editCtaText').val(result.data.cta_text || '');
                $('#editSortOrder').val(result.data.sort_order || 0);

                let imageUrl = result.data.ad_image_url;
                if (isEmpty(imageUrl)) {
                    $('#editPreviewImage').css('background-image',
                        'url("' + $('#defaultDocumentImageUrl').val() + '")');
                } else {
                    $('#editPreviewImage').css('background-image', 'url("' + imageUrl + '")');
                }

                (result.data.is_active == 1)
                    ? $('#editIsActive').prop('checked', true)
                    : $('#editIsActive').prop('checked', false);

                $('#editAdsModal').appendTo('body').modal('show');
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
}

listenSubmit('#addAdNewForm', function (e) {
    e.preventDefault();
    processingBtn('#addAdNewForm', '#adSaveBtn', 'loading');
    $.ajax({
        url: route('ads.store'),
        type: 'POST',
        data: new FormData($(this)[0]),
        dataType: 'JSON',
        processData: false,
        contentType: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#addAdsModal').modal('hide');
                Livewire.dispatch('refreshDatatable');
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
            processingBtn('#addAdNewForm', '#adSaveBtn');
        },
        complete: function () {
            processingBtn('#addAdNewForm', '#adSaveBtn');
        },
    });
});

listenSubmit('#editAdForm', function (event) {
    event.preventDefault();
    processingBtn('#editAdForm', '#editAdSaveBtn', 'loading');
    const adUpdateId = $('#adId').val();
    $.ajax({
        url: route('ads.update', adUpdateId),
        type: 'POST',
        data: new FormData($(this)[0]),
        dataType: 'JSON',
        processData: false,
        contentType: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#editAdsModal').modal('hide');
                Livewire.dispatch('refreshDatatable');
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
            processingBtn('#editAdForm', '#editAdSaveBtn');
        },
        complete: function () {
            processingBtn('#editAdForm', '#editAdSaveBtn');
        },
    });
});

listenClick('.addAdModal', function () {
    $('#addAdsModal').appendTo('body').modal('show');
});

listenChange('.isActiveAd', function () {
    let isActiveAdId = $(this).attr('data-id');
    changeIsActiveAdRenderData(isActiveAdId);
});

function changeIsActiveAdRenderData(isActiveAdId) {
    $.ajax({
        url: route('ads.change-is-active', isActiveAdId),
        method: 'post',
        cache: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                Livewire.dispatch('refreshDatatable');
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
}

listenChange('#adStatusFilter', function () {
    Livewire.dispatch('changeStatusFilter', { status: $(this).val() });
});

listenClick('#adStatusFilter-ResetFilter', function () {
    $('#adStatusFilter').val(2).change();
    hideDropdownManually($('#adFilterBtn'), $('.dropdown-menu'));
});

function hideDropdownManually(button, menu) {
    button.dropdown('toggle');
}
