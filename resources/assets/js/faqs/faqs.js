document.addEventListener('DOMContentLoaded', loadFaqsData);

function createFaqQuill(selector) {
    if (!$(selector).length) {
        return null;
    }

    return new Quill(selector, {
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                ['clean'],
            ],
            keyboard: {
                bindings: {
                    tab: 'disabled',
                }
            }
        },
        placeholder: Lang.get('js.enter_description'),
        theme: 'snow',
    });
}

function decodeHtml(value) {
    let element = document.createElement('textarea');
    element.innerHTML = value || '';
    return element.value;
}

function setQuillValue(editor, value) {
    if (editor) {
        editor.root.innerHTML = decodeHtml(value);
    }
}

function resetQuillValue(editor) {
    if (editor) {
        editor.setContents([{ insert: '' }]);
    }
}

function getQuillHtml(editor) {
    let input = JSON.stringify(editor.root.innerHTML);
    return input.replace(/"/g, '');
}

function requireQuillContent(editor) {
    return editor && editor.getText().trim().length > 0;
}

function loadFaqsData () {
    if (!$('#addFaqDescriptionEnQuillData').length &&
        !$('#editFaqDescriptionEnQuillData').length) {
        return;
    }

    window.addFaqDescriptionEnQuill = createFaqQuill('#addFaqDescriptionEnQuillData');
    window.addFaqDescriptionBnQuill = createFaqQuill('#addFaqDescriptionBnQuillData');
    window.editFaqDescriptionEnQuill = createFaqQuill('#editFaqDescriptionEnQuillData');
    window.editFaqDescriptionBnQuill = createFaqQuill('#editFaqDescriptionBnQuillData');

    listenClick('.faqs-edit-btn', function (event) {
        let editFaqId = $(event.currentTarget).attr('data-id');
        $.ajax({
            url: route('faqs.edit', editFaqId),
            type: 'GET',
            success: function (result) {
                if (result.success) {
                    $('#faqId').val(result.data.id);
                    $('#editFaqCategoryId').val(result.data.faq_category_id || '');
                    $('#editFaqTitleEn').val(decodeHtml(result.data.title_en || result.data.title));
                    $('#editFaqTitleBn').val(decodeHtml(result.data.title_bn || ''));
                    setQuillValue(editFaqDescriptionEnQuill, result.data.description_en || result.data.description);
                    setQuillValue(editFaqDescriptionBnQuill, result.data.description_bn || '');
                    $('#editFAQsModal').appendTo('body').modal('show');
                }
            },
            error: function (result) {
                displayErrorMessage(result.responseJSON.message);
            },
        });
    })

    listenHiddenBsModal('#addFAQsModal', function () {
        resetModalForm('#addFAQsForm', '#validationErrorsBox');
        resetQuillValue(addFaqDescriptionEnQuill);
        resetQuillValue(addFaqDescriptionBnQuill);
    })
}

listenClick('.addFaqModal', function () {
    $('#addFAQsModal').appendTo('body').modal('show');
})

listenClick('.faq-show-btn', function (event) {
    let showFaqId = $(event.currentTarget).attr('data-id');
    $.ajax({
        url: route('faqs.show', showFaqId),
        type: 'GET',
        success: function (result) {
            if (result.success) {
                $('#showFaqName').html('');
                $('#showFaqDescription').html('');
                $('#showFaqName').append(decodeHtml(result.data.title_en || result.data.title));
                $('#showFaqDescription').append(decodeHtml(result.data.description_en || result.data.description));
                $('#showFaqModal').appendTo('body').modal('show');
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
})

listenClick('.faqs-delete-btn', function (event) {
    let deleteFaqId = $(event.currentTarget).attr('data-id');
    deleteItem(route('faqs.destroy', deleteFaqId), Lang.get('js.faq'));
})

listenHiddenBsModal('#editFAQsModal', function () {
    resetModalForm('#editFAQsForm', '#editValidationErrorsBox');
    resetQuillValue(editFaqDescriptionEnQuill);
    resetQuillValue(editFaqDescriptionBnQuill);
})

listenSubmit('#addFAQsForm', function (e) {
    e.preventDefault();

    if (!requireQuillContent(addFaqDescriptionEnQuill) || !requireQuillContent(addFaqDescriptionBnQuill)) {
        displayErrorMessage(Lang.get('js.description_required'));
        return false;
    }

    $('#faqs_desc_en').val(getQuillHtml(addFaqDescriptionEnQuill));
    $('#faqs_desc_bn').val(getQuillHtml(addFaqDescriptionBnQuill));
    processingBtn('#addFAQsForm', '#addFaqSaveBtn', 'loading');
    $.ajax({
        url: route('faqs.store'),
        type: 'POST',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#addFAQsModal').modal('hide');
                setTimeout(function() {
                    window.location.reload();
                }, 800);
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            processingBtn('#addFAQsForm', '#addFaqSaveBtn');
        },
    });
})

listenSubmit('#editFAQsForm', function (event) {
    event.preventDefault();

    if (!requireQuillContent(editFaqDescriptionEnQuill) || !requireQuillContent(editFaqDescriptionBnQuill)) {
        displayErrorMessage(Lang.get('js.description_required'));
        return false;
    }

    $('#edit_faqs_desc_en').val(getQuillHtml(editFaqDescriptionEnQuill));
    $('#edit_faqs_desc_bn').val(getQuillHtml(editFaqDescriptionBnQuill));
    processingBtn('#editFAQsForm', '#editFaqSaveBtn', 'loading');
    const updateFaqId = $('#faqId').val();
    $.ajax({
        url: route('faqs.update', updateFaqId),
        type: 'put',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#editFAQsModal').modal('hide');
                setTimeout(function() {
                    window.location.reload();
                }, 800);
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            processingBtn('#editFAQsForm', '#editFaqSaveBtn');
        },
    });
});
