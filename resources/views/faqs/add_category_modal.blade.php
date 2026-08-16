<div id="addFAQCategoryModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Add FAQ Category</h3>
                <button type="button" aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            {{ Form::open(['id'=>'addFAQCategoryForm']) }}
            <div class="modal-body">
                <div class="alert alert-danger fs-4 text-white d-flex align-items-center d-none" id="catValidationErrorsBox">
                    <i class="fa-solid fa-face-frown me-5"></i>
                </div>
                <div class="mb-5">
                    {{ Form::label('audience', 'Audience:', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::select('audience', ['candidate' => 'Candidate', 'employer' => 'Employer'], 'candidate', ['class' => 'form-select', 'required']) }}
                </div>
                <div class="mb-5">
                    {{ Form::label('name_en', 'Category Name (English):', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::text('name_en', null, ['class' => 'form-control', 'required', 'placeholder' => 'Enter English category name']) }}
                </div>
                <div class="mb-5">
                    {{ Form::label('name_bn', 'Category Name (বাংলা):', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::text('name_bn', null, ['class' => 'form-control', 'required', 'placeholder' => 'বাংলা ক্যাটাগরি নাম লিখুন']) }}
                </div>
                
                <div class="mb-5">
                    {{ Form::label('icon', 'FontAwesome Icon Class:', ['class' => 'form-label']) }}
                    {{ Form::text('icon', 'fa-solid fa-folder-open', ['class' => 'form-control', 'placeholder' => 'e.g. fa-solid fa-user']) }}
                    <small class="text-muted">e.g. fa-solid fa-user, fa-solid fa-briefcase, fa-solid fa-circle-question</small>
                </div>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary m-0', 'id' => 'addFAQCategorySaveBtn', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                <button type="button" class="btn btn-secondary my-0 ms-3" data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
