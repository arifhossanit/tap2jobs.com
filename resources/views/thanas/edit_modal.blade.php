<div id="editThanaModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">{{ __('messages.thana.edit_thana') }}</h3>
                <button type="button" aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            {{ Form::open(['id' => 'editThanaForm']) }}
            <div class="modal-body">
                <div class="alert alert-danger fs-4 text-white d-flex align-items-center d-none"
                     id="editThanaValidationErrorsBox">
                    <i class="fa-solid fa-face-frown me-5"></i>
                </div>
                <div class="mb-5">
                    {{ Form::label('city_id', __('messages.thana.city_name').':', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::select('city_id', $cities, null, ['id' => 'editCityId', 'class' => 'form-select', 'required', 'placeholder' => __('messages.company.select_city')]) }}
                </div>
                {{ Form::hidden('thanaId', null, ['id' => 'thanaId']) }}
                <div class="mb-5">
                    {{ Form::label('name', __('messages.common.name').':', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::textarea('name', null, ['id' => 'editName', 'class' => 'form-control', 'required', 'rows' => 3, 'placeholder' => __('messages.common.name')]) }}
                </div>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary m-0', 'id' => 'editThanaBtnSave', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> ".__('messages.common.process')]) }}
                <button type="button" class="btn btn-secondary my-0 {{ checkLanguageSession() == 'ar' ? 'me-5' : 'ms-5' }} me-0"
                        data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
