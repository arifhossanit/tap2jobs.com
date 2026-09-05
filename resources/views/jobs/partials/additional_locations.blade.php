@php
    $jobLocationCountries = $data['countries'] ?? $countries ?? [];
    $additionalJobLocations = collect(old('job_locations', isset($job) ? $job->locations->where('is_primary', false)->values()->map(function ($location) {
        return [
            'country_id' => $location->country_id,
            'state_id' => $location->state_id,
            'city_id' => $location->city_id,
            'thana_id' => $location->thana_id,
            'city_village_name' => $location->city_village_name,
            'address' => $location->address,
        ];
    })->all() : []))->values();
@endphp

<div class="job-additional-locations">
    <div class="d-flex align-items-center justify-content-end mb-3">
        <button type="button" class="btn btn-sm btn-outline-primary" id="addJobLocationBtn">
            <i class="fa fa-plus me-1"></i> Add location
        </button>
    </div>
    <div id="additionalJobLocations" data-next-index="{{ max(1, $additionalJobLocations->count() + 1) }}">
        @foreach($additionalJobLocations as $locationIndex => $location)
            @php
                $fieldIndex = $locationIndex + 1;
                $locationStates = ! empty($location['country_id']) ? getStates($location['country_id']) : [];
                $locationCities = ! empty($location['state_id']) ? getCities($location['state_id']) : [];
                $locationThanas = ! empty($location['city_id']) ? getThanas($location['city_id']) : [];
            @endphp
            <div class="border rounded p-4 mb-4 job-location-row" data-location-index="{{ $fieldIndex }}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <strong>Location {{ $fieldIndex + 1 }}</strong>
                    <button type="button" class="btn btn-sm btn-light-danger remove-job-location">
                        <i class="fa fa-trash me-1"></i> Remove
                    </button>
                </div>
                <div class="row">
                    <div class="col-xl-2 col-md-4 col-sm-12 mb-4">
                        {{ Form::label("job_locations[$fieldIndex][country_id]", __('messages.company.country').':', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::select("job_locations[$fieldIndex][country_id]", $jobLocationCountries, $location['country_id'] ?? null, ['class' => 'form-select job-location-country', 'placeholder' => __('messages.company.select_country'), 'required']) }}
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-12 mb-4">
                        {{ Form::label("job_locations[$fieldIndex][state_id]", __('messages.company.state').':', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::select("job_locations[$fieldIndex][state_id]", $locationStates, $location['state_id'] ?? null, ['class' => 'form-select job-location-state', 'placeholder' => __('messages.company.select_state'), 'required']) }}
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-12 mb-4">
                        {{ Form::label("job_locations[$fieldIndex][city_id]", __('messages.company.city').':', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::select("job_locations[$fieldIndex][city_id]", $locationCities, $location['city_id'] ?? null, ['class' => 'form-select job-location-city', 'placeholder' => __('messages.company.select_city'), 'required']) }}
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-12 mb-4">
                        {{ Form::label("job_locations[$fieldIndex][thana_id]", __('messages.thana.thana_name').':', ['class' => 'form-label']) }}
                        {{ Form::select("job_locations[$fieldIndex][thana_id]", $locationThanas, $location['thana_id'] ?? null, ['class' => 'form-select job-location-thana', 'placeholder' => __('messages.company.select_thana')]) }}
                    </div>
                    <div class="col-xl-4 col-md-4 col-sm-12 mb-4">
                        {{ Form::label("job_locations[$fieldIndex][city_village_name]", __('messages.city_village.city_villages').':', ['class' => 'form-label']) }}
                        {{ Form::text("job_locations[$fieldIndex][city_village_name]", $location['city_village_name'] ?? null, ['class' => 'form-control', 'placeholder' => 'Enter Area / City / Village']) }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<template id="jobLocationTemplate">
    <div class="border rounded p-4 mb-4 job-location-row" data-location-index="__INDEX__">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <strong>Location __NUMBER__</strong>
            <button type="button" class="btn btn-sm btn-light-danger remove-job-location">
                <i class="fa fa-trash me-1"></i> Remove
            </button>
        </div>
        <div class="row">
            <div class="col-xl-2 col-md-4 col-sm-12 mb-4">
                <label class="form-label">Country:</label><span class="required"></span>
                <select name="job_locations[__INDEX__][country_id]" class="form-select job-location-country" required>
                    <option value="">{{ __('messages.company.select_country') }}</option>
                    @foreach($jobLocationCountries as $countryId => $countryName)
                        <option value="{{ $countryId }}">{{ $countryName }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-12 mb-4">
                <label class="form-label">Division:</label><span class="required"></span>
                <select name="job_locations[__INDEX__][state_id]" class="form-select job-location-state" required>
                    <option value="">{{ __('messages.company.select_state') }}</option>
                </select>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-12 mb-4">
                <label class="form-label">District:</label><span class="required"></span>
                <select name="job_locations[__INDEX__][city_id]" class="form-select job-location-city" required>
                    <option value="">{{ __('messages.company.select_city') }}</option>
                </select>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-12 mb-4">
                <label class="form-label">Thana Name:</label>
                <select name="job_locations[__INDEX__][thana_id]" class="form-select job-location-thana">
                    <option value="">{{ __('messages.company.select_thana') }}</option>
                </select>
            </div>
            <div class="col-xl-4 col-md-4 col-sm-12 mb-4">
                <label class="form-label">City/Villages:</label>
                <input type="text" name="job_locations[__INDEX__][city_village_name]" class="form-control" placeholder="Enter Area / City / Village">
            </div>
        </div>
    </div>
</template>

@once
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var container = document.getElementById('additionalJobLocations');
            var template = document.getElementById('jobLocationTemplate');
            var addButton = document.getElementById('addJobLocationBtn');

            if (!container || !template || !addButton || typeof $ === 'undefined') {
                return;
            }

            function placeholder(key, fallback) {
                return typeof Lang !== 'undefined' && Lang.get ? (Lang.get(key) || fallback) : fallback;
            }

            function initSelects(scope) {
                $(scope).find('.job-location-country, .job-location-state, .job-location-city, .job-location-thana').each(function () {
                    var select = $(this);

                    if (select.hasClass('select2-hidden-accessible')) {
                        select.select2('destroy');
                    }

                    select.select2({ width: '100%' });
                });
            }

            function resetSelect(select, text) {
                select.empty().append($('<option value=""></option>').text(text));
                select.trigger('change.select2');
            }

            function loadStates(row, countryId) {
                var state = row.find('.job-location-state');
                var city = row.find('.job-location-city');
                var thana = row.find('.job-location-thana');

                resetSelect(state, placeholder('js.select_state', 'Select District'));
                resetSelect(city, placeholder('js.select_city', 'Select City'));
                resetSelect(thana, placeholder('js.select_thana', 'Select Thana'));

                if (!countryId) {
                    return;
                }

                $.ajax({
                    url: route('states-list'),
                    type: 'get',
                    dataType: 'json',
                    data: { postal: countryId },
                    success: function (data) {
                        $.each(data.data || {}, function (id, name) {
                            state.append($('<option></option>').attr('value', id).text(name));
                        });
                        state.trigger('change.select2');
                    }
                });
            }

            function loadCities(row, stateId) {
                var city = row.find('.job-location-city');
                var thana = row.find('.job-location-thana');

                resetSelect(city, placeholder('js.select_city', 'Select City'));
                resetSelect(thana, placeholder('js.select_thana', 'Select Thana'));

                if (!stateId) {
                    return;
                }

                $.ajax({
                    url: route('cities-list'),
                    type: 'get',
                    dataType: 'json',
                    data: {
                        state: stateId,
                        country: row.find('.job-location-country').val()
                    },
                    success: function (data) {
                        $.each(data.data || {}, function (id, name) {
                            city.append($('<option></option>').attr('value', id).text(name));
                        });
                        city.trigger('change.select2');
                    }
                });
            }

            function loadThanas(row, cityId) {
                var thana = row.find('.job-location-thana');

                resetSelect(thana, placeholder('js.select_thana', 'Select Thana'));

                if (!cityId) {
                    return;
                }

                $.ajax({
                    url: route('thanas-list'),
                    type: 'get',
                    dataType: 'json',
                    data: { city: cityId },
                    success: function (data) {
                        $.each(data.data || {}, function (id, name) {
                            thana.append($('<option></option>').attr('value', id).text(name));
                        });
                        thana.trigger('change.select2');
                    }
                });
            }

            addButton.addEventListener('click', function () {
                var index = parseInt(container.dataset.nextIndex || '1', 10);
                var html = template.innerHTML
                    .replaceAll('__INDEX__', index)
                    .replaceAll('__NUMBER__', index + 1);

                container.insertAdjacentHTML('beforeend', html);
                container.dataset.nextIndex = String(index + 1);
                initSelects(container.lastElementChild);
            });

            $(container)
                .on('click', '.remove-job-location', function () {
                    $(this).closest('.job-location-row').remove();
                })
                .on('change', '.job-location-country', function () {
                    loadStates($(this).closest('.job-location-row'), $(this).val());
                })
                .on('change', '.job-location-state', function () {
                    loadCities($(this).closest('.job-location-row'), $(this).val());
                })
                .on('change', '.job-location-city', function () {
                    loadThanas($(this).closest('.job-location-row'), $(this).val());
                });

            initSelects(container);
        });
    </script>
@endonce
