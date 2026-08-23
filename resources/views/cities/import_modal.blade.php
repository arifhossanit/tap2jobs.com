<div id="importCityModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Import Districts</h3>
                <button type="button" aria-label="Close" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            {{ Form::open(['id' => 'importCityForm', 'files' => true]) }}
            <div class="modal-body">
                <div class="alert alert-danger fs-4 text-white d-flex align-items-center d-none"
                     id="importCityValidationErrorsBox">
                    <i class="fa-solid fa-face-frown me-5"></i>
                </div>
                <div class="mb-5">
                    {{ Form::label('state_id', __('messages.city.state_name').' (optional):', ['class' => 'form-label']) }}
                    {{ Form::select('state_id', $states, null, ['class' => 'form-select', 'id' => 'importStateId', 'placeholder' => __('messages.company.select_state')]) }}
                    <small class="text-muted d-block mt-2">Leave empty when the CSV has a division_id column. Select a Division only for simple district-name-only files.</small>
                </div>
                <div class="mb-5">
                    {{ Form::label('file', 'File:', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    <input type="file" name="file" class="form-control" accept=".csv,.xlsx,.xls" required>
                </div>
                <a class="d-block mt-3" href="{{ asset('sample-imports/districts-sample.csv') }}">Download sample file</a>
                <small class="text-muted d-block mt-2">Supported file types: CSV, XLSX, XLS. Full Bangladesh district files with division_id are supported.</small>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.import'), ['type' => 'submit', 'class' => 'btn btn-primary m-0', 'id' => 'importCityBtnSave', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> ".__('messages.common.process')]) }}
                <button type="button" class="btn btn-secondary my-0 {{ checkLanguageSession() == 'ar' ? 'me-5' : 'ms-5' }} me-0"
                        data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
