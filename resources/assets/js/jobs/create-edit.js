document.addEventListener("DOMContentLoaded", loadEmployeeCreateEditData);
import "flatpickr/dist/l10n";

const jobRequiredFieldMessage = "This field is required";

function loadEmployeeCreateEditData() {
    if (!$(".jobEmployeePanel").length) {
        return;
    }
    if ($("#editDetails").length) {
        window.editJobDescription = new Quill("#editDetails", {
            modules: {
                toolbar: [
                    ["bold", "italic", "underline", "strike"],
                    [{ list: "ordered" }, { list: "bullet" }],
                    ["clean"]
                ],
                keyboard: {
                    bindings: {
                        tab: "disabled"
                    }
                }
            },
            placeholder: Lang.get("js.enter_description"),
            theme: "snow"
        });
        if ($("#editResponse").length) {
            // Initialize Quill editor for key responsibilities
            window.editResponse = new Quill("#editResponse", {
                modules: {
                    toolbar: [
                        ["bold", "italic", "underline", "strike"],
                        [{ list: "ordered" }, { list: "bullet" }],
                        ["clean"]
                    ],
                    keyboard: {
                        bindings: {
                            tab: "disabled"
                        }
                    }
                },
                placeholder: Lang.get(
                    "js.enter_key_responsibilities"
                ),
                theme: "snow"
            });

            setQuillHtml(editJobDescription, "#editJobDescription");
            setQuillHtml(editResponse, "#edit_responsibilities");
            rememberJobEditorHeight("#editDetails", "tap2jobs:admin-job-editor:description-height");
            rememberJobEditorHeight("#editResponse", "tap2jobs:admin-job-editor:responsibilities-height");
        }
    }

    if ($("#details").length) {
        window.details = new Quill("#details", {
            modules: {
                toolbar: [
                    ["bold", "italic", "underline", "strike"],
                    [{ list: "ordered" }, { list: "bullet" }],
                    ["clean"]
                ],
                keyboard: {
                    bindings: {
                        tab: "disabled"
                    }
                }
            },
            placeholder: Lang.get("js.enter_description"),
            theme: "snow"
        });
        if ($("#response").length) {
            window.response = new Quill("#response", {
                modules: {
                    toolbar: [
                        ["bold", "italic", "underline", "strike"],
                        [{ list: "ordered" }, { list: "bullet" }],
                        ["clean"]
                    ],
                    keyboard: {
                        bindings: {
                            tab: "disabled"
                        }
                    }
                },
                placeholder: Lang.get(
                    "js.enter_key_responsibilities"
                ),
                theme: "snow"
            });

            setQuillHtml(details, "#job_desc");
            setQuillHtml(response, "#key_responsibilities");
            rememberJobEditorHeight("#details", "tap2jobs:admin-job-editor:description-height");
            rememberJobEditorHeight("#response", "tap2jobs:admin-job-editor:responsibilities-height");
        }
    }

    if (!$("#createJobForm").length && !$("#editJobForm").length) {
        return;
    }
    initJobRequiredFieldValidation();
    if ($("#toSalary").length) {
        new AutoNumeric("#toSalary", {
            maximumValue: 9999999999,
            currencySymbol: "",
            digitGroupSeparator: ",",
            decimalPlaces: 1,
            currencySymbolPlacement:
                AutoNumeric.options.currencySymbolPlacement.suffix
        });
    }
    if ($("#fromSalary").length) {
        new AutoNumeric("#fromSalary", {
            maximumValue: 9999999999,
            currencySymbol: "",
            digitGroupSeparator: ",",
            decimalPlaces: 1,
            currencySymbolPlacement:
                AutoNumeric.options.currencySymbolPlacement.suffix
        });
    }
    $("#toSalary").on("keyup", function() {
        if ($("#salary").is(":checked")) {
            return;
        }
        let fromSalary = parseInt(
            Math.trunc(removeCommas($("#fromSalary").val()))
        );
        let toSalary = parseInt(Math.trunc(removeCommas($("#toSalary").val())));
        if (toSalary < fromSalary) {
            $("#toSalary").focus();
            $("#salaryToErrorMsg").text(
                Lang.get(
                    "js.please_enter_salary_range_to_greater_than_salary_range_from"
                )
            );
            $(".actions [href='#next']").css({
                opacity: "0.7",
                "pointer-events": "none"
            });
            $("#saveJob").attr("disabled", true);
        } else {
            $("#salaryToErrorMsg").text("");
            $(".actions [href='#next']").css({
                opacity: "1",
                "pointer-events": "inherit"
            });
            $("#saveJob").attr("disabled", false);
        }
    });

    $("#toSalary").on("wheel", function(e) {
        $(this).trigger("keyup");
    });

    $("#fromSalary").on("keyup", function() {
        if ($("#salary").is(":checked")) {
            return;
        }
        let fromSalary = parseInt(
            Math.trunc(removeCommas($("#fromSalary").val()))
        );
        let toSalary = parseInt(Math.trunc(removeCommas($("#toSalary").val())));
        if (toSalary < fromSalary) {
            $("#fromSalary").focus();
            $("#salaryToErrorMsg").text(
                Lang.get(
                    "js.please_enter_salary_range_to_greater_than_salary_range_from"
                )
            );
            $(".actions [href='#next']").css({
                opacity: "0.7",
                "pointer-events": "none"
            });
            $("#saveJob").attr("disabled", true);
        } else {
            $("#salaryToErrorMsg").text("");
            $(".actions [href='#next']").css({
                opacity: "1",
                "pointer-events": "inherit"
            });
            $("#saveJob").attr("disabled", false);
        }
    });

    $("#fromSalary").on("wheel", function(e) {
        $(this).trigger("keyup");
    });

    function toggleSalaryFields() {
        const isHideSalary = $("#salary").is(":checked");
        if (isHideSalary) {
            $("#fromSalary, #toSalary")
                .prop("disabled", true)
                .prop("required", false)
                .removeAttr("required");
            $("#salaryToErrorMsg").text("");
            $("#saveJob").attr("disabled", false);
        } else {
            $("#fromSalary, #toSalary")
                .prop("disabled", false)
                .prop("required", true)
                .attr("required", "required");
        }
    }

    if ($("#salary").length) {
        toggleSalaryFields();
        $(document).on("change", "#salary", function() {
            toggleSalaryFields();
        });
    }

    function initializeJobSelect2(selector, options) {
        $(selector).each(function() {
            const select = $(this);
            const selectedValue = select.val();

            if (select.hasClass("select2-hidden-accessible")) {
                select.select2("destroy");
            }

            select.select2(options);

            if (selectedValue !== null && selectedValue !== "") {
                select.val(selectedValue).trigger("change.select2");
            }
        });
    }

    const countrySelect = $("#countryId");
    if (countrySelect.length && !countrySelect.val()) {
        countrySelect.val(countrySelect.find("option").first().val());
    }

    initializeJobSelect2(
        "#jobTypeId,#jobCategoryId,#careerLevelsId,#jobShiftId,#countryId,#stateId,#cityId,#thanaId,#salaryPeriodsId,#requiredDegreeLevelId",
        { width: "calc(100% - 44px)" }
    );

    $("#functionalAreaId").select2({
        width: !$(".jobEmployeePanel").val() ? "calc(100% - 44px)" : "100%",
        placeholder: $("#functionalAreaId option:first").text(),
        tags: true,
        createTag: function(params) {
            const term = $.trim(params.term);

            if (!term) {
                return null;
            }

            const alreadyExists = $("#functionalAreaId option").toArray().some(function(option) {
                return $.trim(option.text).toLowerCase() === term.toLowerCase();
            });

            if (alreadyExists) {
                return null;
            }

            return {
                id: term,
                text: term,
                newTag: true
            };
        },
        insertTag: function(data, tag) {
            data.unshift(tag);
        },
        templateResult: function(data) {
            if (data.newTag) {
                return 'Add "' + data.text + '"';
            }

            return data.text;
        }
    });

    $("#preferenceId,#currencyId,#createCityStateID").select2({
        width: "100%"
    });

    $("#jobCountryID").select2({
        width: "100%",
        dropdownParent: $("#createStateModal")
    });

    $("#createCityStateID").select2({
        width: "100%",
        dropdownParent: $("#createStateModal")
    });

    $("#createCityStateID").select2({
        width: "100%",
        dropdownParent: $("#createCityModal")
    });

    const $skillSelect = $("#SkillId");

    $skillSelect.select2({
        width: "100%",
        placeholder: $skillSelect.data("placeholder") || Lang.get("js.select_job_skill"),
        closeOnSelect: false,
        tags: true,
        createTag: function(params) {
            const term = $.trim(params.term);

            if (!term) {
                return null;
            }

            const alreadyExists = $skillSelect.find("option").toArray().some(function(option) {
                return $.trim(option.text).toLowerCase() === term.toLowerCase();
            });

            if (alreadyExists) {
                return null;
            }

            return {
                id: term,
                text: term,
                newTag: true
            };
        },
        insertTag: function(data, tag) {
            data.unshift(tag);
        },
        templateResult: function(data) {
            if (data.newTag) {
                return 'Add "' + data.text + '"';
            }

            return data.text;
        }
    });

    const $tagSelect = $("#tagId");

    $tagSelect.select2({
        width: "100%",
        placeholder: $tagSelect.data("placeholder") || Lang.get("js.select_job_tag"),
        closeOnSelect: false,
        tags: true,
        createTag: function(params) {
            const term = $.trim(params.term);

            if (!term) {
                return null;
            }

            const alreadyExists = $tagSelect.find("option").toArray().some(function(option) {
                return $.trim(option.text).toLowerCase() === term.toLowerCase();
            });

            if (alreadyExists) {
                return null;
            }

            return {
                id: term,
                text: term,
                newTag: true
            };
        },
        insertTag: function(data, tag) {
            data.unshift(tag);
        },
        templateResult: function(data) {
            if (data.newTag) {
                return 'Add "' + data.text + '"';
            }

            return data.text;
        }
    });
    if (
        !$("#companyId").hasClass(".select2-hidden-accessible") &&
        $("#companyId").is("select")
    ) {
        $("#companyId").select2({
            width: "100%"
        });
    }
    var date = new Date();
    date.setDate(date.getDate() + 1);
    $(".expiryDatepicker").flatpickr({
        format: "YYYY-MM-DD",
        useCurrent: false,
        locale: getLoggedInUserLang,
        minDate: "today"
    });
    window.autoNumeric = function(formId, validationBox) {
        $(formId)[0].reset();
        $("select.select2Selector").each(function(index, element) {
            let drpSelector = "#" + $(this).attr("id");
            $(drpSelector).val("");
            $(drpSelector).trigger("change");
        });
        $(validationBox).hide();
    };

    //degree level
    listenClick(".createRequiredDegreeLevelTypeModal", function() {
        $("#createDegreeLevelModal")
            .appendTo("body")
            .modal("show");
    });

    $("#createDegreeLevelModal").on("hidden.bs.modal", function() {
        resetModalForm(
            "#createDegreeLevelForm",
            "#degreeLevelValidationErrorsBox"
        );
    });

    //country
    listenClick(".createCountryModal", function() {
        $("#createCountryModal")
            .appendTo("body")
            .modal("show");
    });
    $("#createCountryModal").on("hidden.bs.modal", function() {
        resetModalForm("#createCountryForm", "#countryValidationErrorsBox");
    });

    // state
    listenClick(".createStateModal", function() {
        let country = $("#countryId").val();
        $("#jobCountryID")
            .val(country)
            .trigger("change");
        $("#createStateModal")
            .appendTo("body")
            .modal("show");
    });

    $("#createStateModal").on("hidden.bs.modal", function() {
        $("#jobCountryID")
            .val("")
            .trigger("change");
        resetModalForm("#createStateForm", "#StateValidationErrorsBox");
    });

    //city
    listenClick(".createCityModal", function() {
        let state = $("#stateId").val();
        $("#createCityStateID")
            .val(state)
            .trigger("change");
        $("#createCityModal")
            .appendTo("body")
            .modal("show");
    });

    $("#createCityModal").on("hidden.bs.modal", function() {
        $("#createCityStateID")
            .val("")
            .trigger("change");
        resetModalForm("#createCityForm", "#cityValidationErrorsBox");
    });

    //functional area
    listenClick(".createFunctionalAreaModal", function() {
        $("#createFunctionalModal")
            .appendTo("body")
            .modal("show");
    });

    $("#createFunctionalModal").on("hidden.bs.modal", function() {
        resetModalForm(
            "#createFunctionalForm",
            "#functionalValidationErrorsBox"
        );
    });

    //career level
    listenClick(".createCareerLevelModal", function() {
        $("#createCareerModal")
            .appendTo("body")
            .modal("show");
    });

    $("#createCareerModal").on("hidden.bs.modal", function() {
        resetModalForm("#createCareerForm", "#careerValidationErrorsBox");
    });
    // $('#details').summernote({
    //     minHeight: 200,
    //     height: 200,
    //     placeholder: 'Enter Job Details...',
    //     toolbar: [
    //         ['style', ['bold', 'italic', 'underline', 'clear']],
    //         ['font', ['strikethrough']],
    //         ['para', ['paragraph']]],
    // });

    // $('#jobCategoryDescription, #skillDescription, #salaryPeriodDescription, #jobShiftDescription, #jobTagDescription').summernote({
    //     minHeight: 200,
    //     height: 200,
    //     toolbar: [
    //         ['style', ['bold', 'italic', 'underline', 'clear']],
    //         ['font', ['strikethrough']],
    //         ['para', ['paragraph']]],
    // });

    // $('#editDetails').summernote({
    //     minHeight: 200,
    //     height: 200,
    //     placeholder: 'Enter Job Details...',
    //     toolbar: [
    //         ['style', ['bold', 'italic', 'underline', 'clear']],
    //         ['font', ['strikethrough']],
    //         ['para', ['paragraph']]],
    // });

    // $('#countryId').on('change', function () {
    //     $.ajax({
    //         url: route('states-list'),
    //         type: 'get',
    //         dataType: 'json',
    //         data: { postal: $(this).val() },
    //         success: function (data) {
    //             $('#cityId').empty();
    //             $('#cityId').append(
    //                 $('<option value=""></option>').text('Select City'));
    //             $('#stateId').empty();
    //             $('#stateId').
    //                 append(
    //                     $('<option value=""></option>').text('Select State'));
    //             $.each(data.data, function (i, v) {
    //                 $('#stateId').
    //                     append($('<option></option>').attr('value', i).text(v));
    //             });
    //         },
    //     });
    // });

    // $('#stateId').on('change', function () {
    //     $.ajax({
    //         url: route('cities-list'),
    //         type: 'get',
    //         dataType: 'json',
    //         data: {
    //             state: $(this).val(),
    //             country: $('#countryId').val(),
    //         },
    //         success: function (data) {
    //             $('#cityId').empty();
    //             $('#cityId').append(
    //                 $('<option value=""></option>').text('Select City'));
    //             $.each(data.data, function (i, v) {
    //                 $('#cityId').append(
    //                     $('<option ></option>').attr('value', i).text(v));
    //             });
    //         },
    //     });
    // });
    if ($("#addJobTypeDescriptionQuillData").length) {
        window.jobTypeDescription = new Quill(
            "#addJobTypeDescriptionQuillData",
            {
                modules: {
                    toolbar: [
                        ["bold", "italic", "underline", "strike"],
                        ["clean"]
                    ]
                },
                theme: "snow"
            }
        );

        $("#createJobTypeModal").on("hidden.bs.modal", function() {
            resetModalForm("#createJobTypeForm", "#jobTypeValidationErrorsBox");
            jobTypeDescription.setContents([{ insert: "" }]);
        });
    }

    if ($("#addJobCategoryDescriptionQuillData").length) {
        window.jobCategoryDescription = new Quill(
            "#addJobCategoryDescriptionQuillData",
            {
                modules: {
                    toolbar: [
                        ["bold", "italic", "underline", "strike"],
                        ["clean"]
                    ],
                    keyboard: {
                        bindings: {
                            tab: "disabled"
                        }
                    }
                },
                theme: "snow"
            }
        );

        $("#createJobCategoryModal").on("hidden.bs.modal", function() {
            resetModalForm(
                "#createJobCategoryForm",
                "#jobCategoryValidationErrorsBox"
            );
            jobCategoryDescription.setContents([{ insert: "" }]);
            let defaultDocumentImageUrl = $("#defaultDocumentImageUrl").val();
            $("#previewImage").css(
                "background-image",
                'url("' + defaultDocumentImageUrl + '")'
            );
        });
    }
    if ($("#addSkillDescriptionQuillData").length) {
        $("#createSkillModal").on("hidden.bs.modal", function() {
            resetModalForm("#createSkillForm", "#skillValidationErrorsBox");
            skillDescription.setContents([{ insert: "" }]);
            // $('#skillDescription').summernote('code', '');
        });

        window.skillDescription = new Quill("#addSkillDescriptionQuillData", {
            modules: {
                toolbar: [["bold", "italic", "underline", "strike"], ["clean"]],
                keyboard: {
                    bindings: {
                        tab: "disabled"
                    }
                }
            },
            theme: "snow"
        });
        $("#createSkillForm").on("submit", function(e) {
            let editor_content1 = skillDescription.root.innerHTML;
            let input = JSON.stringify(editor_content1);
            $("#skill_desc").val(
                skillDescription.getText().trim().length === 0
                    ? ""
                    : input.replace(/"/g, "")
            );
            // if (!checkSummerNoteEmpty('#details',
            //     'Job Description field is required.', 1)) {
            //     e.preventDefault();
            //     $('#saveJob,#draftJob').attr('disabled', false);
            //     return false;
            // }

            if ($("#salaryToErrorMsg").text() !== "") {
                $("#toSalary").focus();
                $("#skillBtnSave").attr("disabled", false);
                return false;
            }
        });
    }

    if ($("#addJobTagDescriptionQuillData").length) {
        window.jobTagDescription = new Quill("#addJobTagDescriptionQuillData", {
            modules: {
                toolbar: [["bold", "italic", "underline", "strike"], ["clean"]],
                keyboard: {
                    bindings: {
                        tab: "disabled"
                    }
                }
            },
            theme: "snow"
        });

        $("#createJobTagModal").on("hidden.bs.modal", function() {
            resetModalForm("#createJobTagForm", "#jobTagValidationErrorsBox");
            // $('#jobTagDescription').summernote('code', '');
            jobTagDescription.setContents([{ insert: "" }]);
        });
    }

    $("#createJobShiftModal").on("hidden.bs.modal", function() {
        resetModalForm("#createJobShiftForm", "#jobShiftValidationErrorsBox");
        // $('#jobShiftDescription').summernote('code', '');
        jobShiftDescription.setContents([{ insert: "" }]);
    });

    if ($("#addJobShiftDescriptionQuillData").length) {
        window.jobShiftDescription = new Quill(
            "#addJobShiftDescriptionQuillData",
            {
                modules: {
                    toolbar: [
                        ["bold", "italic", "underline", "strike"],
                        ["clean"]
                    ],
                    keyboard: {
                        bindings: {
                            tab: "disabled"
                        }
                    }
                },
                theme: "snow"
            }
        );
    }
    if ($("#createSalaryPeriodModal").length) {
        window.salaryPeriodDescription = new Quill(
            "#addSalaryPeriodDescriptionQuillData",
            {
                modules: {
                    toolbar: [
                        ["bold", "italic", "underline", "strike"],
                        ["clean"]
                    ],
                    keyboard: {
                        bindings: {
                            tab: "disabled"
                        }
                    }
                },
                theme: "snow"
            }
        );

        $("#createSalaryPeriodModal").on("hidden.bs.modal", function() {
            resetModalForm(
                "#createSalaryPeriodForm",
                "#salaryPeriodValidationErrorsBox"
            );
            salaryPeriodDescription.setContents([{ insert: "" }]);
            // $('#salaryPeriodDescription').summernote('code', '');
        });
    }

    //job Type
    listenClick(".createJobTypeModal", function() {
        $("#createJobTypeModal")
            .appendTo("body")
            .modal("show");
    });

    //job category
    listenClick(".createJobCategoryModal", function() {
        $("#createJobCategoryModal")
            .appendTo("body")
            .modal("show");
    });

    //skill
    listenClick(".createSkillModal", function() {
        $("#createSkillModal")
            .appendTo("body")
            .modal("show");
    });
    //salary period
    listenClick(".createSalaryPeriodModal", function() {
        $("#createSalaryPeriodModal")
            .appendTo("body")
            .modal("show");
    });

    //job shift
    listenClick(".createJobShiftModal", function() {
        $("#createJobShiftModal")
            .appendTo("body")
            .modal("show");
    });

    //job tag
    listenClick(".createJobTagModal", function() {
        $("#createJobTagModal")
            .appendTo("body")
            .modal("show");
    });
}

listenSubmit("#createDegreeLevelForm", function() {
    processingBtn("#createDegreeLevelForm", "#degreeLevelBtnSave", "loading");
    $.ajax({
        url: route("requiredDegreeLevel.store"),
        type: "POST",
        data: $(this).serialize(),
        success: function(result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $("#createDegreeLevelModal").modal("hide");
                let data = {
                    id: result.data.id,
                    text: result.data.name
                };
                let newOption = new Option(data.text, data.id, false, true);
                $("#requiredDegreeLevelId")
                    .append(newOption)
                    .trigger("change");
            }
        },
        error: function(result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function() {
            processingBtn("#createDegreeLevelForm", "#degreeLevelBtnSave");
        }
    });
    return false;
});

listenSubmit("#createCountryForm", function() {
    processingBtn("#createCountryForm", "#countryBtnSave", "loading");
    $.ajax({
        url: route("countries.store"),
        type: "POST",
        data: $(this).serialize(),
        success: function(result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $("#createCountryModal").modal("hide");
                let data = {
                    id: result.data.id,
                    text: result.data.name
                };
                let newOption = new Option(data.text, data.id, false, true);
                $("#countryId")
                    .append(newOption)
                    .trigger("change");
                let newCountry = new Option(data.text, data.id, false, true);
                $("#jobCountryID")
                    .append(newCountry)
                    .trigger("change");
            }
        },
        error: function(result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function() {
            processingBtn("#createCountryForm", "#countryBtnSave");
        }
    });
    return false;
});

listenSubmit("#createStateForm", function() {
    processingBtn("#createStateForm", "#stateBtnSave", "loading");
    $.ajax({
        url: route("states.store"),
        type: "POST",
        data: $(this).serialize(),
        success: function(result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $("#createStateModal").modal("hide");
                let data = {
                    id: result.data.id,
                    text: result.data.name
                };
                let newOption = new Option(data.text, data.id, false, true);
                $("#stateId")
                    .append(newOption)
                    .trigger("change");
                let newState = new Option(data.text, data.id, false, true);
                $("#createCityStateID")
                    .append(newState)
                    .trigger("change");
            }
        },
        error: function(result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function() {
            processingBtn("#createStateForm", "#stateBtnSave");
        }
    });
    return false;
});

listenSubmit("#createCityForm", function() {
    processingBtn("#createCityForm", "#cityBtnSave", "loading");
    $.ajax({
        url: route("cities.store"),
        type: "POST",
        data: $(this).serialize(),
        success: function(result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $("#createCityModal").modal("hide");
                let data = {
                    id: result.data.id,
                    text: result.data.name
                };
                let newOption = new Option(data.text, data.id, false, true);
                $("#cityId")
                    .append(newOption)
                    .trigger("change");
            }
        },
        error: function(result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function() {
            processingBtn("#createCityForm", "#cityBtnSave");
        }
    });
    return false;
});

listenSubmit("#createFunctionalForm", function() {
    processingBtn("#createFunctionalForm", "#functionalBtnSave", "loading");
    $.ajax({
        url: route("functionalArea.store"),
        type: "POST",
        data: $(this).serialize(),
        success: function(result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $("#createFunctionalModal").modal("hide");
                let data = {
                    id: result.data.id,
                    text: result.data.name
                };
                let newOption = new Option(data.text, data.id, false, true);
                $("#functionalAreaId")
                    .append(newOption)
                    .trigger("change");
            }
        },
        error: function(result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function() {
            processingBtn("#createFunctionalForm", "#functionalBtnSave");
        }
    });
    return false;
});

listenSubmit("#createCareerForm", function() {
    processingBtn("#createCareerForm", "#careerBtnSave", "loading");
    $.ajax({
        url: route("careerLevel.store"),
        type: "POST",
        data: $(this).serialize(),
        success: function(result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $("#createCareerModal").modal("hide");
                let data = {
                    id: result.data.id,
                    text: result.data.level_name
                };
                let newOption = new Option(data.text, data.id, false, true);
                $("#careerLevelsId")
                    .append(newOption)
                    .trigger("change");
            }
        },
        error: function(result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function() {
            processingBtn("#createCareerForm", "#careerBtnSave");
        }
    });
    return false;
});

listenSubmit("#createJobTypeForm", function() {
    let editor_content = jobTypeDescription.root.innerHTML;
    if (editor_content.length) {
        if (jobTypeDescription.getText().trim().length === 0) {
            displayErrorMessage(
                Lang.get("js.description_required")
            );
            return false;
        }
    } else {
        displayErrorMessage(Lang.get("js.description_required"));
        return false;
    }
    processingBtn("#createJobTypeForm", "#jobTypeBtnSave", "loading");
    let input = JSON.stringify(editor_content);
    $("#job_type_desc").val(input.replace(/"/g, ""));
    $.ajax({
        url: route("jobType.store"),
        type: "POST",
        data: $(this).serialize(),
        success: function(result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $("#createJobTypeModal").modal("hide");
                let data = {
                    id: result.data.id,
                    text: result.data.name
                };
                let newOption = new Option(data.text, data.id, false, true);
                $("#jobTypeId")
                    .append(newOption)
                    .trigger("change");
            }
        },
        error: function(result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function() {
            processingBtn("#createJobTypeForm", "#jobTypeBtnSave");
        }
    });
    return false;
});

listenSubmit("#createJobCategoryForm", function() {
    processingBtn("#createJobCategoryForm", "#jobCategoryBtnSave", "loading");
    // if (!checkSummerNoteEmpty('#jobCategoryDescription',
    //     'Description field is required.')) {
    //     processingBtn('#addJobCategoryForm', '#jobCategoryBtnSave');
    //     return true;
    // }
    var editor_content = jobCategoryDescription.root.innerHTML;
    if (jobCategoryDescription.getText().trim().length === 0) {
        displayErrorMessage(Lang.get("js.description_required"));
        processingBtn("#createJobCategoryForm", "#jobCategoryBtnSave");
        return false;
    }
    let input = JSON.stringify(editor_content);
    $("#jobCategoryDescriptionValue").val(input.replace(/"/g, ""));

    $.ajax({
        url: route("job-categories.store"),
        type: "POST",
        data: new FormData($(this)[0]),
        dataType: "JSON",
        processData: false,
        contentType: false,
        success: function(result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $("#createJobCategoryModal").modal("hide");
                let data = {
                    id: result.data.id,
                    text: result.data.name
                };
                let newOption = new Option(data.text, data.id, false, true);
                $("#jobCategoryId")
                    .append(newOption)
                    .trigger("change");
            }
        },
        error: function(result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function() {
            processingBtn("#createJobCategoryForm", "#jobCategoryBtnSave");
        }
    });
    return false;
});

listenSubmit("#createSkillForm", function() {
    let editor_content = skillDescription.root.innerHTML;
    let input = JSON.stringify(editor_content);
    $("#skill_desc").val(
        skillDescription.getText().trim().length === 0
            ? ""
            : input.replace(/"/g, "")
    );
    // if (!checkSummerNoteEmpty('#skillDescription',
    //     'Description field is required.')) {
    //     return true;
    // }
    processingBtn("#createSkillForm", "#skillBtnSave", "loading");
    $.ajax({
        url: route("skills.store"),
        type: "POST",
        data: $(this).serialize(),
        success: function(result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $("#createSkillModal").modal("hide");
                let data = {
                    id: result.data.id,
                    text: result.data.name
                };
                let newOption = new Option(data.text, data.id, false, true);
                $("#SkillId")
                    .append(newOption)
                    .trigger("change");
            }
        },
        error: function(result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function() {
            processingBtn("#createSkillForm", "#skillBtnSave");
        }
    });
    return false;
});

listenSubmit("#createJobTagForm", function() {
    let editor_content = jobTagDescription.root.innerHTML;
    if (editor_content.length) {
        if (jobTagDescription.getText().trim().length === 0) {
            displayErrorMessage(
                Lang.get("js.description_required")
            );
            return false;
        }
    } else {
        displayErrorMessage(Lang.get("js.description_required"));
        return false;
    }
    processingBtn("#createJobTagForm", "#jobTagBtnSave", "loading");
    let input = JSON.stringify(editor_content);
    $("#job_tag_desc").val(input.replace(/"/g, ""));
    $.ajax({
        url: route("jobTag.store"),
        type: "POST",
        data: $(this).serialize(),
        success: function(result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $("#createJobTagModal").modal("hide");
                let data = {
                    id: result.data.id,
                    text: result.data.name
                };
                let newOption = new Option(data.text, data.id, false, true);
                $("#tagId")
                    .append(newOption)
                    .trigger("change");
            }
        },
        error: function(result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function() {
            processingBtn("#createJobTagForm", "#jobTagBtnSave");
        }
    });
    return false;
});

listenSubmit("#createJobShiftForm", function() {
    // if (!checkSummerNoteEmpty('#jobShiftDescription',
    //     'Description field is required.', 1)) {
    //     processingBtn('#addJobShiftForm', '#jobShiftBtnSave');
    //     return true;
    // }
    let editor_content = jobShiftDescription.root.innerHTML;
    if (editor_content.length) {
        if (jobShiftDescription.getText().trim().length === 0) {
            displayErrorMessage(
                Lang.get("js.description_required")
            );
            return false;
        }
    } else {
        displayErrorMessage(Lang.get("js.description_required"));
        return false;
    }
    let input = JSON.stringify(editor_content);
    $("#job_shift_desc").val(input.replace(/"/g, ""));
    processingBtn("#createJobShiftForm", "#jobShiftBtnSave", "loading");
    $.ajax({
        url: route("jobShift.store"),
        type: "post",
        data: $(this).serialize(),
        success: function(result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $("#createJobShiftModal").modal("hide");
                let data = {
                    id: result.data.id,
                    text: result.data.shift
                };
                let newOption = new Option(data.text, data.id, false, true);
                $("#jobShiftId")
                    .append(newOption)
                    .trigger("change");
            }
        },
        error: function(result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function() {
            processingBtn("#createJobShiftForm", "#jobShiftBtnSave");
        }
    });
    return false;
});

listenSubmit("#createSalaryPeriodForm", function() {
    // if (!checkSummerNoteEmpty('#salaryPeriodDescription',
    //     'Description field is required.', 1)) {
    //     return true;
    // }
    let editor_content = salaryPeriodDescription.root.innerHTML;
    if (salaryPeriodDescription.getText().trim().length === 0) {
        displayErrorMessage(Lang.get("js.description_required"));
        return false;
    }
    let input = JSON.stringify(editor_content);
    $("#salary_period_desc").val(input.replace(/"/g, ""));
    processingBtn("#createSalaryPeriodForm", "#salaryPeriodBtnSave", "loading");
    $.ajax({
        url: route("salaryPeriod.store"),
        type: "POST",
        data: $(this).serialize(),
        success: function(result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $("#createSalaryPeriodModal").modal("hide");
                let data = {
                    id: result.data.id,
                    text: result.data.period
                };
                let newOption = new Option(data.text, data.id, false, true);
                $("#salaryPeriodsId")
                    .append(newOption)
                    .trigger("change");
            }
        },
        error: function(result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function() {
            processingBtn("#createSalaryPeriodForm", "#salaryPeriodBtnSave");
        }
    });
    return false;
});


function prepareJobFormForSubmission(formSelector, focusInvalid = true) {
    const form = $(formSelector)[0];

    if (!form) {
        return false;
    }

    clearJobValidationState(form);

    if ($(form).find("#salary").is(":checked")) {
        $(form).find("#fromSalary, #toSalary")
            .prop("disabled", true)
            .prop("required", false)
            .removeAttr("required");
    } else if ($(form).find("#salary").length) {
        $(form).find("#fromSalary, #toSalary")
            .prop("disabled", false)
            .prop("required", true)
            .attr("required", "required");
    }

    ["#fromSalary", "#toSalary"].forEach(function(selector) {
        const field = $(form).find(selector)[0];

        if (field && field.value !== "") {
            field.value = removeCommas(field.value).trim();
        }
    });

    $(form).find("#job_desc, #key_responsibilities, #editJobDescription, #edit_responsibilities")
        .prop("required", false)
        .removeAttr("required");

    $(form).find("select.select2-hidden-accessible").each(function() {
        const select = $(this);
        const selectedIds = (select.select2("data") || [])
            .map(function(item) {
                return item.id;
            })
            .filter(function(id) {
                return id !== null && id !== undefined && id !== "";
            });

        if ((!select.val() || select.val().length === 0) && selectedIds.length) {
            select.val(select.prop("multiple") ? selectedIds : selectedIds[0]);
        }
    });

    const countrySelect = $(form).find("#countryId");
    if (countrySelect.length && !countrySelect.val()) {
        countrySelect.val(countrySelect.find("option").first().val()).trigger("change.select2");
    }

    if (!form.checkValidity()) {
        if (focusInvalid) {
            focusInvalidJobField(form);
        }

        return false;
    }

    return true;
}

function initJobRequiredFieldValidation() {
    $("#createJobForm, #editJobForm")
        .off("input.jobRequired change.jobRequired", "input, select, textarea")
        .on("input.jobRequired change.jobRequired", "input, select, textarea", function() {
            clearJobFieldValidationState($(this));
        });

    [window.details, window.response, window.editJobDescription, window.editResponse].forEach(function(editor) {
        if (!editor || editor.__jobValidationBound) {
            return;
        }

        editor.__jobValidationBound = true;
        editor.on("text-change", function() {
            if (editor.getText().trim().length > 0) {
                clearJobEditorValidationState(editor);
            }
        });
    });
}

function clearJobValidationState(form) {
    $(form).find(".is-invalid").removeClass("is-invalid");
    $(form).find(".job-required-feedback").remove();
    $(form).find("select.select2-hidden-accessible").each(function() {
        $(this).next(".select2-container").removeClass("job-field-invalid");
    });

    [window.details, window.response, window.editJobDescription, window.editResponse].forEach(function(editor) {
        clearJobEditorValidationState(editor);
    });
}

function clearJobFieldValidationState($field) {
    $field.removeClass("is-invalid");

    if ($field.hasClass("select2-hidden-accessible")) {
        $field.next(".select2-container").removeClass("job-field-invalid");
    }

    getJobValidationAnchor($field).next(".job-required-feedback").remove();
}

function focusInvalidJobField(form) {
    const invalidField = getFirstInvalidJobField(form);

    if (!invalidField.length) {
        return;
    }

    invalidField.addClass("is-invalid");

    if (invalidField.hasClass("select2-hidden-accessible")) {
        const select2Container = invalidField.next(".select2-container");
        select2Container.addClass("job-field-invalid");
        showJobRequiredFeedback(getJobValidationAnchor(invalidField));
        scrollToJobField(select2Container[0]);
        invalidField.select2("open");
        return;
    }

    showJobRequiredFeedback(getJobValidationAnchor(invalidField));
    scrollToJobField(invalidField[0]);
    invalidField.trigger("focus");
}

function getFirstInvalidJobField(form) {
    return $(form).find(":invalid").filter(function() {
        return $(this).is("input:not([type='hidden']), select, textarea");
    }).first();
}

function validateJobFieldsInOrder(formSelector, editors) {
    const form = $(formSelector)[0];

    if (!prepareJobFormForSubmission(formSelector, false)) {
        const invalidField = getFirstInvalidJobField(form);

        for (const editor of editors) {
            const editorShell = getJobEditorShell(editor);

            if (isJobEditorEmpty(editor) && (!invalidField.length || isBeforeJobField(editorShell[0], invalidField[0]))) {
                markJobEditorInvalid(editor);
                return false;
            }

            if (invalidField.length && editorShell.length && isBeforeJobField(invalidField[0], editorShell[0])) {
                focusInvalidJobField(form);
                return false;
            }
        }

        focusInvalidJobField(form);
        return false;
    }

    for (const editor of editors) {
        if (isJobEditorEmpty(editor)) {
            markJobEditorInvalid(editor);
            return false;
        }
    }

    return true;
}

function isJobEditorEmpty(editor) {
    return !editor || editor.getText().trim().length === 0;
}

function getJobEditorShell(editor) {
    if (!editor || !editor.container) {
        return $();
    }

    return $(editor.container).closest(".job-rich-editor-shell");
}

function isBeforeJobField(firstField, secondField) {
    if (!firstField || !secondField) {
        return false;
    }

    return Boolean(firstField.compareDocumentPosition(secondField) & Node.DOCUMENT_POSITION_FOLLOWING);
}

function markJobEditorInvalid(editor) {
    if (!editor || !editor.container) {
        return;
    }

    const shell = $(editor.container).closest(".job-rich-editor-shell");
    shell.addClass("is-invalid");
    showJobRequiredFeedback(shell);
    scrollToJobField(shell[0]);
    editor.focus();
}

function clearJobEditorValidationState(editor) {
    if (!editor || !editor.container) {
        return;
    }

    $(editor.container).closest(".job-rich-editor-shell").removeClass("is-invalid");
    $(editor.container).closest(".job-rich-editor-shell").next(".job-required-feedback").remove();
}

function getJobValidationAnchor($field) {
    const inputGroup = $field.closest(".input-group");

    if (inputGroup.length) {
        return inputGroup;
    }

    if ($field.hasClass("select2-hidden-accessible")) {
        const select2Container = $field.next(".select2-container");

        if (select2Container.length) {
            return select2Container;
        }
    }

    return $field;
}

function showJobRequiredFeedback($anchor) {
    if (!$anchor.length || $anchor.next(".job-required-feedback").length) {
        return;
    }

    $('<div class="invalid-feedback d-block job-required-feedback"></div>')
        .text(jobRequiredFieldMessage)
        .insertAfter($anchor);
}

function scrollToJobField(field) {
    if (!field || !field.scrollIntoView) {
        return;
    }

    field.scrollIntoView({
        behavior: "smooth",
        block: "center"
    });
}

function getQuillHtml(editor) {
    if (!editor || !editor.root) {
        return "";
    }

    if (editor.getText().trim().length === 0) {
        return "";
    }

    let html = editor.root.innerHTML || "";

    const tempDiv = document.createElement("div");
    tempDiv.innerHTML = html;

    tempDiv.querySelectorAll("ol").forEach(function (ol) {
        const hasBullet = ol.querySelector('li[data-list="bullet"]');
        if (hasBullet) {
            const ul = document.createElement("ul");
            Array.from(ol.attributes).forEach(function (attr) {
                ul.setAttribute(attr.name, attr.value);
            });
            ul.innerHTML = ol.innerHTML;
            ol.parentNode.replaceChild(ul, ol);
        }
    });

    return tempDiv.innerHTML;
}

function setQuillHtml(editor, selectorOrHtml) {
    if (!editor) {
        return;
    }

    let rawHtml = "";
    if (typeof selectorOrHtml === "string") {
        if ($(selectorOrHtml).length) {
            rawHtml = $(selectorOrHtml).val() || "";
        } else {
            rawHtml = selectorOrHtml;
        }
    }

    if (!rawHtml || !rawHtml.trim()) {
        if (editor.setContents) {
            editor.setContents([]);
        }
        return;
    }

    const tempDiv = document.createElement("div");
    tempDiv.innerHTML = rawHtml;

    tempDiv.querySelectorAll("ol").forEach(function (ol) {
        const hasBullet = ol.querySelector('li[data-list="bullet"]');
        if (hasBullet) {
            const ul = document.createElement("ul");
            Array.from(ol.attributes).forEach(function (attr) {
                ul.setAttribute(attr.name, attr.value);
            });
            ul.innerHTML = ol.innerHTML;
            ol.parentNode.replaceChild(ul, ol);
        }
    });

    const cleanHtml = tempDiv.innerHTML;

    if (editor.clipboard && typeof editor.clipboard.dangerouslyPasteHTML === "function") {
        editor.clipboard.dangerouslyPasteHTML(0, cleanHtml);
    } else if (editor.root) {
        editor.root.innerHTML = cleanHtml;
    }
}

function rememberJobEditorHeight(selector, storageKey) {
    const editor = document.querySelector(selector);
    const editorShell = editor ? editor.closest(".job-rich-editor-shell") : null;

    if (!editorShell) {
        return;
    }

    const minHeight = 200;
    const maxHeight = 600;
    const savedHeight = Number(localStorage.getItem(storageKey));

    if (savedHeight >= minHeight && savedHeight <= maxHeight) {
        editorShell.style.height = `${savedHeight}px`;
    }

    const resizeHandle = editorShell.querySelector(".job-rich-editor-resize-handle");

    if (resizeHandle && !editorShell.dataset.jobEditorResizeReady) {
        editorShell.dataset.jobEditorResizeReady = "true";

        resizeHandle.addEventListener("pointerdown", event => {
            event.preventDefault();

            const startY = event.clientY;
            const startHeight = editorShell.getBoundingClientRect().height;

            const resizeEditor = moveEvent => {
                const nextHeight = Math.min(
                    maxHeight,
                    Math.max(minHeight, startHeight + moveEvent.clientY - startY)
                );

                editorShell.style.height = `${Math.round(nextHeight)}px`;
            };

            const stopResize = () => {
                localStorage.setItem(
                    storageKey,
                    Math.round(editorShell.getBoundingClientRect().height)
                );
                document.removeEventListener("pointermove", resizeEditor);
                document.removeEventListener("pointerup", stopResize);
                document.body.classList.remove("job-editor-resizing");
            };

            document.body.classList.add("job-editor-resizing");
            document.addEventListener("pointermove", resizeEditor);
            document.addEventListener("pointerup", stopResize);
        });
    }

    if (!window.ResizeObserver) {
        return;
    }

    let lastSavedHeight = savedHeight || Math.round(editorShell.getBoundingClientRect().height);
    const resizeObserver = new ResizeObserver(entries => {
        const currentHeight = Math.round(entries[0].contentRect.height);

        if (
            currentHeight >= minHeight &&
            currentHeight <= maxHeight &&
            Math.abs(currentHeight - lastSavedHeight) > 2
        ) {
            localStorage.setItem(storageKey, currentHeight);
            lastSavedHeight = currentHeight;
        }
    });

    resizeObserver.observe(editorShell);
}

listenClick("#jobsSaveBtn, #saveDraft", function(e) {
    e.preventDefault();
    clearJobValidationState($("#createJobForm")[0]);
    $("#saveAsDraft").val($(this).val() === "draft" ? "1" : "0");

    if (!validateJobFieldsInOrder("#createJobForm", [details, response])) {
        return false;
    }

    let editor_content1 = getQuillHtml(details);
    $("#job_desc").val(editor_content1);
    // if (!checkSummerNoteEmpty('#details',
    //     'Job Description field is required.', 1)) {
    //     e.preventDefault();
    //     $('#saveJob,#draftJob').attr('disabled', false);
    //     return false;
    // }
    let keyResponsibilitiesContent = getQuillHtml(response);
    $("#key_responsibilities").val(keyResponsibilitiesContent);

    if ($("#salaryToErrorMsg").text() !== "") {
        $("#toSalary").focus();
        $("#saveJob,#draftJob").attr("disabled", false);
        return false;
    }

    processingBtn("#createJobForm", $(this), "loading");
    $("#createJobForm")[0].submit();
});

listenClick("#editJobsSaveBtn, #saveDraft", function(e) {
    e.preventDefault();
    clearJobValidationState($("#editJobForm")[0]);

    if (!validateJobFieldsInOrder("#editJobForm", [editJobDescription, editResponse])) {
        return false;
    }

    let editor_content2 = getQuillHtml(editJobDescription);
    $("#editJobDescription").val(editor_content2);
    let editor_content3 = getQuillHtml(editResponse);
    $("#edit_responsibilities").val(editor_content3);

    if ($("#salaryToErrorMsg").text() !== "") {
        $("#toSalary").focus();
        $("#saveJob,#draftJob").attr("disabled", false);
        return false;
    }

    processingBtn("#editJobForm", $(this), "loading");
    $("#editJobForm")[0].submit();
});
