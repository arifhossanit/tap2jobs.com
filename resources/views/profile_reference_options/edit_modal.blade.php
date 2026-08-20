<div id="editProfileReferenceOptionModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Profile Reference</h3>
                <button type="button" aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            {{ Form::open(['id'=>'editProfileReferenceOptionForm']) }}
            <div class="modal-body">
                <div class="alert alert-danger fs-4 text-white d-flex align-items-center d-none" id="editProfileReferenceOptionValidationErrorsBox">
                    <i class="fa-solid fa-face-frown me-5"></i>
                </div>
                {{ Form::hidden('profileReferenceOptionId', null, ['id'=>'editProfileReferenceOptionId']) }}
                {{ Form::hidden('scope', $scope) }}
                <div class="mb-5">
                    {{ Form::label('label', __('messages.common.name').':', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::text('label', null, ['id'=>'editProfileReferenceLabel', 'class' => 'form-control', 'required', 'placeholder' => __('messages.common.name')]) }}
                </div>
                <div class="mb-5">
                    {{ Form::label('value', 'Stored Value:', ['class' => 'form-label']) }}
                    {{ Form::text('value', null, ['id'=>'editProfileReferenceValue', 'class' => 'form-control', 'placeholder' => 'Leave blank to use name']) }}
                </div>
                <div class="mb-5">
                    {{ Form::label('sort_order', 'Sort Order:', ['class' => 'form-label']) }}
                    {{ Form::number('sort_order', 0, ['id'=>'editProfileReferenceSortOrder', 'class' => 'form-control', 'min' => 0]) }}
                </div>
                <div class="form-check form-switch">
                    {{ Form::checkbox('is_active', '1', true, ['id'=>'editProfileReferenceIsActive', 'class' => 'form-check-input']) }}
                    {{ Form::label('is_active', __('messages.common.active'), ['class' => 'form-check-label']) }}
                </div>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary m-0', 'id' => 'editProfileReferenceOptionBtnSave', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> ".__('messages.common.process')]) }}
                <button type="button" class="btn btn-light btn-active-light-primary ms-5 me-2" data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
