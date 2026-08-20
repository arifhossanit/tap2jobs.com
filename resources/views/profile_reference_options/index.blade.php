@extends('layouts.app')
@section('title')
    {{ $title }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="fw-bold m-0">{{ $title }}</h3>
                    </div>
                    <div class="card-toolbar">
                        @include('profile_reference_options.table-components.add_button')
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                            <tr>
                                <th>{{ __('messages.common.name') }}</th>
                                <th>Value</th>
                                <th>Sort</th>
                                <th>{{ __('messages.common.status') }}</th>
                                <th>{{ __('messages.common.action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($options as $option)
                                <tr>
                                    <td>{{ $option->label }}</td>
                                    <td>{{ $option->value }}</td>
                                    <td>{{ $option->sort_order }}</td>
                                    <td>{{ $option->is_active ? __('messages.common.active') : __('messages.common.deactive') }}</td>
                                    <td>
                                        <a href="javascript:void(0)"
                                           class="btn px-2 text-primary fs-3 profile-reference-option-edit-btn"
                                           data-id="{{ $option->id }}"
                                           data-bs-toggle="tooltip">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="javascript:void(0)"
                                           class="profile-reference-option-delete-btn btn px-2 text-danger fs-3"
                                           data-id="{{ $option->id }}"
                                           data-bs-toggle="tooltip">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">{{ __('messages.common.no_data_available') }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @include('profile_reference_options.add_modal')
        @include('profile_reference_options.edit_modal')
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const scope = @json($scope);
            const type = @json($type);

            listenClick('.addProfileReferenceOptionModal', function () {
                $('#addProfileReferenceOptionModal').appendTo('body').modal('show');
            });

            listenClick('.profile-reference-option-edit-btn', function (event) {
                const optionId = event.currentTarget.dataset.id;
                $.ajax({
                    url: route('profileReferenceOptions.edit', [scope, type, optionId]),
                    type: 'GET',
                    success: function (result) {
                        if (result.success) {
                            $('#editProfileReferenceOptionId').val(result.data.id);
                            $('#editProfileReferenceLabel').val(result.data.label);
                            $('#editProfileReferenceValue').val(result.data.value);
                            $('#editProfileReferenceSortOrder').val(result.data.sort_order);
                            $('#editProfileReferenceIsActive').prop('checked', Boolean(result.data.is_active));
                            $('#editProfileReferenceOptionModal').appendTo('body').modal('show');
                        }
                    },
                });
            });

            listenClick('.profile-reference-option-delete-btn', function (event) {
                deleteItem(
                    route('profileReferenceOptions.destroy', [scope, type, event.currentTarget.dataset.id]),
                    'Profile Reference',
                    null,
                    'location.reload()'
                );
            });

            listenHiddenBsModal('#addProfileReferenceOptionModal', function () {
                resetModalForm('#addProfileReferenceOptionForm', '#profileReferenceOptionValidationErrorsBox');
                $('#profileReferenceIsActive').prop('checked', true);
            });

            listenHiddenBsModal('#editProfileReferenceOptionModal', function () {
                resetModalForm('#editProfileReferenceOptionForm', '#editProfileReferenceOptionValidationErrorsBox');
            });

            listenSubmit('#addProfileReferenceOptionForm', function (event) {
                event.preventDefault();
                processingBtn('#addProfileReferenceOptionForm', '#profileReferenceOptionBtnSave', 'loading');
                $.ajax({
                    url: route('profileReferenceOptions.store', [scope, type]),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            $('#addProfileReferenceOptionModal').modal('hide');
                            location.reload();
                        }
                    },
                    error: function (result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                    complete: function () {
                        processingBtn('#addProfileReferenceOptionForm', '#profileReferenceOptionBtnSave');
                    },
                });
            });

            listenSubmit('#editProfileReferenceOptionForm', function (event) {
                event.preventDefault();
                processingBtn('#editProfileReferenceOptionForm', '#editProfileReferenceOptionBtnSave', 'loading');
                const optionId = $('#editProfileReferenceOptionId').val();
                $.ajax({
                    url: route('profileReferenceOptions.update', [scope, type, optionId]),
                    type: 'PUT',
                    data: $(this).serialize(),
                    success: function (result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            $('#editProfileReferenceOptionModal').modal('hide');
                            location.reload();
                        }
                    },
                    error: function (result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                    complete: function () {
                        processingBtn('#editProfileReferenceOptionForm', '#editProfileReferenceOptionBtnSave');
                    },
                });
            });
        });
    </script>
@endsection
