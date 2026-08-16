<div id="editFAQCategoryModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit FAQ Category</h3>
                <button type="button" aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            {{ Form::open(['id'=>'editFAQCategoryForm']) }}
            {{ Form::hidden('category_id', null, ['id' => 'editFAQCategoryId']) }}
            <div class="modal-body">
                <div class="alert alert-danger fs-4 text-white d-flex align-items-center d-none" id="editCatValidationErrorsBox">
                    <i class="fa-solid fa-face-frown me-5"></i>
                </div>
                <div class="mb-5">
                    {{ Form::label('audience', 'Audience:', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::select('audience', ['candidate' => 'Candidate', 'employer' => 'Employer'], null, ['id' => 'editFAQCategoryAudience', 'class' => 'form-select', 'required']) }}
                </div>
                <div class="mb-5">
                    {{ Form::label('name_en', 'Category Name (English):', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::text('name_en', null, ['id' => 'editFAQCategoryNameEn', 'class' => 'form-control', 'required']) }}
                </div>
                <div class="mb-5">
                    {{ Form::label('name_bn', 'Category Name (বাংলা):', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::text('name_bn', null, ['id' => 'editFAQCategoryNameBn', 'class' => 'form-control', 'required']) }}
                </div>
                
                <div class="mb-5">
                    {{ Form::label('icon', 'FontAwesome Icon Class:', ['class' => 'form-label']) }}
                    {{ Form::text('icon', null, ['id' => 'editFAQCategoryIcon', 'class' => 'form-control', 'placeholder' => 'e.g. fa-solid fa-user']) }}
                </div>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary m-0', 'id' => 'editFAQCategorySaveBtn', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                <button type="button" class="btn btn-secondary my-0 ms-3" data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
