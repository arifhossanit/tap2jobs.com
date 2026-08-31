<div id="editCityVillageModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">{{ __('messages.city_village.edit_city_village') }}</h3>
                <button type="button" aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            {{ Form::open(['id' => 'editCityVillageForm']) }}
            <div class="modal-body">
                <div class="alert alert-danger fs-4 text-white d-flex align-items-center d-none"
                     id="editCityVillageValidationErrorsBox">
                    <i class="fa-solid fa-face-frown me-5"></i>
                </div>
                {{ Form::hidden('cityVillageId', null, ['id' => 'cityVillageId']) }}
                <div class="mb-5">
                    {{ Form::label('city_id', __('messages.city.city_name').':', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::select('city_id', $cities ?? [], null, ['id' => 'editCityVillageCityId', 'required', 'class' => 'form-select', 'placeholder' => __('messages.company.select_city')]) }}
                </div>
                <div class="mb-5">
                    {{ Form::label('name', __('messages.common.name').':', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::textarea('name', null, ['id' => 'editCityVillageName', 'class' => 'form-control', 'required', 'rows' => 3, 'placeholder' => __('messages.common.name')]) }}
                </div>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary m-0', 'id' => 'editCityVillageBtnSave', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> ".__('messages.common.process')]) }}
                <button type="button" class="btn btn-secondary my-0 {{ checkLanguageSession() == 'ar' ? 'me-5' : 'ms-5' }} me-0"
                        data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
