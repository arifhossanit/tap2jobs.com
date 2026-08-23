@extends('layouts.app')
@section('title')
    {{ __('messages.thana.thanas') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column ">
            @include('flash::message')
            <livewire:thana-table/>
        </div>
    </div>
    @include('thanas.add_modal')
    @include('thanas.edit_modal')
    {{ Form::hidden('thanasData', true, ['id' => 'indexThanasData']) }}

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (!$('#indexThanasData').length) {
                return;
            }

            $('#selectCity').select2();
            $('#cityID').select2({ width: '100%', dropdownParent: $('#addThanaModal') });
            $('#editCityId').select2({ width: '100%', dropdownParent: $('#editThanaModal') });

            listenClick('.addThanaModal', function () {
                $('#addThanaModal').appendTo('body').modal('show');
            });

            listenClick('.thanas-edit-btn', function (event) {
                let thanaId = $(event.currentTarget).attr('data-id');
                $.ajax({
                    url: route('thanas.edit', thanaId),
                    type: 'GET',
                    success: function (result) {
                        if (result.success) {
                            $('#thanaId').val(result.data.id);
                            $('#editName').val(result.data.name);
                            $('#editCityId').val(result.data.city_id).trigger('change');
                            $('#editThanaModal').appendTo('body').modal('show');
                        }
                    },
                    error: function (result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                });
            });

            listenClick('.thanas-delete-btn', function (event) {
                let thanaId = $(event.currentTarget).attr('data-id');
                deleteItem(route('thanas.destroy', thanaId), 'Thana');
            });

            listenHiddenBsModal('#addThanaModal', function () {
                $('#cityID').val('').trigger('change');
                resetModalForm('#addThanaForm', '#thanaValidationErrorsBox');
            });

            listenHiddenBsModal('#editThanaModal', function () {
                resetModalForm('#editThanaForm', '#editThanaValidationErrorsBox');
            });

            listenSubmit('#addThanaForm', function (event) {
                event.preventDefault();
                processingBtn('#addThanaForm', '#thanaBtnSave', 'loading');
                $.ajax({
                    url: route('thanas.store'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            $('#addThanaModal').modal('hide');
                            Livewire.dispatch('refreshDatatable');
                        }
                    },
                    error: function (result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                    complete: function () {
                        processingBtn('#addThanaForm', '#thanaBtnSave');
                    },
                });
            });

            listenSubmit('#editThanaForm', function (event) {
                event.preventDefault();
                processingBtn('#editThanaForm', '#editThanaBtnSave', 'loading');
                $.ajax({
                    url: route('thanas.update', $('#thanaId').val()),
                    type: 'PUT',
                    data: $(this).serialize(),
                    success: function (result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            $('#editThanaModal').modal('hide');
                            Livewire.dispatch('refreshDatatable');
                        }
                    },
                    error: function (result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                    complete: function () {
                        processingBtn('#editThanaForm', '#editThanaBtnSave');
                    },
                });
            });

            listenChange('#selectCity', function () {
                Livewire.dispatch('changeCityFilter', { city: $(this).val() });
            });

            listenClick('#city-ResetFilter', function () {
                $('#selectCity').val(0).trigger('change');
                $('#selectCityBtn').dropdown('toggle');
            });
        });
    </script>
@endsection
