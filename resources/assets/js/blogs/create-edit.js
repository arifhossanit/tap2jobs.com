document.addEventListener('DOMContentLoaded', loadCreateEditBlogData);

function loadCreateEditBlogData() {

    if(!$('#blog_category_id').length){
        return
    }

    $('#blog_category_id').select2({
        width: '100%',
        placeholder: Lang.get('js.select_post_category'),
    });

    window.blogDescriptionEditor = new AppTextEditor('#postDescription', {
        placeholder: Lang.get('js.enter_post_description'),
    });

    listenSubmit('#editBlogForm, #createBlogForm', (e) => {
        if (!blogDescriptionEditor || blogDescriptionEditor.getText().trim().length === 0) {
            e.preventDefault();
            displayErrorMessage(Lang.get('js.description_required'));
            return false;
        }
    });
}

    listenChange('#image', function () {
        let validFile = isValidFile($(this), '#validationErrorsBox');
        if (validFile) {
            displayPhoto(this, '#previewImage');
            $('#btnSave').prop('disabled', false);
        } else {
            $('#btnSave').prop('disabled', true);
        }
    });
