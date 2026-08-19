<div id="editEducationDegreeTitleModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Degree Title</h3>
                <button type="button" aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            {{ Form::open(['id'=>'editEducationDegreeTitleForm']) }}
            <div class="modal-body">
                <div class="alert alert-danger fs-4 text-white d-flex align-items-center d-none" id="editEducationDegreeTitleValidationErrorsBox">
                    <i class="fa-solid fa-face-frown me-5"></i>
                </div>
                {{ Form::hidden('educationDegreeTitleId', null, ['id'=>'editEducationDegreeTitleId']) }}
                <div class="mb-5">
                    {{ Form::label('required_degree_level_id', __('messages.required_degree_levels').':', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::select('required_degree_level_id', $degreeLevels, null, ['id'=>'editDegreeLevelId', 'class' => 'form-select', 'required', 'placeholder' => 'Select Level']) }}
                </div>
                <div class="mb-5">
                    {{ Form::label('name', __('messages.common.name').':', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::text('name', null, ['id'=>'editDegreeTitleName', 'class' => 'form-control', 'required', 'placeholder' => __('messages.common.name')]) }}
                </div>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary m-0', 'id' => 'editEducationDegreeTitleBtnSave', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> ".__('messages.common.process')]) }}
                <button type="button" class="btn btn-light btn-active-light-primary ms-5 me-2" data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
