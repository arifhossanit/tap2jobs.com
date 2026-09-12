document.addEventListener("DOMContentLoaded", loadCandidateGeneralData);
import "flatpickr/dist/l10n";

function loadCandidateGeneralData() {
    if (!$("#birthDate").length && !$("#availableAt").length) {
        return;
    }
    const $nationalityInput = $("#nationalityInput");
    const $isBangladeshi = $("#isBangladeshi");

    function syncNationalityInput() {
        if (!$nationalityInput.length || !$isBangladeshi.length) {
            return;
        }

        if ($isBangladeshi.prop("checked")) {
            $nationalityInput
                .val("Bangladeshi")
                .prop("readonly", true)
                .addClass("candidate-readonly-cross");
        } else {
            $nationalityInput
                .prop("readonly", false)
                .removeClass("candidate-readonly-cross");
        }
    }

    syncNationalityInput();
    $isBangladeshi.on("change", syncNationalityInput);
    const relevantQuillEditors = [];

    function initRelevantQuillEditors() {
        if (typeof Quill === "undefined") {
            return;
        }

        document
            .querySelectorAll("[data-relevant-quill-editor]")
            .forEach(function (element) {
                if (element.dataset.quillReady === "true") {
                    return;
                }

                const input = element
                    .closest(".candidate-relevant-editor")
                    .querySelector("[data-relevant-quill-input]");
                const quill = new Quill(element, {
                    modules: {
                        toolbar: [["bold", "italic"], [{ list: "bullet" }]],
                        keyboard: {
                            bindings: {
                                tab: "disabled",
                            },
                        },
                    },
                    placeholder: element.dataset.placeholder || "",
                    theme: "snow",
                });

                if (input && input.value) {
                    quill.root.innerHTML = input.value;
                }

                quill.on("text-change", function () {
                    if (input) {
                        input.value = quill.getText().trim().length
                            ? quill.root.innerHTML
                            : "";
                    }
                });

                element.dataset.quillReady = "true";
                relevantQuillEditors.push({ quill, input });
            });
    }

    window.syncRelevantQuillEditors = function () {
        relevantQuillEditors.forEach(function (editor) {
            if (editor.input) {
                editor.input.value = editor.quill.getText().trim().length
                    ? editor.quill.root.innerHTML
                    : "";
            }
        });
    };

    initRelevantQuillEditors();

    function scrollToProfileEditTarget(selector) {
        const target = document.querySelector(selector);
        if (!target) {
            return;
        }

        window.setTimeout(function () {
            if (typeof window.scrollCandidateProfileSection === 'function') {
                window.scrollCandidateProfileSection(target);
                return;
            }

            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 50);
    }

    $('[data-personal-edit-toggle]').on('click', function (event) {
        event.preventDefault();
        event.stopPropagation();
        $('.candidate-personal-summary').addClass('d-none');
        $('.candidate-personal-form').removeClass('d-none');
        $('.candidate-personal-image-actions').removeClass('d-none');
        $(this).addClass('d-none').closest('.candidate-profile-section__header').addClass('candidate-profile-section__header--editing');
        scrollToProfileEditTarget('.candidate-personal-form');
    });

    $("[data-personal-edit-close]").on("click", function (event) {
        event.preventDefault();
        $(".candidate-personal-form").addClass("d-none");
        $(".candidate-personal-image-actions").addClass("d-none");
        $(".candidate-personal-summary").removeClass("d-none");
        $("[data-personal-edit-toggle]")
            .removeClass("d-none")
            .closest(".candidate-profile-section__header")
            .removeClass("candidate-profile-section__header--editing");
    });

    $("[data-address-edit-toggle]").on("click", function (event) {
        event.preventDefault();
        event.stopPropagation();
        $('.candidate-address-summary').addClass('d-none');
        $('.candidate-address-form').removeClass('d-none');
        $(this).addClass('d-none').closest('.candidate-profile-section__header').addClass('candidate-profile-section__header--editing');
        scrollToProfileEditTarget('.candidate-address-form');
    });

    $("[data-address-edit-close]").on("click", function (event) {
        event.preventDefault();
        $(".candidate-address-form").addClass("d-none");
        $(".candidate-address-summary").removeClass("d-none");
        $("[data-address-edit-toggle]")
            .removeClass("d-none")
            .closest(".candidate-profile-section__header")
            .removeClass("candidate-profile-section__header--editing");
    });

    const resetAddressSelect = function (selector, placeholder) {
        if (!$(selector).length) {
            return;
        }

        $(selector)
            .empty()
            .append($('<option value=""></option>').text(placeholder));
        $(selector).trigger("change.select2");
    };

    const loadAddressThanas = function (
        citySelector,
        thanaSelector,
        selectedThana = null,
    ) {
        if (!$(thanaSelector).length) {
            return;
        }

        const city = $(citySelector).val();
        resetAddressSelect(thanaSelector, 'Select Thana');

        if (!city) {
            return;
        }

        $.ajax({
            url: route("thanas-list"),
            type: "get",
            dataType: "json",
            data: { city: city },
            success: function (data) {
                $.each(data.data, function (i, v) {
                    $(thanaSelector).append(
                        $("<option></option>").attr("value", i).text(v),
                    );
                });
                if (selectedThana) {
                    $(thanaSelector).val(selectedThana);
                }
                $(thanaSelector).trigger("change.select2");
            },
        });
    };

    const loadAddressCities = function (
        stateSelector,
        countrySelector,
        citySelector,
        thanaSelector = null,
        selectedCity = null,
        selectedThana = null,
    ) {
        const state = $(stateSelector).val();
        resetAddressSelect(citySelector, "Select your District");
        if (thanaSelector) {
            resetAddressSelect(thanaSelector, 'Select Thana');
        }

        if (!state) {
            return;
        }

        $.ajax({
            url: route("cities-list"),
            type: "get",
            dataType: "json",
            data: {
                state: state,
                country: $(countrySelector).val(),
            },
            success: function (data) {
                $.each(data.data, function (i, v) {
                    $(citySelector).append(
                        $("<option></option>").attr("value", i).text(v),
                    );
                });
                if (selectedCity) {
                    $(citySelector).val(selectedCity);
                }
                $(citySelector).trigger("change.select2");
                if (thanaSelector) {
                    loadAddressThanas(
                        citySelector,
                        thanaSelector,
                        selectedThana,
                    );
                }
            },
        });
    };

    const loadAddressStates = function (
        countrySelector,
        stateSelector,
        citySelector,
        thanaSelector = null,
        selectedState = null,
        selectedCity = null,
        selectedThana = null,
        statePlaceholder = "Select your Division",
    ) {
        const country = $(countrySelector).val();
        $(stateSelector)
            .empty()
            .append($('<option value=""></option>').text(statePlaceholder));
        resetAddressSelect(citySelector, "Select your District");
        if (thanaSelector) {
            resetAddressSelect(thanaSelector, 'Select Thana');
        }

        if (!country) {
            $(stateSelector).trigger("change.select2");
            return;
        }

        $.ajax({
            url: route("states-list"),
            type: "get",
            dataType: "json",
            data: { postal: country },
            success: function (data) {
                $.each(data.data, function (i, v) {
                    $(stateSelector).append(
                        $("<option></option>").attr("value", i).text(v),
                    );
                });
                if (selectedState) {
                    $(stateSelector).val(selectedState);
                    $(stateSelector).trigger("change.select2");
                    if (citySelector && (selectedCity || selectedThana)) {
                        loadAddressCities(
                            stateSelector,
                            countrySelector,
                            citySelector,
                            thanaSelector,
                            selectedCity,
                            selectedThana,
                        );
                    }
                } else {
                    $(stateSelector).trigger("change.select2");
                }
            },
        });
    };

    let lastPresentStateId = $("#stateId").val() || null;
    let lastPresentCityId = $("#cityId").val() || null;
    let lastPresentThanaId = $("#thanaId").val() || null;

    let lastPermanentStateId = $("#permanentStateId").val() || null;
    let lastPermanentCityId = $("#permanentCityId").val() || null;
    let lastPermanentThanaId = $("#permanentThanaId").val() || null;

    const togglePresentAddressMode = function (resetLocation = false) {
        const type = $('input[name="present_address_type"]:checked').val();
        const bangladeshId = $("#countryId").data("bangladesh-id");
        const isOutside = type === "outside";

        $(".candidate-address-country-field").toggleClass("d-none", !isOutside);
        $(".candidate-present-district-field").toggleClass("d-none", isOutside);
        $(".candidate-present-state-text-field").toggleClass(
            "d-none",
            !isOutside,
        );
        $(".candidate-present-thana-po-field").toggleClass("d-none", isOutside);

        if (type === "inside" && bangladeshId) {
            $("#countryId").val(bangladeshId);
            if (resetLocation) {
                const hasStateOptions = $("#stateId option").length > 1;
                const hasCityOptions = $("#cityId option").length > 1;
                const hasThanaOptions = $("#thanaId option").length > 1;

                if (hasStateOptions && lastPresentStateId) {
                    $("#stateId")
                        .val(lastPresentStateId)
                        .trigger("change.select2");
                    if (hasCityOptions && lastPresentCityId) {
                        $("#cityId")
                            .val(lastPresentCityId)
                            .trigger("change.select2");
                        if (hasThanaOptions && lastPresentThanaId) {
                            $("#thanaId")
                                .val(lastPresentThanaId)
                                .trigger("change.select2");
                        } else if (lastPresentThanaId) {
                            loadAddressThanas(
                                "#cityId",
                                "#thanaId",
                                lastPresentThanaId,
                            );
                        }
                    } else if (lastPresentCityId) {
                        loadAddressCities(
                            "#stateId",
                            "#countryId",
                            "#cityId",
                            "#thanaId",
                            lastPresentCityId,
                            lastPresentThanaId,
                        );
                    }
                } else {
                    loadAddressStates(
                        "#countryId",
                        "#stateId",
                        "#cityId",
                        "#thanaId",
                        lastPresentStateId,
                        lastPresentCityId,
                        lastPresentThanaId,
                    );
                }
            }
            return;
        }

        $("#countryId").val("");
    };


    let permanentAddressTypeChosen =
        $(".candidate-address-form").data("has-permanent-details") == 1;

    const togglePermanentAddress = function () {
        const sameAsPresent = $("#permanentSameAsPresent").is(":checked");
        const type = $('input[name="permanent_address_type"]:checked').val();
        const bangladeshId = $("#countryId").data("bangladesh-id");
        const showPermanentFields =
            !sameAsPresent && permanentAddressTypeChosen;
        const isOutside = type === "outside";

        $(".candidate-permanent-address-options").toggleClass(
            "d-none",
            sameAsPresent,
        );
        $(".candidate-permanent-address-fields").toggleClass(
            "d-none",
            !showPermanentFields,
        );
        $(".candidate-permanent-country-field").toggleClass(
            "d-none",
            !showPermanentFields || !isOutside,
        );
        $(".candidate-permanent-district-field").toggleClass(
            "d-none",
            showPermanentFields && isOutside,
        );
        $(".candidate-permanent-state-text-field").toggleClass(
            "d-none",
            !showPermanentFields || !isOutside,
        );
        $(".candidate-permanent-thana-po-field").toggleClass(
            "d-none",
            showPermanentFields && isOutside,
        );

        if (showPermanentFields && type === "inside" && bangladeshId) {
            $("#permanentCountryId")
                .val(bangladeshId)
                .trigger("change.select2");
        }
    };

    togglePresentAddressMode($("#stateId option").length <= 1);
    togglePermanentAddress();

    $('input[name="present_address_type"]').on("change", function () {
        togglePresentAddressMode(true);
    });

    $("#permanentSameAsPresent").on("change", function () {
        if ($(this).is(":checked")) {
            permanentAddressTypeChosen = false;
            $("#permanentAddressSelected").val("0");
        } else {
            permanentAddressTypeChosen =
                $('input[name="permanent_address_type"]:checked').length > 0;
            $("#permanentAddressSelected").val("1");
        }
        togglePermanentAddress();
    });

    $('input[name="permanent_address_type"]').on("change", function () {
        permanentAddressTypeChosen = true;
        $("#permanentAddressSelected").val("1");
        togglePermanentAddress();
    });

    $("#permanentCountryId").on("change", function () {
        if (
            $('input[name="permanent_address_type"]:checked').val() !==
            "outside"
        ) {
            const hasStateOptions = $("#permanentStateId option").length > 1;
            const hasCityOptions = $("#permanentCityId option").length > 1;
            const hasThanaOptions = $("#permanentThanaId option").length > 1;

            if (hasStateOptions && lastPermanentStateId) {
                $("#permanentStateId")
                    .val(lastPermanentStateId)
                    .trigger("change.select2");
                if (hasCityOptions && lastPermanentCityId) {
                    $("#permanentCityId")
                        .val(lastPermanentCityId)
                        .trigger("change.select2");
                    if (hasThanaOptions && lastPermanentThanaId) {
                        $("#permanentThanaId")
                            .val(lastPermanentThanaId)
                            .trigger("change.select2");
                    } else if (lastPermanentThanaId) {
                        loadAddressThanas(
                            "#permanentCityId",
                            "#permanentThanaId",
                            lastPermanentThanaId,
                        );
                    }
                } else if (lastPermanentCityId) {
                    loadAddressCities(
                        "#permanentStateId",
                        "#permanentCountryId",
                        "#permanentCityId",
                        "#permanentThanaId",
                        lastPermanentCityId,
                        lastPermanentThanaId,
                    );
                }
            } else {
                loadAddressStates(
                    "#permanentCountryId",
                    "#permanentStateId",
                    "#permanentCityId",
                    "#permanentThanaId",
                    lastPermanentStateId,
                    lastPermanentCityId,
                    lastPermanentThanaId,
                );
            }
        }
    });

    $("#stateId").on("change", function () {
        const val = $(this).val();
        if (val) {
            lastPresentStateId = val;
        }
        loadAddressCities("#stateId", "#countryId", "#cityId", "#thanaId");
    });

    $("#cityId").on("change", function () {
        const val = $(this).val();
        if (val) {
            lastPresentCityId = val;
        }
        loadAddressThanas("#cityId", "#thanaId");
    });

    $("#thanaId").on("change", function () {
        const val = $(this).val();
        if (val) {
            lastPresentThanaId = val;
        }
    });

    $("#permanentStateId").on("change", function () {
        const val = $(this).val();
        if (val) {
            lastPermanentStateId = val;
        }
        loadAddressCities(
            "#permanentStateId",
            "#permanentCountryId",
            "#permanentCityId",
            "#permanentThanaId",
        );
    });

    $("#permanentCityId").on("change", function () {
        const val = $(this).val();
        if (val) {
            lastPermanentCityId = val;
        }
        loadAddressThanas("#permanentCityId", "#permanentThanaId");
    });

    $("#permanentThanaId").on("change", function () {
        const val = $(this).val();
        if (val) {
            lastPermanentThanaId = val;
        }
    });

    $("[data-career-edit-toggle]").on("click", function (event) {
        event.preventDefault();
        event.stopPropagation();
        $('.candidate-career-summary').addClass('d-none');
        $('.candidate-career-form').removeClass('d-none');
        $(this).addClass('d-none').closest('.candidate-profile-section__header').addClass('candidate-profile-section__header--editing');
        scrollToProfileEditTarget('.candidate-career-form');
    });

    $("[data-career-edit-close]").on("click", function (event) {
        event.preventDefault();
        $(".candidate-career-form").addClass("d-none");
        $(".candidate-career-summary").removeClass("d-none");
        $("[data-career-edit-toggle]")
            .removeClass("d-none")
            .closest(".candidate-profile-section__header")
            .removeClass("candidate-profile-section__header--editing");
    });

    $("[data-preferred-edit-toggle]").on("click", function (event) {
        event.preventDefault();
        event.stopPropagation();
        $('.candidate-preferred-summary').addClass('d-none');
        $('.candidate-preferred-form').removeClass('d-none');
        $(this).addClass('d-none').closest('.candidate-profile-section__header').addClass('candidate-profile-section__header--editing');
        scrollToProfileEditTarget('.candidate-preferred-form');
    });

    $("[data-preferred-edit-close]").on("click", function (event) {
        event.preventDefault();
        $(".candidate-preferred-form").addClass("d-none");
        $(".candidate-preferred-summary").removeClass("d-none");
        $("[data-preferred-edit-toggle]")
            .removeClass("d-none")
            .closest(".candidate-profile-section__header")
            .removeClass("candidate-profile-section__header--editing");
    });

    $("[data-relevant-edit-toggle]").on("click", function (event) {
        event.preventDefault();
        event.stopPropagation();
        $('.candidate-relevant-summary').addClass('d-none');
        $('.candidate-relevant-form').removeClass('d-none');
    initRelevantQuillEditors();
        $(this).addClass('d-none').closest('.candidate-profile-section__header').addClass('candidate-profile-section__header--editing');
        scrollToProfileEditTarget('.candidate-relevant-form');
    });

    $("[data-relevant-edit-close]").on("click", function (event) {
        event.preventDefault();
        $(".candidate-relevant-form").addClass("d-none");
        $(".candidate-relevant-summary").removeClass("d-none");
        $("[data-relevant-edit-toggle]")
            .removeClass("d-none")
            .closest(".candidate-profile-section__header")
            .removeClass("candidate-profile-section__header--editing");
    });

    $("[data-disability-edit-toggle]").on("click", function (event) {
        event.preventDefault();
        event.stopPropagation();
        $('.candidate-disability-summary').addClass('d-none');
        $('.candidate-disability-form').removeClass('d-none');
        $(this).addClass('d-none').closest('.candidate-profile-section__header').addClass('candidate-profile-section__header--editing');
        scrollToProfileEditTarget('.candidate-disability-form');
    });

    $("[data-disability-edit-close]").on("click", function (event) {
        event.preventDefault();
        $(".candidate-disability-form").addClass("d-none");
        $(".candidate-disability-summary").removeClass("d-none");
        $("[data-disability-edit-toggle]")
            .removeClass("d-none")
            .closest(".candidate-profile-section__header")
            .removeClass("candidate-profile-section__header--editing");
    });

    function syncDisabilityDetails() {
        const showDetails = $("[data-disability-toggle]:checked").val() === "1";
        const $details = $("[data-disability-details]");
        const $detailInputs = $("[data-disability-detail-input]");

        $details.toggleClass("d-none", !showDetails);
        $("[data-disability-support]").toggleClass("d-none", showDetails);
        $detailInputs.prop("disabled", !showDetails);

        if (!showDetails) {
            $detailInputs.each(function () {
                if ($(this).is(":radio")) {
                    $(this).prop("checked", $(this).val() === "1");
                    return;
                }

                $(this).val("");
            });
        }
    }

    syncDisabilityDetails();
    $("[data-disability-toggle]").on("change", syncDisabilityDetails);

    $("#candidatePersonalImageInput").on("change", function () {
        const input = this;
        const file = this.files && this.files[0];

        if (!isValidFile($(this), "#validationErrors")) {
            displayErrorMessage(
                "The image must be a file of type: jpeg, jpg, png.",
            );
            $(".btnSave").prop("disabled", true);
            return;
        }

        if (
            !file ||
            !["image/jpeg", "image/png"].includes(file.type) ||
            file.size > 1024 * 1024
        ) {
            $(this).val("");
            displayErrorMessage(
                "Upload your Profile image JPG or PNG, 1MB max.",
            );
            $(".btnSave").prop("disabled", true);
            return;
        }

        const formData = new FormData();
        formData.append("image", file);

        $(
            ".candidate-image-upload-modal__upload, [data-candidate-image-input-trigger]",
        ).prop("disabled", true);

        $.ajax({
            url: route("candidate-profile.image.update"),
            type: "post",
            data: formData,
            processData: false,
            contentType: false,
            success: function (result) {
                const avatar =
                    result.data && result.data.avatar
                        ? result.data.avatar
                        : null;
                if (avatar) {
                    $("#candidatePersonalAvatar")
                        .attr("src", avatar)
                        .attr("data-original-src", avatar)
                        .data("original-src", avatar);
                } else {
                    displayPhoto(input, "#candidatePersonalAvatar");
                }
                closeCandidateImageModal();
                displaySuccessMessage(result.message);
            },
            error: function (result) {
                displayErrorMessage(
                    result.responseJSON && result.responseJSON.message
                        ? result.responseJSON.message
                        : "The image could not be uploaded.",
                );
            },
            complete: function () {
                $("#candidatePersonalImageInput").val("");
                $(
                    ".candidate-image-upload-modal__upload, [data-candidate-image-input-trigger]",
                ).prop("disabled", false);
                $(".btnSave").prop("disabled", false);
            },
        });
    });

    function openCandidateImageModal() {
        $("#candidateImageUploadModal")
            .removeClass("d-none")
            .attr("aria-hidden", "false");
        $("body").addClass("candidate-image-upload-modal-open");
    }

    function closeCandidateImageModal() {
        $("#candidateImageUploadModal")
            .addClass("d-none")
            .attr("aria-hidden", "true");
        $("body").removeClass("candidate-image-upload-modal-open");
        $("[data-candidate-image-dropzone]").removeClass(
            "candidate-image-upload-modal__dropzone--dragging",
        );
    }

    $("[data-candidate-image-modal-open]").on("click", function (event) {
        event.preventDefault();
        openCandidateImageModal();
    });

    $("[data-candidate-image-modal-close]").on("click", function (event) {
        event.preventDefault();
        closeCandidateImageModal();
    });

    $("[data-candidate-image-input-trigger]").on("click", function (event) {
        event.preventDefault();
        $("#candidatePersonalImageInput").trigger("click");
    });

    $("[data-candidate-image-dropzone]")
        .on("click", function (event) {
            if (
                $(event.target).closest("[data-candidate-image-input-trigger]")
                    .length
            ) {
                return;
            }

            $("#candidatePersonalImageInput").trigger("click");
        })
        .on("dragover", function (event) {
            event.preventDefault();
            $(this).addClass(
                "candidate-image-upload-modal__dropzone--dragging",
            );
        })
        .on("dragleave drop", function (event) {
            event.preventDefault();
            $(this).removeClass(
                "candidate-image-upload-modal__dropzone--dragging",
            );

            if (event.type !== "drop") {
                return;
            }

            const files =
                event.originalEvent.dataTransfer &&
                event.originalEvent.dataTransfer.files;
            if (!files || !files.length) {
                return;
            }

            $("#candidatePersonalImageInput")[0].files = files;
            $("#candidatePersonalImageInput").trigger("change");
        });

    $(".candidate-personal-delete").on("click", function (event) {
        event.preventDefault();
        const $button = $(this);

        swal({
            title: Lang.get("js.delete") + " !",
            text: Lang.get("js.are_you_sure") + ' "Profile Image" ?',
            buttons: {
                confirm: Lang.get("js.yes_delete"),
                cancel: Lang.get("js.no_cancel"),
            },
            reverseButtons: true,
            icon: "warning",
            confirmButtonColor: "#F62947",
        }).then(function (willDelete) {
            if (!willDelete) {
                return;
            }

            $button.prop("disabled", true);

            $.ajax({
                url: route("candidate-profile.image.delete"),
                type: "delete",
                success: function (result) {
                    const avatar =
                        result.data && result.data.avatar
                            ? result.data.avatar
                            : $("#candidatePersonalAvatar").data(
                                  "original-src",
                              );
                    $("#candidatePersonalImageInput").val("");
                    $("#candidatePersonalAvatar")
                        .attr("src", avatar)
                        .attr("data-original-src", avatar)
                        .data("original-src", avatar);
                    displaySuccessMessage(result.message);
                },
                error: function (result) {
                    displayErrorMessage(
                        result.responseJSON && result.responseJSON.message
                            ? result.responseJSON.message
                            : "The image could not be deleted.",
                    );
                },
                complete: function () {
                    $button.prop("disabled", false);
                },
            });
        });
    });

    $("#birthDate").flatpickr({
        format: "YYYY-MM-DD",
        useCurrent: true,
        sideBySide: true,
        locale: getLoggedInUserLang,
        maxDate: new Date(),
    });

    $("#availableAt").flatpickr({
        format: "YYYY-MM-DD",
        useCurrent: false,
        sideBySide: true,
        locale: getLoggedInUserLang,
        minDate: new Date(),
    });

    if ($("#passportIssueDate").length) {
        $("#passportIssueDate").flatpickr({
            format: "YYYY-MM-DD",
            useCurrent: false,
            sideBySide: true,
            locale: getLoggedInUserLang,
        });
    }

    if ($("#candidateProfileUpdate").length) {
        $(
            "#salaryCurrencyId,#stateId,#cityId,#thanaId,#industryId,#careerLevelId,#functionalAreaId,#permanentCountryId,#permanentStateId,#permanentCityId,#permanentThanaId",
        ).select2({
            width: "100%",
        });
        $("#createCityStateID").select2({
            width: "100%",
            dropdownParent: $("#createCityModal"),
        });
    }
    if ($("#skillId").length && $("#languageId").length) {
        $("#skillId").select2({
            width: "100%",
            placeholder: Lang.get("js.select_skill"),
        });
        $("#languageId").select2({
            width: "100%",
            placeholder: Lang.get("js.select_language"),
        });
    }
    $(".form-select")
        .on("select2:open", function () {
            $(this)
                .next(".select2-container")
                .addClass("select2-container--open-chevron");
        })
        .on("select2:close", function () {
            $(this)
                .next(".select2-container")
                .removeClass("select2-container--open-chevron");
        });
    $(".candidate-profile-accordion .form-select")
        .not("[multiple]")
        .on("mousedown", function () {
            if ($(this).next(".select2-container").length) {
                return;
            }

            $(this).addClass("candidate-select-open");
        })
        .on("change blur", function () {
            $(this).removeClass("candidate-select-open");
        });
    setTimeout(function () {
        $("input[type=radio][name=immediate_available]").trigger("change");
    }, 300);

    function renderPreferredCheckboxChips(target) {
        const $target = $(target);
        if (!$target.length) {
            return;
        }

        $target.empty();
        $(
            '.candidate-preferred-checkbox[data-chip-target="' +
                target +
                '"]:checked',
        ).each(function () {
            const $checkbox = $(this);
            const $chip = $('<span class="candidate-preferred-chip"></span>');
            $chip.text($checkbox.data("label"));
            $('<button type="button" aria-label="Remove">&times;</button>')
                .appendTo($chip)
                .on("click", function () {
                    $checkbox.prop("checked", false).trigger("change");
                });
            $target.append($chip);
        });
    }

    $(".candidate-preferred-checkbox")
        .each(function () {
            renderPreferredCheckboxChips($(this).data("chip-target"));
        })
        .on("change", function () {
            renderPreferredCheckboxChips($(this).data("chip-target"));
        });

    const normalizePreferredSelectText = function (text) {
        return $.trim(text || "").toLowerCase();
    };
    const preferredSelectMatcher = function (params, data) {
        const term = normalizePreferredSelectText(params.term);
        if (!term) {
            return data;
        }

        const text = normalizePreferredSelectText(data.text);
        if (!text || !text.includes(term)) {
            return null;
        }

        const words = text.split(/[\s\-\/]+/).filter(Boolean);
        const matchedData = $.extend(true, {}, data);
        matchedData.matchPriority = text.startsWith(term)
            ? 0
            : words.some(function (word) { return word.startsWith(term); })
                ? 1
                : 2;

        return matchedData;
    };
    const preferredSelectSorter = function (data) {
        if (!data.some(function (item) { return item.matchPriority !== undefined; })) {
            return data;
        }

        return data.sort(function (first, second) {
            const priorityDifference =
                (first.matchPriority || 0) - (second.matchPriority || 0);
            return priorityDifference || normalizePreferredSelectText(first.text)
                .localeCompare(normalizePreferredSelectText(second.text));
        });
    };
    $("#preferredJobCategories, #preferredInsideDistricts").each(function () {
        const $preferredSelect = $(this);
        if ($preferredSelect.hasClass("select2-hidden-accessible")) {
            $preferredSelect.select2("destroy");
        }

        $preferredSelect.select2({
            width: "100%",
            placeholder: $preferredSelect.data("placeholder") || "",
            closeOnSelect: false,
            dropdownCssClass: "candidate-preferred-select-dropdown",
            matcher: preferredSelectMatcher,
            sorter: preferredSelectSorter,
            maximumSelectionLength:
                Number($preferredSelect.data("maximum-selection-length")) || 0,
        });

        $preferredSelect.on("select2:select", function () {
            window.setTimeout(function () {
                $preferredSelect
                    .next(".select2-container")
                    .find(".select2-search__field")
                    .val("")
                    .trigger("input")
                    .trigger("keyup");
                $(".select2-container--open .select2-search__field")
                    .val("")
                    .trigger("input")
                    .trigger("keyup");
            }, 0);
        });
    });

    $("#countryId").on("change", function () {
        $.ajax({
            url: route("states-list"),
            type: "get",
            dataType: "json",
            data: { postal: $(this).val() },
            success: function (data) {
                $("#cityId").empty();
                $("#cityId").append(
                    $('<option value=""></option>').text(
                        Lang.get("js.select_city"),
                    ),
                );
                $("#thanaId").empty();
                $("#thanaId").append(
                    $('<option value=""></option>').text(
                        Lang.get("js.select_thana") || "Select Thana",
                    ),
                );
                $("#stateId").empty();
                $("#stateId").append(
                    $('<option value=""></option>').text(
                        Lang.get("js.select_state"),
                    ),
                );
                $.each(data.data, function (i, v) {
                    $("#stateId").append(
                        $("<option></option>").attr("value", i).text(v),
                    );
                });
                // if (isEdit && stateId) {
                //     $('#stateId').val(stateId).trigger('change');
                // }
            },
        });
    });

    $("#stateId").on("change", function () {
        if (!$("#cityId").length) {
            return;
        }

        $.ajax({
            url: route("cities-list"),
            type: "get",
            dataType: "json",
            data: {
                state: $(this).val(),
                country: $("#countryId").val(),
            },
            success: function (data) {
                $("#cityId").empty();
                $("#cityId").append(
                    $('<option value=""></option>').text(
                        Lang.get("js.select_city"),
                    ),
                );
                $.each(data.data, function (i, v) {
                    $("#cityId").append(
                        $("<option ></option>").attr("value", i).text(v),
                    );
                });
                loadAddressThanas("#cityId", "#thanaId");
                // if (isEdit && cityId) {
                //     $('#cityId').val(cityId).trigger('change');
                // }
            },
        });
    });
    // if (isEdit & countryId) {
    //     $('#countryId').val(countryId).trigger('change');
    // }

    $(document).on("change", "#profile", function () {
        let validFile = isValidFile($(this), "#validationErrors");
        if (validFile) {
            displayPhoto(this, "#profilePreview");
            $(".btnSave").prop("disabled", false);
        } else {
            $(".btnSave").prop("disabled", true);
        }
    });
    $("input[type=radio][name=immediate_available]").change(function () {
        let radioValue = $("input[name='immediate_available']:checked").val();
        if (radioValue == 1) {
            $(".available-at").hide();
        } else {
            $(".available-at").show();
        }
    });

    $("#available").click(function () {
        radio();
    });
    $("#not_available").click(function () {
        radio();
    });

    function radio() {
        let radioValue = $("input[name='immediate_available']:checked").val();
        if (radioValue == "0") {
            $(".available-at").show();
        } else {
            $(".available-at").hide();
        }
    }
}

$(document).on("keyup", "#facebookUrl", function () {
    this.value = this.value.toLowerCase();
});
$(document).on("keyup", "#twitterUrl", function () {
    this.value = this.value.toLowerCase();
});
$(document).on("keyup", "#linkedInUrl", function () {
    this.value = this.value.toLowerCase();
});
$(document).on("keyup", "#googlePlusUrl", function () {
    this.value = this.value.toLowerCase();
});
$(document).on("keyup", "#pinterestUrl", function () {
    this.value = this.value.toLowerCase();
});

// City modal handlers for candidate profile
// (jobs/create-edit.js registers .createCityModal only on job forms)
$(document).on("click", ".createCityModal", function () {
    if (!$("#candidateProfileUpdate").length || !$("#createCityModal").length) {
        return;
    }

    let $modalState = $("#createCityStateID");
    let state = $("#stateId").val();

    // Keep modal states in sync with the profile state dropdown (country-filtered)
    if ($modalState.length && $("#stateId").length) {
        $modalState.empty();
        $("#stateId option").each(function () {
            $modalState.append($(this).clone());
        });
        $modalState.val(state).trigger("change");
    }

    $("#createCityModal").appendTo("body").modal("show");
});

$(document).on("hidden.bs.modal", "#createCityModal", function () {
    if (!$("#candidateProfileUpdate").length) {
        return;
    }
    $("#createCityStateID").val("").trigger("change");
    resetModalForm("#createCityForm", "#cityValidationErrorsBox");
});

$(document).on("submit", "#candidateProfileUpdate", function (e) {
    e.preventDefault();
    const form = this;
    const submitter =
        e.originalEvent && e.originalEvent.submitter
            ? e.originalEvent.submitter
            : null;
    const submitAction =
        submitter && submitter.getAttribute("formaction")
            ? submitter.getAttribute("formaction")
            : null;
    const submitMethod =
        submitter && submitter.getAttribute("formmethod")
            ? submitter.getAttribute("formmethod")
            : null;
    const isScopedSectionSubmit =
        submitAction && submitAction !== form.getAttribute("action");
    const isScopedAjaxSubmit =
        submitter && submitter.hasAttribute("data-scoped-ajax-submit");

    if (typeof window.syncRelevantQuillEditors === "function") {
        window.syncRelevantQuillEditors();
    }

    if (
        !isScopedSectionSubmit &&
        !$("#error-msg").hasClass("d-none") &&
        $("#error-msg").text() !== ""
    ) {
        $("#phoneNumber").focus();
        return false;
    }


    let facebookExp = new RegExp(
        /^(https?:\/\/)?((m{1}\.)?)?((w{3}\.)?)facebook.[a-z]{2,3}\/?.*/i,
    );
    let twitterExp = new RegExp(
        /^(https?:\/\/)?((m{1}\.)?)?((w{3}\.)?)twitter\.[a-z]{2,3}\/?.*/i,
    );
    let googlePlusExp = new RegExp(
        /^(https?:\/\/)?((w{3}\.)?)?(plus\.)?(google\.[a-z]{2,3})\/?(([a-zA-Z 0-9._])?).*/i,
    );
    let linkedInExp = new RegExp(
        /^(https?:\/\/)?((w{3}\.)?)linkedin\.[a-z]{2,3}\/?.*/i,
    );
    let pinterestExp = new RegExp(
        /^(https?:\/\/)?((w{3}\.)?)pinterest\.[a-z]{2,3}\/?.*/i,
    );

    const validateOptionalUrl = function (selector, expression, message) {
        const value = $(selector).length ? $(selector).val() : "";
        if (!urlValidation(value, expression)) {
            displayErrorMessage(message);
            return false;
        }

        return true;
    };

    if (!isScopedSectionSubmit) {
        if (
            !validateOptionalUrl(
                "#facebookUrl",
                facebookExp,
                Lang.get("js.valid_facebook_url"),
            )
        ) {
            return false;
        }
        if (
            !validateOptionalUrl(
                "#twitterUrl",
                twitterExp,
                Lang.get("js.valid_twitter_url"),
            )
        ) {
            return false;
        }
        if (
            !validateOptionalUrl(
                "#googlePlusUrl",
                googlePlusExp,
                Lang.get("js.valid_google_plus_url"),
            )
        ) {
            return false;
        }
        if (
            !validateOptionalUrl(
                "#linkedInUrl",
                linkedInExp,
                Lang.get("js.valid_linkedin_url"),
            )
        ) {
            return false;
        }
        if (
            !validateOptionalUrl(
                "#pinterestUrl",
                pinterestExp,
                Lang.get("js.valid_pinterest_url"),
            )
        ) {
            return false;
        }
    }

    if (isScopedAjaxSubmit) {
        const formData = new FormData(form);
        const $submitter = $(submitter);
        $submitter.prop("disabled", true);

        $.ajax({
            url: submitAction,
            type: "post",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                Accept: "application/json",
            },
            success: function (result) {
                displaySuccessMessage(result.message);
                const panel = submitter.closest('.candidate-profile-section__collapse');
                window.refreshCandidateProfileSection('personal-information', panel.id);
            },
            error: function (result) {
                let message =
                    result.responseJSON && result.responseJSON.message
                        ? result.responseJSON.message
                        : Lang.get("js.error");

                if (
                    result.status === 422 &&
                    result.responseJSON &&
                    result.responseJSON.errors
                ) {
                    const firstErrorKey = Object.keys(
                        result.responseJSON.errors,
                    )[0];
                    if (
                        firstErrorKey &&
                        result.responseJSON.errors[firstErrorKey][0]
                    ) {
                        message = result.responseJSON.errors[firstErrorKey][0];
                    }
                }

                displayErrorMessage(message);
            },
            complete: function () {
                $submitter.prop("disabled", false);
            },
        });

        return false;
    }

    if (submitAction) {
        form.setAttribute("action", submitAction);
    }
    if (submitMethod) {
        form.setAttribute("method", submitMethod);
    }
    form.submit();

    return true;
});

// Refresh saved results without navigating or replacing sibling edit forms.
window.refreshCandidateProfileSection = async function (section, panelId) {
    try {
        const response = await fetch(route('candidate.profile', { section: section }), {
            headers: { Accept: 'text/html' }, cache: 'no-store'
        });
        if (!response.ok || response.redirected) throw new Error('Unable to refresh profile.');
        const page = new DOMParser().parseFromString(await response.text(), 'text/html');
        const progress = page.querySelector('.candidate-profile-progress');
        if (!progress) throw new Error('Unable to refresh profile.');
        const currentProgress = document.querySelector('.candidate-profile-progress');
        if (currentProgress) currentProgress.replaceWith(progress);
        if (!panelId) return;
        const currentBody = document.getElementById(panelId);
        const freshBody = page.getElementById(panelId);
        if (!currentBody || !freshBody) throw new Error('Unable to refresh saved section.');
        if (section === 'personal-information') {
            for (const name of ['personal', 'address', 'career', 'preferred', 'relevant', 'disability']) {
                const selector = '.candidate-' + name + '-summary';
                const current = currentBody.querySelector(selector);
                const fresh = freshBody.querySelector(selector);
                if (current && fresh) {
                    current.innerHTML = fresh.innerHTML;
                    const close = currentBody.querySelector('[data-' + name + '-edit-close]');
                    if (close) close.click();
                }
            }
            return;
        }
        const current = currentBody.closest('.candidate-profile-section');
        const fresh = freshBody.closest('.candidate-profile-section');
        if (!current || !fresh) throw new Error('Unable to refresh saved section.');
        current.replaceWith(fresh);
        freshBody.removeAttribute('data-bs-parent');
        freshBody.classList.add('show');
        if (section === 'education-training' && window.initCandidateEducationPage) window.initCandidateEducationPage(fresh);
        if (section === 'employment' && window.initCandidateEmploymentPage) window.initCandidateEmploymentPage(fresh);
        if (window.initCandidateProfileRefreshedPanel) window.initCandidateProfileRefreshedPanel(fresh);
    } catch (error) {
        displayErrorMessage('Saved, but the displayed result could not be refreshed. Please refresh the page to see it.');
    }
};
// Shared validation for candidate profile forms, including dynamically refreshed panels.
(function () {
    let submitted = null;
    const suppressedValidationMessages = new Map();
    const originalDisplayErrorMessage = window.displayErrorMessage;
    const messageText = value => {
        if (Array.isArray(value)) return messageText(value[0]);
        if (value && typeof value === 'object') {
            if (value.message) return messageText(value.message);
            if (value.responseJSON) return messageText(value.responseJSON);
            if (value.errors) {
                const first = Object.values(value.errors)[0];
                if (first) return messageText(first);
            }
        }
        return typeof value === 'string' ? value.trim() : '';
    };
    const suppressValidationToast = message => {
        const text = messageText(message);
        if (text) suppressedValidationMessages.set(text, Date.now() + 3000);
    };
    const isSuppressedValidationToast = message => {
        const text = messageText(message);
        const expiresAt = suppressedValidationMessages.get(text);
        if (!expiresAt) return false;
        suppressedValidationMessages.delete(text);
        return expiresAt >= Date.now();
    };

    // Every candidate-profile request can use the same response-message parser.
    window.getCandidateProfileErrorMessage = function (error, fallback) {
        return messageText(error) || messageText(fallback) ||
            (typeof Lang !== 'undefined' ? Lang.get('js.error') : 'Something went wrong.');
    };
    if (typeof originalDisplayErrorMessage === 'function') {
        window.displayErrorMessage = function (message) {
            if (isSuppressedValidationToast(message)) return;
            return originalDisplayErrorMessage(window.getCandidateProfileErrorMessage(message));
        };
    }
    const visible = element => !!element && element.getClientRects().length > 0;
    const scopeFor = (form, button) => button?.closest('.candidate-profile-section__collapse') || form;
    const isProfile = form => !!form && !!document.querySelector('.candidate-profile-menu') &&
        (form.id === 'candidateProfileUpdate' || !!form.closest('.candidate-profile-section'));
    const anchorFor = field => {
        if (field._flatpickr?.altInput) return field._flatpickr.altInput;
        if (field.matches('select')) {
            const next = field.nextElementSibling;
            if (next?.matches('.select2-container, .candidate-education-custom-select')) return next;
        }
        if (field.type === 'hidden' || field.classList.contains('d-none')) {
            return field.parentElement.querySelector('.ql-editor, [contenteditable="true"]') || field;
        }
        return field;
    };
    const feedbacks = new WeakMap();
    let feedbackId = 0;
    const requiredMessage = () => document.documentElement.lang.startsWith('bn')
        ? 'এই ঘরটি পূরণ করা আবশ্যক।' : 'This field is required.';
    const clear = field => {
        const feedback = feedbacks.get(field);
        if (feedback) {
            const descriptions = (field.getAttribute('aria-describedby') || '').split(/\s+/).filter(id => id && id !== feedback.id);
            if (descriptions.length) field.setAttribute('aria-describedby', descriptions.join(' '));
            else field.removeAttribute('aria-describedby');
            feedback.remove();
            feedbacks.delete(field);
        }
        field.classList.remove('is-invalid');
        field.removeAttribute('aria-invalid');
        anchorFor(field).classList.remove('candidate-profile-field-invalid');
    };
    const mark = (field, message) => {
        let feedback = feedbacks.get(field);
        if (!feedback) {
            feedback = document.createElement('div');
            feedback.id = 'candidate-field-error-' + (++feedbackId);
            feedback.className = 'invalid-feedback d-block candidate-profile-field-feedback';
            feedback.setAttribute('role', 'alert');
            const anchor = anchorFor(field);
            const feedbackContainer = anchor.closest('[data-profile-feedback-container]');
            if (feedbackContainer) feedbackContainer.appendChild(feedback);
            else {
                const container = anchor.closest('.input-group, .ql-container') || anchor;
                container.insertAdjacentElement('afterend', feedback);
            }
            feedbacks.set(field, feedback);
            field.setAttribute('aria-describedby', [field.getAttribute('aria-describedby'), feedback.id].filter(Boolean).join(' '));
        }
        feedback.textContent = message || (field.validity?.valueMissing || !String(field.value || '').trim()
            ? requiredMessage() : field.validationMessage || requiredMessage());
        field.classList.add('is-invalid');
        field.setAttribute('aria-invalid', 'true');
        anchorFor(field).classList.add('candidate-profile-field-invalid');
    };
    const focus = field => {
        const anchor = anchorFor(field);
        anchor.scrollIntoView({ behavior: 'smooth', block: 'center' });
        const target = anchor.matches('input, select, textarea, [contenteditable="true"]')
            ? anchor : anchor.querySelector('input, button, [tabindex]') || field;
        target.focus({ preventScroll: true });
    };
    const fieldsIn = scope => Array.from(scope.querySelectorAll('input, select, textarea'))
        .filter(field => !field.disabled && !['submit', 'button', 'reset'].includes(field.type) && visible(anchorFor(field)));
    const validate = scope => {
        let first = null;
        for (const field of fieldsIn(scope)) {
            clear(field);
            const anchor = anchorFor(field);
            const wrapper = field.closest('.candidate-education-form-field, .candidate-address-field, .candidate-reference-field, .form-group, .mb-3, .mb-4');
            const labelledRequired = wrapper?.querySelector('label.required, .form-label.required');
            const required = !field.hasAttribute('data-profile-optional') && (field.required || !!labelledRequired);
            const value = anchor.matches('[contenteditable="true"]') ? anchor.textContent.trim() : String(field.value || '').trim();
            let empty = !value;
            if (field.type === 'checkbox') empty = !field.checked;
            if (field.type === 'radio') empty = !fieldsIn(scope).some(other => other.name === field.name && other.checked);
            const invalid = (required && empty) || (field.willValidate && !field.validity.valid);
            if (invalid) { mark(field, required && empty ? requiredMessage() : field.validationMessage); first ||= field; }
        }
        if (first) focus(first);
        return !first;
    };
    document.addEventListener('click', function (event) {
        const button = event.target.closest('button, input[type="submit"]');
        if (!button || button.type !== 'submit' || !isProfile(button.form)) return;
        const scope = scopeFor(button.form, button);
        submitted = { form: button.form, scope };
        if (!validate(scope)) {
            event.preventDefault();
            event.stopImmediatePropagation();
        }
    }, true);
    document.addEventListener('submit', function (event) {
        if (!isProfile(event.target)) return;
        const scope = scopeFor(event.target, event.submitter);
        submitted = { form: event.target, scope };
        if (!validate(scope)) {
            event.preventDefault();
            event.stopImmediatePropagation();
        }
    }, true);
    let invalidFocusPending = false;
    document.addEventListener('invalid', function (event) {
        if (!isProfile(event.target.form)) return;
        event.preventDefault();
        mark(event.target);
        if (!invalidFocusPending) {
            invalidFocusPending = true;
            focus(event.target);
            setTimeout(() => { invalidFocusPending = false; }, 0);
        }
    }, true);
    const clearEdited = event => {
        const target = event.target;
        if (!target.closest?.('.candidate-profile-section')) return;
        if (target.matches('input, select, textarea') && (!target.willValidate || target.validity.valid) && String(target.value || '').trim()) clear(target);
        const editor = target.closest('.ql-editor');
        if (editor?.textContent.trim()) {
            editor.classList.remove('candidate-profile-field-invalid');
            editor.parentElement.parentElement.querySelectorAll('[aria-invalid="true"]').forEach(clear);
        }
    };
    document.addEventListener('input', clearEdited, true);
    document.addEventListener('change', clearEdited, true);
    const serverErrors = (data, context) => {
        if (!context?.scope?.isConnected || !data?.errors) return false;
        let first = null;
        let mapped = false;
        for (const [name, messages] of Object.entries(data.errors)) {
            const bracketName = name.replace(/\.([^.]+)/g, '[$1]');
            const field = Array.from(context.scope.querySelectorAll('[name]')).find(input =>
                input.name === name || input.name === bracketName || input.name === name + '[]');
            if (field) {
                const message = Array.isArray(messages) ? messages[0] : messages;
                mark(field, message);
                suppressValidationToast(message);
                mapped = true;
                if (visible(anchorFor(field))) first ||= field;
            }
        }
        if (mapped) {
            // Laravel's generic validation message must not appear beside inline errors.
            suppressValidationToast(data.message);
            if (first) focus(first);
        }
        return mapped;
    };
    // Associate each request with its submitting form before asynchronous responses arrive.
    const originalFetch = window.fetch;
    window.fetch = function (input, options) {
        const context = submitted;
        const method = String(options?.method || input?.method || 'GET').toUpperCase();
        return originalFetch.apply(this, arguments).then(async response => {
            if (method !== 'GET' && response.status === 422 && context) {
                try {
                    serverErrors(await response.clone().json(), context);
                } catch (error) {
                    // The request owner will display a server/network error if it is not JSON.
                }
            }
            return response;
        });
    };
    if (window.jQuery) {
        jQuery.ajaxPrefilter(function (options) {
            const context = submitted;
            const originalError = options.error;
            options.error = function (xhr) {
                if (xhr.status === 422) serverErrors(xhr.responseJSON, context);
                if (typeof originalError === 'function') return originalError.apply(this, arguments);
            };
        });
        jQuery(document).on('change', '.candidate-profile-section select', function () { clearEdited({ target: this }); });
    }
})();
