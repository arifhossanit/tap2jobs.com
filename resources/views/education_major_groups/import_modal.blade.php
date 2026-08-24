<div id="importEducationMajorGroupModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Import Major / Groups</h3>
                <button type="button" aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            {{ Form::open(['id' => 'importEducationMajorGroupForm', 'files' => true]) }}
            <div class="modal-body">
                <div class="alert alert-danger fs-4 text-white d-flex align-items-center d-none"
                     id="importEducationMajorGroupValidationErrorsBox">
                    <i class="fa-solid fa-face-frown me-5"></i>
                </div>
                <div class="mb-5">
                    {{ Form::label('required_degree_level_id', __('messages.required_degree_levels').':', ['class' => 'form-label']) }}
                    {{ Form::select('required_degree_level_id', $degreeLevels, null, ['class' => 'form-select', 'id' => 'importMajorDegreeLevelId', 'placeholder' => 'Select Level (Optional)']) }}
                    <small class="text-muted d-block mt-2">Leave empty when the CSV has a degree_level, degree_level_id, or required_degree_level_id column. Select a Level only for simple major-only files.</small>
                </div>
                <div class="mb-5">
                    {{ Form::label('file', 'File:', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    <input type="file" name="file" class="form-control" accept=".csv,.xlsx,.xls" required>
                </div>
                <a class="d-block mt-3" href="{{ asset('sample-imports/education-major-groups-sample.csv') }}?v={{ filemtime(public_path('sample-imports/education-major-groups-sample.csv')) }}">Download sample file</a>
                <small class="text-muted d-block mt-2">Supported file types: CSV, XLSX, XLS. Files with degree_level, degree_level_id, or required_degree_level_id are supported.</small>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary m-0', 'id' => 'importEducationMajorGroupBtnSave', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> ".__('messages.common.process')]) }}
                <button type="button" class="btn btn-light btn-active-light-primary ms-5 me-2" data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
