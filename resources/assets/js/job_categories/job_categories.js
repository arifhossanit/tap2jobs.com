import Tagify from '@yaireo/tagify';

document.addEventListener('DOMContentLoaded', loadJobCategoryData);

let addJobCategorySearchTags;
let editJobCategorySearchTags;

Livewire.hook("element.init", ({ component }) => {
    if (!$('#indexJobCategoryData').length) {
        return;
    }
    $('#jobCategoryFilter').select2();
})

function loadJobCategoryData() {

    if (!$('#indexJobCategoryData').length) {
        return;
    }

    $('#jobCategoryFilter').select2();

    initAddJobCategorySlug();
    initJobCategorySearchTags();

    var defaultDocumentImageUrl = $('#defaultDocumentImageUrl').val();

    if ($('#addJobCategoryDescriptionQuillData').length) {
        window.addJobCategoryDescriptionQuill = new AppTextEditor(
            '#addJobCategoryDescriptionQuillData', {
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

    if($('#editJobCategoryDescriptionQuillData').length) {
        window.editJobCategoryDescriptionQuill = new AppTextEditor(
            '#editJobCategoryDescriptionQuillData', {
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

    listenClick('.addJobCategoryModal', function () {
        $('#addJobCategoryModal').appendTo('body').modal('show');
    })

    listenChange('#job_category_image', function () {
        if (isValidFile($(this), '#validationErrorsBox')) {
            displayPhoto(this, '#previewImage');
        }
    })

    listenChange('#editCustomerImage', function () {
        if (isValidFile($(this), '#editValidationErrorsBox')) {
            displayPhoto(this, '#editPreviewImage');
        }
    })

    listenClick('.job-category-edit-btn', function (event) {
        // if (ajaxCallIsRunning) {
//            return;
//        }
        ajaxCallInProgress();
        let editJobCategoryId = $(event.currentTarget).attr('data-id');
        $.ajax({
            url: route('job-categories.edit', editJobCategoryId),
            type: 'GET',
            success: function (result) {
                if (result.success) {
                    let element = document.createElement('textarea');
                    element.innerHTML = result.data.name;
                    $('#jobCategoryId').val(result.data.id);
                    $('#editName').val(element.value);
                    $('#editJobCategorySlug').val(result.data.slug || '');
                    $('#editJobCategorySeoTitle').val(result.data.seo_title || '');
                    $('#editJobCategoryMetaDescription').val(result.data.meta_description || '');
                    setJobCategorySearchTags(editJobCategorySearchTags, result.data.search_tags || []);
                    element.innerHTML = result.data.description;
                    editJobCategoryDescriptionQuill.root.innerHTML = element.value;
                    (result.data.is_featured == 1) ? $('#editIsFeatured').
                        prop('checked', true) : $('#editIsFeatured').
                        prop('checked', false);
                    if (isEmpty(result.data.image_url)) {
                        $('#editPreviewImage').
                            css('background-image',
                                'url("' + defaultDocumentImageUrl + '")');
                    } else {
                        $('#editPreviewImage').
                            css('background-image',
                                'url("' + result.data.image_url + '")');
                    }
                    $('#jobCategoryEditModal').appendTo('body').modal('show');
                    ajaxCallCompleted();
                }
            },
            error: function (result) {
                displayErrorMessage(result.responseJSON.message);
            },
        });
    })

    listenClick('.job-category-show-btn', function (event) {
        // if (ajaxCallIsRunning) {
//            return;
//        }
        ajaxCallInProgress();
        let showJobCategoryId = $(event.currentTarget).attr('data-id');
        $.ajax({
            url: route('job-categories.show', showJobCategoryId),
            type: 'GET',
            success: function (result) {
                if (result.success) {
                    $('#showJobCategoryName').html('');
                    $('#showJobCategoryDescription').html('');
                    $('#showIsFeatured').html('');
                    $('#showJobCategoryName').append(result.data.name);
                    if (!isEmpty(result.data.description) ? $(
                        '#showJobCategoryDescription').
                        append(result.data.description) : $('#showJobCategoryDescription').
                        append('N/A'))
                        (result.data.is_featured == 1) ? $('#showIsFeatured').
                                append('Yes')
                            : $('#showIsFeatured').append('No');
                    $('#showModal').appendTo('body').modal('show');
                    ajaxCallCompleted();
                }
            },
            error: function (result) {
                displayErrorMessage(result.responseJSON.message);
            },
        });
    })

    listenHiddenBsModal('#addJobCategoryModal', function () {
        resetModalForm('#addJobCategoryForm',
            '#jobCategoryValidationErrorsBox');
        let defaultDocumentImageUrl = $('#defaultDocumentImageUrl').val();
        addJobCategoryDescriptionQuill.setContents([{insert: ''}]);
        $('#previewImage').css('background-image', 'url("' + defaultDocumentImageUrl + '")');
        const addSlugInput = document.getElementById('addJobCategorySlug');
        if (addSlugInput) addSlugInput.dataset.manuallyEdited = 'false';
        setJobCategorySearchTags(addJobCategorySearchTags, []);
    })

    listenHiddenBsModal('#jobCategoryEditModal', function () {
        resetModalForm('#editJobCategoryForm', '#editValidationErrorsBox');
    })

    listenClick('#resetFilter', function () {
        $('#filterFeatured').val('').trigger('change');
    })

}

function initAddJobCategorySlug() {
    const nameInput = document.getElementById('addJobCategoryName');
    const slugInput = document.getElementById('addJobCategorySlug');
    if (!nameInput || !slugInput || slugInput.dataset.slugInitialized === 'true') return;

    slugInput.dataset.slugInitialized = 'true';
    slugInput.dataset.manuallyEdited = slugInput.value.trim() ? 'true' : 'false';

    nameInput.addEventListener('input', function () {
        if (slugInput.dataset.manuallyEdited !== 'true') {
            slugInput.value = makeJobCategorySlug(nameInput.value);
        }
    });

    slugInput.addEventListener('input', function () {
        slugInput.dataset.manuallyEdited = slugInput.value.trim() ? 'true' : 'false';
    });
}

function makeJobCategorySlug(value) {
    return String(value || '')
        .normalize('NFKD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase()
        .replace(/&/g, ' and ')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '')
        .replace(/-{2,}/g, '-')
        .substring(0, 180)
        .replace(/-+$/g, '');
}

function initJobCategorySearchTags() {
    const options = {
        delimiters: ',|\\n|\\r',
        duplicates: false,
        trim: true,
        pasteAsTags: true,
        validate: function (tagData) {
            return String(tagData.value || '').trim().length <= 60 || 'Maximum 60 characters';
        },
        originalInputValueFormat: function (values) {
            return values.map(function (item) { return item.value; }).join(',');
        },
    };

    const addInput = document.getElementById('addJobCategorySearchTags');
    const editInput = document.getElementById('editJobCategorySearchTags');

    if (addInput && !addJobCategorySearchTags) {
        addJobCategorySearchTags = new Tagify(addInput, options);
        addJobCategorySearchTags.DOM.scope.classList.add('job-category-tagify');
    }

    if (editInput && !editJobCategorySearchTags) {
        editJobCategorySearchTags = new Tagify(editInput, options);
        editJobCategorySearchTags.DOM.scope.classList.add('job-category-tagify');
    }
}

function setJobCategorySearchTags(tagify, tags) {
    if (!tagify) return;

    tagify.removeAllTags();
    const normalizedTags = Array.isArray(tags) ? tags : String(tags || '').split(/[,\r\n]+/);
    tagify.addTags(normalizedTags.filter(Boolean));
}

listenChange('.isFeaturedJobCategory', function (event) {
    let isFeaturedJobCategoryId = $(event.currentTarget).attr('data-id');
    $.ajax({
        url: route('change-status', isFeaturedJobCategoryId),
        method: 'post',
        cache: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                Livewire.dispatch('refresh');
                Livewire.dispatch('refreshDatatable');
            }
        },
    });
})

listenChange('.jobCategoryStatus', function (event) {
    let jobCategoryId = $(event.currentTarget).attr('data-id');
    $.ajax({
        url: route('job-categories.change-status-value', jobCategoryId),
        method: 'post',
        cache: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                Livewire.dispatch('refresh');
                Livewire.dispatch('refreshDatatable');
            }
        },
    });
})

listenSubmit('#addJobCategoryForm', function (e) {
    e.preventDefault();
    let add_job_category_editor_content = addJobCategoryDescriptionQuill.root.innerHTML;
    if (add_job_category_editor_content.length) {
        if (addJobCategoryDescriptionQuill.getText().trim().length === 0) {
            displayErrorMessage(Lang.get('js.description_required'));
            return false;
        }
    } else {
        displayErrorMessage(Lang.get('js.description_required'));
        return false;
    }

    let input = JSON.stringify(add_job_category_editor_content);
    $('#jobCategoryDescriptionValue').val(input.replace(/"/g, ''));
    processingBtn('#addJobCategoryForm', '#jobCategoryBtnSave', 'loading');

    $.ajax({
        url: route('job-categories.store'),
        type: 'POST',
        data: new FormData(this),
        dataType: 'JSON',
        processData: false,
        contentType: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#addJobCategoryModal').modal('hide');
                Livewire.dispatch('refreshDatatable');
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            processingBtn('#addJobCategoryForm', '#jobCategoryBtnSave');
        },
    });
})

listenSubmit('#editJobCategoryForm', function (event) {
    event.preventDefault();
    let update_editor_content = editJobCategoryDescriptionQuill.root.innerHTML;

    if (editJobCategoryDescriptionQuill.getText().trim().length === 0) {
        displayErrorMessage(Lang.get('js.description_required'));
        return false;
    }

    let input = JSON.stringify(update_editor_content);
    $('#editJobCategoryDescriptionValue').val(input.replace(/"/g, ""));
    processingBtn('#editJobCategoryForm', '#editJobCategorySaveBtn', 'loading');
    const updateJobcategoryId = $('#jobCategoryId').val();
    $.ajax({
        url: route('job-categories.update', updateJobcategoryId),
        type: 'POST',
        data: new FormData($(this)[0]),
        dataType: 'JSON',
        processData: false,
        contentType: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#jobCategoryEditModal').modal('hide');
                Livewire.dispatch('refreshDatatable');
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            processingBtn('#editJobCategoryForm', '#editJobCategorySaveBtn');
        },
    });
})

listenClick('.job-category-delete-btn', function (event) {
    let deleteJobCategoryId = $(event.currentTarget).attr('data-id');
    deleteItem(route('job-categories.destroy', deleteJobCategoryId),
        Lang.get('js.job_category'));
});

listenClick('#remove-image', function () {
    defaultImagePreview('#previewImage', 1);
});
listenChange("#jobCategoryFilter", function() {
         Livewire.dispatch("changeFeaturedFilter", { featured: $(this).val() });
});
listenClick("#jobCategory-ResetFilter", function() {
         $("#jobCategoryFilter").val(2).change();
         hideDropdownManually($('#jobCategoryFilterBtn'), $('.dropdown-menu'));
});
function hideDropdownManually(button, menu) {
    button.dropdown('toggle');
}
