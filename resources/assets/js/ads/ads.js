Livewire.hook('element.init', () => {
    loadAdData();
});

function loadAdData() {
    if (!$('#addAdNewForm').length && !$('#editAdForm').length) {
        return;
    }

    listenHiddenBsModal('#addAdsModal', function () {
        resetModalForm('#addAdNewForm', '#validationErrorsBox');
        clearAdPreview('#previewImage');
        resetAdUploadProgress('ad');
    });

    listenHiddenBsModal('#editAdsModal', function () {
        resetModalForm('#editAdForm', '#editValidationErrorsBox');
        clearAdPreview('#editPreviewImage');
        resetAdUploadProgress('editAd');
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

                let mediaUrl = result.data.ad_media_url || result.data.ad_image_url;
                if (isEmpty(mediaUrl) || result.data.ad_media_type === 'video') {
                    clearAdPreview('#editPreviewImage');
                } else {
                    $('#editPreviewImage').css('background-image', 'url("' + mediaUrl + '")');
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
    resetAdUploadProgress('ad');
    $.ajax({
        url: route('ads.store'),
        type: 'POST',
        data: new FormData($(this)[0]),
        dataType: 'JSON',
        processData: false,
        contentType: false,
        xhr: function () {
            return adUploadProgressXhr('ad');
        },
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
    resetAdUploadProgress('editAd');
    const adUpdateId = $('#adId').val();
    $.ajax({
        url: route('ads.update', adUpdateId),
        type: 'POST',
        data: new FormData($(this)[0]),
        dataType: 'JSON',
        processData: false,
        contentType: false,
        xhr: function () {
            return adUploadProgressXhr('editAd');
        },
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

function adUploadProgressXhr(prefix) {
    let xhr = $.ajaxSettings.xhr();

    if (xhr.upload) {
        xhr.upload.addEventListener('progress', function (event) {
            if (!event.lengthComputable) {
                return;
            }

            let percent = Math.round((event.loaded / event.total) * 100);
            updateAdUploadProgress(prefix, percent);
        }, false);
    }

    return xhr;
}

function updateAdUploadProgress(prefix, percent) {
    let progress = $('#' + prefix + 'UploadProgress');
    let progressBar = $('#' + prefix + 'UploadProgressBar');
    let progressText = $('#' + prefix + 'UploadProgressText');

    progress.removeClass('d-none');
    progressBar.css('width', percent + '%').attr('aria-valuenow', percent);
    progressText.text(percent + '%');
}

function resetAdUploadProgress(prefix) {
    updateAdUploadProgress(prefix, 0);
    $('#' + prefix + 'UploadProgress').addClass('d-none');
}

function clearAdPreview(selector) {
    $(selector).css('background-image', 'none');
}
