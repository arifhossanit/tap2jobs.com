<div class="dropdown d-flex align-items-center me-4 me-md-5">
    <button class="btn btn btn-icon btn-primary text-white dropdown-toggle hide-arrow ps-2 pe-0" type="button"
            id="selectCityBtn" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <p class="text-center">
            <i class='fas fa-filter'></i>
        </p>
    </button>
    <div class="dropdown-menu py-0" aria-labelledby="selectCityBtn">
        <div class="text-start border-bottom py-4 px-7">
            <h3 class="text-gray-900 mb-0">{{ __('messages.common.filter_options') }}</h3>
        </div>
        <div class="p-5">
            <div class="mb-5">
                <label for="selectCity" class="form-label">{{ __('messages.thana.city_name') }}:</label>
                {{ Form::select('city', ['0' => __('messages.company.select_city')] + getCity(), null, ['class' => 'form-select', 'id' => 'selectCity', 'data-control' => 'select2']) }}
            </div>
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-secondary" id="city-ResetFilter">{{ __('messages.common.reset') }}</button>
            </div>
        </div>
    </div>
</div>
