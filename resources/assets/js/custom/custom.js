document.addEventListener("DOMContentLoaded", loadCustom);

let Handlebars = "";
let source = null;
let jsrender = require("jsrender");
let csrfToken = $('meta[name="csrf-token"]').attr("content");

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": csrfToken
    }
});

document.addEventListener("DOMContentLoaded", initAllComponents);

function initAllComponents() {
    select2initialize();
    refreshCsrfToken();
    alertInitialize();
    modalInputFocus();
    inputFocus();
    IOInitImageComponent();
    IOInitSidebar();
    tooltip();
    inputAutoFocus();
}
$(function() {
    $(document).on("shown.bs.modal", ".modal", function() {
        if ($(this).find("input:text")[0]) {
            $(this)
                .find("input:text")[0]
                .focus();
        }
    });
});

const inputAutoFocus = () => {
    $(
        'input:text:not([readonly="readonly"]):not([name="search"]):not(.front-input)'
    )
        .first()
        .focus();
};
function tooltip() {
    var tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

function alertInitialize() {
    $(".alert")
        .delay(5000)
        .slideUp(300);
}

let firstTime = true;

function refreshCsrfToken() {
    csrfToken = $('meta[name="csrf-token"]').attr("content");

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": csrfToken
        }
    });
}

function select2initialize() {
    $('[data-control="select2"]').each(function() {
        $(this).select2();
    });
}

document.addEventListener("livewire:load", function() {
    window.livewire.hook("message.processed", () => {
        $('[data-control="select2"]').each(function() {
            $(this).select2();
        });
    });
});

document.addEventListener("turbo:before-cache", function() {
    let currentSelect2 = ".select2-hidden-accessible";
    let currentQuill = ".ql-container";
    $(currentSelect2).each(function() {
        $(this).select2("destroy");
    });

    $(currentSelect2).each(function() {
        $(this).select2();
    });

    if ($(currentQuill).length) {
        $(currentQuill).each(function() {
            let quill = "#" + $(this).attr("id");
            resetQuill(quill);
        });
    }
    $("#toast-container").empty();
});

window.resetQuill = function(quill) {
    if ($(quill)[0]) {
        var content = $(quill)
            .find(".ql-editor")
            .html();
        $(quill).html(content);

        $(quill)
            .siblings(".ql-toolbar")
            .remove();
        $(quill + " *[class*='ql-']").removeClass(function(index, class_name) {
            return (class_name.match(/(^|\s)ql-\S+/g) || []).join(" ");
        });

        $(quill + "[class*='ql-']").removeClass(function(index, class_name) {
            return (class_name.match(/(^|\s)ql-\S+/g) || []).join(" ");
        });
    }
};

const modalInputFocus = () => {
    $(function() {
        $(".modal").on("shown.bs.modal", function() {
            $(this)
                .find("input:text")
                .first()
                .focus();
        });
    });
};

const inputFocus = () => {
    $('input:text:not([readonly="readonly"]):not([name="search"])')
        .first()
        .focus();
};

function loadCustom() {
    Handlebars = require("handlebars");
    source = null;
    jsrender = require("jsrender");

    // $('input:text:not([readonly="readonly"])')
    //     .first()
    //     .focus();

    // infy loader js
    stopLoader();

    // script to active parent menu if sub menu has currently active
    let hasActiveMenu = $(document)
        .find(".nav-item.dropdown ul li")
        .hasClass("active");
    if (hasActiveMenu) {
        $(document)
            .find(".nav-item.dropdown ul li.active")
            .parent("ul")
            .css("display", "block");
        $(document)
            .find(".nav-item.dropdown ul li.active")
            .parent("ul")
            .parent("li")
            .addClass("active");
        $(".dropdown-toggle").dropdown();
    }

    if ($(window).width() > 992) {
        $(".no-hover").on("click", function() {
            $(this).toggleClass("open");
        });
    }
}

window.startLoader = function() {
    $(".infy-loader").show();
};

window.stopLoader = function() {
    $(".infy-loader").hide();
};

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    }
});

// $(document).on('focus', '.select2-selection.select2-selection--single', function (e) {
//     // $(this).closest('.select2-container').siblings('select:enabled').select2('open');
//     document.querySelector(".select2-container--open .select2-search__field").focus();
// });

// $(document).on('select2:open', () => {
//     document.querySelector('.select2-search__field').focus();
// });
listenWithOutTarget("select2:open", () => {
    let allFound = document.querySelectorAll(
        ".select2-container--open .select2-search__field"
    );
    allFound[allFound.length - 1].focus();
});

listen("focus", ".select2.select2-container", function(e) {
    let isOriginalEvent = e.originalEvent; // don't re-open on closing focus event
    let isSingleSelect = $(this).find(".select2-selection--single").length > 0; // multi-select will pass focus to input

    if (isOriginalEvent && isSingleSelect) {
        $(this)
            .siblings("select:enabled")
            .select2("open");
    }
});

listen("shown.bs.modal", ".modal", function() {
    $(this)
        .find("input:text")
        .first()
        .focus();
});

window.resetModalForm = function(formId, validationBox) {
    $(formId)[0].reset();
    $("select.select2Selector").each(function(index, element) {
        let drpSelector = "#" + $(this).attr("id");
        $(drpSelector).val("");
        $(drpSelector).trigger("change");
    });
    $(validationBox).hide();
};

window.printErrorMessage = function(selector, errorResult) {
    $(selector).show();
    $(selector).html(
        "<i class='fa-solid fa-face-frown me-4'></i>" +
            errorResult.responseJSON.message
    );
    $(selector).removeClass("hide d-none");
};

window.manageAjaxErrors = function(data) {
    var errorDivId =
        arguments.length > 1 && arguments[1] !== undefined
            ? arguments[1]
            : "editValidationErrorsBox";
    if (data.status == 404) {
        toastr.error({
            title: "Error!",
            message: data.responseJSON.message,
            position: "topRight"
        });
    } else {
        printErrorMessage("#" + errorDivId, data);
    }
};

var isRTL = lancode == "ar" ? true : false;
if(isRTL){
    toastr.options = {
        rtl: true,
        positionClass: "toast-top-left",
    };
}else{
    toastr.options = {
        positionClass: "toast-top-right",
    };
}

window.displaySuccessMessage = function(message) {
    let successTitle = Lang.get("js.success");
    if (successTitle === "js.success") {
        successTitle = typeof lancode !== 'undefined' && lancode === 'bn' ? 'সফল' : 'Successful';
    }
    toastr.success(message, successTitle);
};

window.displayErrorMessage = function(message) {
    let errorTitle = Lang.get("js.error");
    if (errorTitle === "js.error") {
        errorTitle = typeof lancode !== 'undefined' && lancode === 'bn' ? 'ত্রুটি' : 'Error';
    }
    toastr.error(message, errorTitle);
};

window.displayDeleteSuccessMessage = function(message) {
    swal({
        icon: "success",
        title: Lang.get("js.deleted") + " !",
        text: message,
        buttons: {
            confirm: Lang.get("js.ok")
        },
        reverseButtons: true,
        confirmButtonColor: "#F62947",
        timer: 2000
    });
};

window.displayDeleteErrorMessage = function(message) {
    swal({
        title: Lang.get("js.error"),
        icon: "error",
        text: message,
        type: "error",
        buttons: {
            confirm: Lang.get("js.ok")
        },
        reverseButtons: true,
        confirmButtonColor: "#F62947",
        timer: 4000
    });
};

window.addEventListener("bulk-action-feedback", function(event) {
    const feedback = event.detail || {};

    if (feedback.type === "success") {
        window.displayDeleteSuccessMessage(feedback.message);
    } else if (feedback.type === "error") {
        window.displayDeleteErrorMessage(feedback.message);
    }
});

window.confirmDeleteAction = function(message, onConfirm) {
    return swal({
        title: Lang.get("js.delete") + " !",
        text: message,
        buttons: {
            confirm: Lang.get("js.yes_delete"),
            cancel: Lang.get("js.no_cancel")
        },
        reverseButtons: true,
        confirmButtonColor: "#F62947",
        cancelButtonColor: "#ADB5BD",
        icon: "warning"
    }).then(function(willDelete) {
        if (willDelete && typeof onConfirm === "function") {
            onConfirm();
        }
    });
};

window.deleteItem = function(url, header) {
    var callFunction =
        arguments.length > 3 && arguments[3] !== undefined
            ? arguments[3]
            : null;
    window.confirmDeleteAction(
        Lang.get("js.are_you_sure") + ' "' + header + '" ?',
        function() {
            deleteItemAjax(url, header, callFunction);
        }
    );
};

function deleteItemAjax(url, header, callFunction = null) {
    $.ajax({
        url: url,
        type: "DELETE",
        dataType: "json",
        success: function(obj) {
            if (obj.success) {
                Livewire.dispatch("refreshDatatable");
                Livewire.dispatch("refresh");
                Livewire.dispatch('resetPage');
            }
            window.displayDeleteSuccessMessage(
                header + " " + Lang.get("js.has_been_deleted")
            );
            if (callFunction) {
                eval(callFunction);
            }
        },
        error: function(data) {
            window.displayDeleteErrorMessage(data.responseJSON.message);
        }
    });
}

window.format = function(dateTime) {
    var format =
        arguments.length > 1 && arguments[1] !== undefined
            ? arguments[1]
            : "DD-MMM-YYYY";
    return moment(dateTime).format(format);
};

window.processingBtn = function(selecter, btnId, state = null) {
    let loadingButton = $(selecter).find(btnId);
    if (state == "loading") {
        loadingButton
            .data("original-text", loadingButton.html())
            .html(loadingButton.data("loading-text"))
            .prop("disabled", true);
    } else {
        loadingButton
            .html(loadingButton.data("original-text"))
            .prop("disabled", false);
    }
};
window.setAdminBtnLoader = function(btnLoader) {
    if (btnLoader.attr("data-loading-text")) {
        btnLoader
            .html(btnLoader.attr("data-loading-text"))
            .prop("disabled", true);
        btnLoader.removeAttr("data-loading-text");
        return;
    }
    btnLoader.attr("data-old-text", btnLoader.text());
    btnLoader.html(btnLoader.attr("data-new-text")).prop("disabled", false);
};

window.prepareTemplateRender = function(templateSelector, data) {
    let template = jsrender.templates(templateSelector);
    return template.render(data);
};

window.isValidFile = function(inputSelector, validationMessageSelector) {
    let ext = $(inputSelector)
        .val()
        .split(".")
        .pop()
        .toLowerCase();
    if ($.inArray(ext, ["gif", "png", "jpg", "jpeg"]) == -1) {
        $(inputSelector).val("");
        $(validationMessageSelector).removeClass("d-none");
        $(validationMessageSelector)
            .html("The image must be a file of type: jpeg, jpg, png.")
            .show();
        $(validationMessageSelector)
            .delay(5000)
            .slideUp(300);

        return false;
    }
    $(validationMessageSelector).hide();
    return true;
};

window.displayPhoto = function(input, selector) {
    let displayPreview = true;
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function(e) {
            let image = new Image();
            image.src = e.target.result;
            image.onload = function() {
                $(selector).attr("src", e.target.result);
                displayPreview = true;
            };
        };
        if (displayPreview) {
            reader.readAsDataURL(input.files[0]);
            $(selector).show();
        }
    }
};
window.removeCommas = function(str) {
    return str.replace(/,/g, "");
};

// window.DatetimepickerDefaults = function (opts) {
//     return $.extend({}, {
//         sideBySide: true,
//         ignoreReadonly: true,
//         icons: {
//             close: 'fa fa-times',
//             time: 'fa fa-clock-o',
//             date: 'fa fa-calendar',
//             up: 'fa fa-arrow-up',
//             down: 'fa fa-arrow-down',
//             previous: 'fa fa-chevron-left',
//             next: 'fa fa-chevron-right',
//             today: 'fa fa-clock-o',
//             clear: 'fa fa-trash-o',
//         },
//     }, opts);
// };

window.isEmpty = value => {
    return value === undefined || value === null || value === "";
};

window.screenLock = function() {
    $("#overlay-screen-lock").show();
    $("body").css({ "pointer-events": "none", opacity: "0.6" });
};

window.screenUnLock = function() {
    $("body").css({ "pointer-events": "auto", opacity: "1" });
    $("#overlay-screen-lock").hide();
};

window.urlValidation = function(value, regex) {
    if (!value) {
        return true;
    }

    let urlCheck = value.match(regex) ? true : false;
    if (!urlCheck) {
        return false;
    }

    return true;
};

function closeFrontLanguageDropdowns (restoreFocus) {
    $(".language-dropdown.is-open").each(function () {
        const dropdown = $(this);
        dropdown.removeClass("is-open");
        dropdown.find(".language-dropdown-btn").attr("aria-expanded", "false");

        if (restoreFocus) {
            dropdown.find(".language-dropdown-btn").trigger("focus");
        }
    });
}

function closeFrontUserDropdowns (restoreFocus) {
    $(".front-user-dropdown.is-open").each(function () {
        const dropdown = $(this);
        dropdown.removeClass("is-open");
        dropdown.find(".front-user-dropdown-menu").removeClass("show");
        dropdown.find(".front-user-dropdown-toggle").attr("aria-expanded", "false");

        if (restoreFocus) {
            dropdown.find(".front-user-dropdown-toggle").trigger("focus");
        }
    });
}

listenClick(".front-user-dropdown-toggle", function(e) {
    e.preventDefault();
    e.stopPropagation();

    const dropdown = $(this).closest(".front-user-dropdown");
    const menu = dropdown.find(".front-user-dropdown-menu");
    const willOpen = !dropdown.hasClass("is-open");

    closeFrontUserDropdowns(false);
    dropdown.toggleClass("is-open", willOpen);
    menu.toggleClass("show", willOpen);
    $(this).attr("aria-expanded", willOpen ? "true" : "false");
});

listenClick(".language-dropdown-btn", function(e) {
    e.preventDefault();
    e.stopPropagation();

    const dropdown = $(this).closest(".language-dropdown");
    const willOpen = !dropdown.hasClass("is-open");
    closeFrontLanguageDropdowns(false);
    dropdown.toggleClass("is-open", willOpen);
    $(this).attr("aria-expanded", willOpen ? "true" : "false");
});

listenClick(".languageSelection", function(e) {
    e.preventDefault();
    e.stopPropagation();

    let languageName = $(this).data("prefix-value");
    let languageUrl = $(this).closest(".language-dropdown").data("language-url");
    closeFrontLanguageDropdowns(false);
    refreshCsrfToken();
    $.ajax({
        type: "POST",
        url: languageUrl,
        data: { languageName: languageName },
        success: function() {
            location.reload();
        },
        error: function(result) {
            if (typeof displayErrorMessage === "function") {
                displayErrorMessage(result.responseJSON && result.responseJSON.message
                    ? result.responseJSON.message
                    : "Unable to change language.");
            }
        }
    });
});

listenWithOutTarget("click", function(e) {
    if (!$(e.target).closest(".language-dropdown").length) {
        closeFrontLanguageDropdowns(false);
    }

    if (!$(e.target).closest(".front-user-dropdown").length) {
        closeFrontUserDropdowns(false);
    }
});

listenWithOutTarget("keydown", function(e) {
    if (e.key === "Escape") {
        closeFrontLanguageDropdowns(true);
        closeFrontUserDropdowns(true);
    }
});

const adminNotificationList = $("#adminNotificationList");

function escapeNotificationText(value) {
    return $("<div>").text(value || "").html();
}

function adminNotificationEmptyState() {
    return (
        '<div class="admin-notification-empty d-flex flex-column align-items-center justify-content-center text-center py-8" data-height="400">' +
        '<i class="fa-regular fa-bell-slash text-gray-500 fs-1 mb-3"></i>' +
        '<p class="fs-6 fw-semibold text-gray-700 mb-0">No notification found</p>' +
        "</div>"
    );
}

function renderAdminNotifications(notificationData) {
    if (!adminNotificationList.length || !notificationData) {
        return;
    }

    let notifications = notificationData.notifications || [];
    let notificationCount = notificationData.count || 0;

    $("#counter")
        .text(notificationCount)
        .toggleClass("d-none", notificationCount === 0);

    if (notifications.length === 0) {
        adminNotificationList.html(adminNotificationEmptyState());

        return;
    }

    let notificationItems = notifications
        .map(function(notification) {
            let isRead = notification.is_read;
            let itemStyle = isRead
                ? "background: transparent; opacity: 0.7;"
                : "background: rgba(101, 113, 255, 0.08);";

            return (
                '<div class="admin-notification-item ' +
                (isRead ? "admin-notification-read" : "admin-notification-unread") +
                ' d-flex position-relative mb-3 p-3 rounded readNotification cursor-pointer" style="' +
                itemStyle +
                '" data-id="' +
                notification.id +
                '" data-url="' +
                escapeNotificationText(notification.url) +
                '" data-read="' +
                (isRead ? "1" : "0") +
                '">' +
                '<span class="me-5 text-primary fs-2 icon-label"><i class="' +
                escapeNotificationText(notification.icon) +
                '"></i></span>' +
                "<div>" +
                '<h5 class="text-gray-900 fs-6 mb-2">' +
                escapeNotificationText(notification.title) +
                "</h5>" +
                '<h6 class="text-gray-600 fs-small fw-light mb-0">' +
                escapeNotificationText(notification.created_at) +
                "</h6>" +
                "</div>" +
                "</div>"
            );
        })
        .join("");

    adminNotificationList.html(notificationItems);
}

function refreshAdminNotifications() {
    if (!adminNotificationList.length || typeof route !== "function") {
        return;
    }

    $.ajax({
        type: "GET",
        url: route("notifications.latest"),
        success: function(response) {
            renderAdminNotifications(response.data);
        }
    });
}

if (adminNotificationList.length) {
    setInterval(refreshAdminNotifications, 30000);
}

listenClick(".readNotification", function(e) {
    e.preventDefault();
    let notificationId = $(this).data("id");
    let notificationUrl = $(this).data("url");
    let notification = $(this);
    let wasUnread = String(notification.data("read")) !== "1";
    $.ajax({
        type: "POST",
        url: route("read-notification", notificationId),
        data: { notificationId: notificationId },
        success: function(response) {
            notificationUrl = notificationUrl || (response.data ? response.data.url : "");
            notification
                .removeClass("admin-notification-unread")
                .css({
                    background: "transparent",
                    opacity: 0.7
                });
            notification.attr("data-read", "1").data("read", "1");
            let notificationCounter = parseInt($("#counter").text(), 10) || 0;
            notificationCounter = wasUnread
                ? Math.max(notificationCounter - 1, 0)
                : notificationCounter;
            $("#counter").text(notificationCounter);
            if (notificationCounter == 0) {
                $(".notification-count").addClass("d-none");
                $("#counter").text(notificationCounter);
            }

            if (notificationUrl) {
                setTimeout(function() {
                    window.location.href = notificationUrl;
                }, 140);
                return;
            }

            displaySuccessMessage(Lang.get("js.notification_read"));
        },
        error: function(error) {
            manageAjaxErrors(error);
        }
    });
});

listenClick("#register", function(e) {
    e.preventDefault();
    $(".open #dropdownLanguage").trigger("click");
    $(".open #dropdownLogin").trigger("click");
});

listenClick("#language", function(e) {
    e.preventDefault();
    $(".open #dropdownRegister").trigger("click");
    $(".open #dropdownLogin").trigger("click");
});

listenClick("#login", function(e) {
    e.preventDefault();
    $(".open #dropdownRegister").trigger("click");
    $(".open #dropdownLanguage").trigger("click");
});

window.checkSummerNoteEmpty = function(
    selectorElement,
    errorMessage,
    isRequired = 0
) {
    if ($(selectorElement).summernote("isEmpty") && isRequired === 1) {
        displayErrorMessage(errorMessage);
        $(document)
            .find(".note-editable")
            .html("<p><br></p>");
        return false;
    } else if (!$(selectorElement).summernote("isEmpty")) {
        $(document)
            .find(".note-editable")
            .contents()
            .each(function() {
                if (this.nodeType === 3) {
                    // text node
                    this.textContent = this.textContent.replace(/\u00A0/g, "");
                }
            });
        if (
            $(document)
                .find(".note-editable")
                .text()
                .trim().length == 0
        ) {
            $(document)
                .find(".note-editable")
                .html("<p><br></p>");
            $(selectorElement).val(null);
            if (isRequired === 1) {
                displayErrorMessage(errorMessage);

                return false;
            }
        }
    } else if (
        $(document)
            .find(".note-editable")
            .html() == "<p><br></p>"
    ) {
        $(selectorElement).summernote("code", "");
    }

    return true;
};

window.preparedTemplate = function() {
    let source = $("#actionTemplate").html();
    window.preparedTemplate = Handlebars.compile(source);
};

window.ajaxCallInProgress = function() {
    ajaxCallIsRunning = true;
};

window.ajaxCallCompleted = function() {
    ajaxCallIsRunning = false;
};

window.avoidSpace = function(event) {
    let k = event ? event.which : window.event.keyCode;
    if (k == 32 && event.path[0].value.length <= 0) {
        return false;
    }
};
window.isOnlyContainWhiteSpace = function(value) {
    return value.trim().replace(/ \r\n\t/g, "") === "";
};

let defaultAvatarImageUrl = "asset('assets/img/user.png')";
window.defaultImagePreview = function(imagePreviewSelector, id = null) {
    if (id == 1) {
        $(imagePreviewSelector).css(
            "background-image",
            'url("' + defaultAvatarImageUrl + '")'
        );
    } else {
        $(imagePreviewSelector).css(
            "background-image",
            'url("' + $("#defaultDocumentImageUrl").val() + '")'
        );
    }
};

window.isEmpty = (value) => {
    return value === undefined || value === null || value === '';
};

window.openDropdownManually = function (dropdownBtnEle, dropdownEle) {
    if (!dropdownBtnEle.hasClass('show')) {
        dropdownBtnEle.addClass('show')
        dropdownEle.addClass('show')
    } else {
        dropdownBtnEle.removeClass('show')
        dropdownEle.removeClass('show')
    }
}

window.hideDropdownManually = function (dropdownBtnEle, dropdownEle) {
    dropdownBtnEle.removeClass('show')
    dropdownEle.removeClass('show')
}
