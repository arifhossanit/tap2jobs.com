<div class="d-flex justify-content-end">
    <button type="button" class="btn btn-success bulk-import"
            data-url="{{ route('thanas.import') }}"
            data-sample-file="{{ asset('sample-imports/thanas-sample.csv') }}"
            data-parent-name="city_id"
            data-parent-label="{{ __('messages.thana.city_name') }}"
            data-parent-placeholder="{{ __('messages.company.select_city') }}"
            data-parent-options='@json(getCity())'
            data-parent-required="false"
            data-parent-help="Leave empty when the CSV has a district_name, district_id, or city_id column. Select a District only for simple thana-name-only files."
            data-supported-note="Supported file types: CSV, XLSX, XLS. Full Bangladesh thana files with district_name, district_id, or city_id are supported.">
        Import
    </button>
    <a type="button" class="btn btn-primary form-btn addThanaModal ms-3">
        {{ __('messages.common.add') }}
    </a>
</div>
