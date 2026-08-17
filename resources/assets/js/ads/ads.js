Livewire.hook("element.init", () => {
    loadAdData();
});

function loadAdData() {
    if (!$("#addAdNewForm").length && !$("#editAdForm").length) {
        return;
    }

    listenHiddenBsModal("#addAdsModal", function () {
        resetModalForm("#addAdNewForm", "#validationErrorsBox");
        clearAdPreview("#previewImage", $("#adChooseMediaText").val());
        resetAdUploadProgress("ad");
    });

    listenHiddenBsModal("#editAdsModal", function () {
        resetModalForm("#editAdForm", "#editValidationErrorsBox");
        clearAdPreview("#editPreviewImage", $("#adNoMediaSelectedText").val());
        resetAdUploadProgress("editAd");
    });
}

listenClick(".ad-delete-btn", function (event) {
    let deleteAdId = $(event.currentTarget).attr("data-id");
    deleteItem(route("ads.destroy", deleteAdId), Lang.get("js.ad"));
});

listenClick(".ad-edit-btn", function (event) {
    let editAdId = $(event.currentTarget).attr("data-id");
    adRenderData(editAdId);
});

function adRenderData(editAdId) {
    $.ajax({
        url: route("ads.edit", editAdId),
        type: "GET",
        success: function (result) {
            if (result.success) {
                let element = document.createElement("textarea");
                element.innerHTML = result.data.title || "";
                $("#adId").val(result.data.id);
                $("#editTitle").val(element.value);
                $("#editPosition").val(result.data.position);
                $("#editDescription").val(result.data.description || "");
                $("#editLinkUrl").val(result.data.link_url || "");
                $("#editCtaText").val(result.data.cta_text || "");
                $("#editSortOrder").val(result.data.sort_order || 0);

                let targetPages = result.data.page_array ||
                    result.data.page || ["all"];
                if (typeof targetPages === "string") {
                    try {
                        targetPages = JSON.parse(targetPages);
                    } catch (e) {
                        targetPages = [targetPages];
                    }
                }
                if (!Array.isArray(targetPages) || targetPages.length === 0) {
                    targetPages = ["all"];
                }

                $(".edit-page-checkbox").prop("checked", false);
                if (targetPages.includes("all")) {
                    $(".edit-page-checkbox").prop("checked", true);
                } else {
                    targetPages.forEach(function (pageVal) {
                        $('.edit-page-checkbox[value="' + pageVal + '"]').prop(
                            "checked",
                            true,
                        );
                    });

                    if (
                        $(
                            ".edit-page-checkbox:not(.edit-page-all-checkbox):checked",
                        ).length ===
                        $(".edit-page-checkbox:not(.edit-page-all-checkbox)")
                            .length
                    ) {
                        $(".edit-page-all-checkbox").prop("checked", true);
                    }
                }

                let mediaUrl =
                    result.data.ad_media_url || result.data.ad_image_url;
                if (isEmpty(mediaUrl)) {
                    clearAdPreview(
                        "#editPreviewImage",
                        $("#adNoMediaSelectedText").val(),
                    );
                } else if (result.data.ad_media_type === "video") {
                    setAdVideoPreview("#editPreviewImage", mediaUrl);
                } else {
                    setAdImagePreview("#editPreviewImage", mediaUrl);
                }

                result.data.is_active == 1
                    ? $("#editIsActive").prop("checked", true)
                    : $("#editIsActive").prop("checked", false);

                $("#editAdsModal").appendTo("body").modal("show");
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
}

listenSubmit("#addAdNewForm", function (e) {
    e.preventDefault();
    processingBtn("#addAdNewForm", "#adSaveBtn", "loading");
    resetAdUploadProgress("ad");
    $.ajax({
        url: route("ads.store"),
        type: "POST",
        data: buildAdFormData(this, ".page-all-checkbox"),
        dataType: "JSON",
        processData: false,
        contentType: false,
        xhr: function () {
            return adUploadProgressXhr("ad");
        },
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $("#addAdsModal").modal("hide");
                Livewire.dispatch("refreshDatatable");
            }
        },
        error: function (result) {
            displayErrorMessage(getAdUploadErrorMessage(result));
            processingBtn("#addAdNewForm", "#adSaveBtn");
        },
        complete: function () {
            processingBtn("#addAdNewForm", "#adSaveBtn");
        },
    });
});

listenSubmit("#editAdForm", function (event) {
    event.preventDefault();
    processingBtn("#editAdForm", "#editAdSaveBtn", "loading");
    resetAdUploadProgress("editAd");
    const adUpdateId = $("#adId").val();
    $.ajax({
        url: route("ads.update", adUpdateId),
        type: "POST",
        data: buildAdFormData(this, ".edit-page-all-checkbox"),
        dataType: "JSON",
        processData: false,
        contentType: false,
        xhr: function () {
            return adUploadProgressXhr("editAd");
        },
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $("#editAdsModal").modal("hide");
                Livewire.dispatch("refreshDatatable");
            }
        },
        error: function (result) {
            displayErrorMessage(getAdUploadErrorMessage(result));
            processingBtn("#editAdForm", "#editAdSaveBtn");
        },
        complete: function () {
            processingBtn("#editAdForm", "#editAdSaveBtn");
        },
    });
});

listenClick(".addAdModal", function () {
    $("#addAdsModal").appendTo("body").modal("show");
});

listenChange(".isActiveAd", function () {
    let isActiveAdId = $(this).attr("data-id");
    changeIsActiveAdRenderData(isActiveAdId);
});

function changeIsActiveAdRenderData(isActiveAdId) {
    $.ajax({
        url: route("ads.change-is-active", isActiveAdId),
        method: "post",
        cache: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                Livewire.dispatch("refreshDatatable");
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
}

listenChange("#adStatusFilter", function () {
    Livewire.dispatch("changeStatusFilter", { status: $(this).val() });
});

listenClick("#adStatusFilter-ResetFilter", function () {
    $("#adStatusFilter").val(2).change();
    hideDropdownManually($("#adFilterBtn"), $(".dropdown-menu"));
});

function hideDropdownManually(button, menu) {
    button.dropdown("toggle");
}

function adUploadProgressXhr(prefix) {
    let xhr = $.ajaxSettings.xhr();

    if (xhr.upload) {
        xhr.upload.addEventListener(
            "progress",
            function (event) {
                if (!event.lengthComputable) {
                    return;
                }

                let percent = Math.round((event.loaded / event.total) * 100);
                updateAdUploadProgress(prefix, percent);
            },
            false,
        );
    }

    return xhr;
}

function updateAdUploadProgress(prefix, percent) {
    let progress = $("#" + prefix + "UploadProgress");
    let progressBar = $("#" + prefix + "UploadProgressBar");
    let progressText = $("#" + prefix + "UploadProgressText");

    progress.removeClass("d-none");
    progressBar.css("width", percent + "%").attr("aria-valuenow", percent);
    progressText.text(
        percent >= 100 ? $("#adSavingMediaText").val() : percent + "%",
    );
}

function resetAdUploadProgress(prefix) {
    updateAdUploadProgress(prefix, 0);
    $("#" + prefix + "UploadProgress").addClass("d-none");
}

function getAdUploadErrorMessage(result) {
    if (result.responseJSON && result.responseJSON.message) {
        return result.responseJSON.message;
    }

    if (result.status === 413) {
        return "The media is larger than the server upload limit. Increase PHP upload_max_filesize and post_max_size, then restart the server.";
    }

    if (result.status === 0) {
        return "Upload failed before the server responded. Please check the connection and server upload limit.";
    }

    return "The media could not be uploaded. Please try again.";
}

function buildAdFormData(form, allPageSelector) {
    const formData = new FormData(form);

    if ($(form).find(allPageSelector).is(":checked")) {
        formData.delete("page[]");
        formData.append("page[]", "all");
    }

    return formData;
}

function clearAdPreview(selector, text) {
    $(selector)
        .css("background-image", "none")
        .html('<span class="text-muted fs-12 px-2">' + text + "</span>");
}

function setAdImagePreview(selector, mediaUrl) {
    $(selector)
        .empty()
        .css("background-image", 'url("' + mediaUrl + '")');
}

function setAdVideoPreview(selector, mediaUrl) {
    $(selector)
        .css("background-image", "none")
        .html(
            '<video src="' +
                mediaUrl +
                '" autoplay muted loop playsinline preload="metadata" style="width:100%;height:100%;object-fit:contain;background:#000;border-radius:inherit;"></video>',
        );
}

listenChange(".page-all-checkbox", function () {
    if ($(this).is(":checked")) {
        $(".page-checkbox").prop("checked", true);
    } else {
        $(".page-checkbox").prop("checked", false);
    }
});

listenChange(".page-checkbox:not(.page-all-checkbox)", function () {
    if (!$(this).is(":checked")) {
        $(".page-all-checkbox").prop("checked", false);
    } else if (
        $(".page-checkbox:not(.page-all-checkbox):checked").length ===
        $(".page-checkbox:not(.page-all-checkbox)").length
    ) {
        $(".page-all-checkbox").prop("checked", true);
    }
});

listenChange(".edit-page-all-checkbox", function () {
    if ($(this).is(":checked")) {
        $(".edit-page-checkbox").prop("checked", true);
    } else {
        $(".edit-page-checkbox").prop("checked", false);
    }
});

listenChange(".edit-page-checkbox:not(.edit-page-all-checkbox)", function () {
    if (!$(this).is(":checked")) {
        $(".edit-page-all-checkbox").prop("checked", false);
    } else if (
        $(".edit-page-checkbox:not(.edit-page-all-checkbox):checked").length ===
        $(".edit-page-checkbox:not(.edit-page-all-checkbox)").length
    ) {
        $(".edit-page-all-checkbox").prop("checked", true);
    }
});
