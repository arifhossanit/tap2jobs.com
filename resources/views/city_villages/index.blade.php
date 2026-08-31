@extends('layouts.app')
@section('title')
    {{ __('messages.city_village.city_villages') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column ">
            @include('flash::message')
            <livewire:city-village-table/>
        </div>
    </div>
    @include('city_villages.add_modal')
    @include('city_villages.edit_modal')
    {{ Form::hidden('cityVillagesData', true, ['id' => 'indexCityVillagesData']) }}

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (!$('#indexCityVillagesData').length) {
                return;
            }

            $('#selectCityVillageDistrict').select2();
            $('#cityVillageCityId').select2({ width: '100%', dropdownParent: $('#addCityVillageModal') });
            $('#editCityVillageCityId').select2({ width: '100%', dropdownParent: $('#editCityVillageModal') });

            listenClick('.addCityVillageModal', function () {
                $('#addCityVillageModal').appendTo('body').modal('show');
            });

            listenClick('.city-villages-edit-btn', function (event) {
                let cityVillageId = $(event.currentTarget).attr('data-id');
                $.ajax({
                    url: route('city-villages.edit', cityVillageId),
                    type: 'GET',
                    success: function (result) {
                        if (result.success) {
                            $('#cityVillageId').val(result.data.id);
                            $('#editCityVillageName').val(result.data.name);
                            $('#editCityVillageCityId').val(result.data.city_id).trigger('change');
                            $('#editCityVillageModal').appendTo('body').modal('show');
                        }
                    },
                    error: function (result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                });
            });

            listenClick('.city-villages-delete-btn', function (event) {
                let cityVillageId = $(event.currentTarget).attr('data-id');
                deleteItem(route('city-villages.destroy', cityVillageId), 'City/Village');
            });

            listenHiddenBsModal('#addCityVillageModal', function () {
                $('#cityVillageCityId').val('').trigger('change');
                resetModalForm('#addCityVillageForm', '#cityVillageValidationErrorsBox');
            });

            listenHiddenBsModal('#editCityVillageModal', function () {
                resetModalForm('#editCityVillageForm', '#editCityVillageValidationErrorsBox');
            });

            listenSubmit('#addCityVillageForm', function (event) {
                event.preventDefault();
                processingBtn('#addCityVillageForm', '#cityVillageBtnSave', 'loading');
                $.ajax({
                    url: route('city-villages.store'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            $('#addCityVillageModal').modal('hide');
                            Livewire.dispatch('refreshDatatable');
                        }
                    },
                    error: function (result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                    complete: function () {
                        processingBtn('#addCityVillageForm', '#cityVillageBtnSave');
                    },
                });
            });

            listenSubmit('#editCityVillageForm', function (event) {
                event.preventDefault();
                processingBtn('#editCityVillageForm', '#editCityVillageBtnSave', 'loading');
                $.ajax({
                    url: route('city-villages.update', $('#cityVillageId').val()),
                    type: 'PUT',
                    data: $(this).serialize(),
                    success: function (result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            $('#editCityVillageModal').modal('hide');
                            Livewire.dispatch('refreshDatatable');
                        }
                    },
                    error: function (result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                    complete: function () {
                        processingBtn('#editCityVillageForm', '#editCityVillageBtnSave');
                    },
                });
            });

            listenChange('#selectCityVillageDistrict', function () {
                Livewire.dispatch('changeCityVillageDistrictFilter', { city: $(this).val() });
            });

            listenClick('#city-village-reset-filter', function () {
                $('#selectCityVillageDistrict').val(0).trigger('change');
                $('#selectCityVillageDistrictBtn').dropdown('toggle');
            });
        });
    </script>
@endsection
