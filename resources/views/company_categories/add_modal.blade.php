<div id="addCompanyCategoryModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">New Company Category</h3>
                <button type="button" aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            {{ Form::open(['id'=>'addCompanyCategoryForm']) }}
            <div class="modal-body">
                <div class="alert alert-danger fs-4 text-white d-flex align-items-center d-none" id="companyCategoryValidationErrorsBox">
                    <i class="fa-solid fa-face-frown me-5"></i>
                </div>
                <div class="mb-5">
                    {{ Form::label('name', 'Name:', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::textarea('name', null, ['id' => 'companyCategoryName', 'class' => 'form-control', 'required', 'rows' => 3, 'placeholder' => 'Enter name or multiple names']) }}
                </div>
                <div class="mb-5">
                    {{ Form::label('sort_order', 'Sort:', ['class' => 'form-label']) }}
                    {{ Form::number('sort_order', 0, ['id' => 'companyCategorySortOrder', 'class' => 'form-control', 'min' => 0]) }}
                </div>
                <div class="form-check mb-5">
                    {{ Form::checkbox('is_active', '1', true, ['id' => 'companyCategoryIsActive', 'class' => 'form-check-input']) }}
                    {{ Form::label('is_active', __('messages.common.active'), ['class' => 'form-check-label']) }}
                </div>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary m-0', 'id' => 'companyCategoryBtnSave', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> ".__('messages.common.process')]) }}
                <button type="button" class="btn btn-secondary my-0 ms-5 me-0" data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
