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
                        <button type="button"
                                class="btn btn-danger me-2 d-none"
                                id="profileReferenceBulkDeleteBtn"
                                disabled>
                            Bulk Delete
                        </button>
                        @include('profile_reference_options.table-components.add_button')
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                            <tr>
                                <th>
                                    <input type="checkbox"
                                           class="form-check-input"
                                           id="profileReferenceSelectAll">
                                </th>
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
                                    <td>
                                        <input type="checkbox"
                                               class="form-check-input profile-reference-option-checkbox"
                                               value="{{ $option->id }}">
                                    </td>
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
                                    <td colspan="6" class="text-center">{{ __('messages.common.no_data_available') }}</td>
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
            const bulkDeleteBtn = $('#profileReferenceBulkDeleteBtn');
            const selectAllCheckbox = $('#profileReferenceSelectAll');

            const selectedProfileReferenceOptionIds = function () {
                return $('.profile-reference-option-checkbox:checked').map(function () {
                    return this.value;
                }).get();
            };

            const refreshProfileReferenceBulkDeleteState = function () {
                const selectedCount = selectedProfileReferenceOptionIds().length;
                bulkDeleteBtn.toggleClass('d-none', selectedCount === 0).prop('disabled', selectedCount === 0);
                selectAllCheckbox.prop(
                    'checked',
                    $('.profile-reference-option-checkbox').length > 0
                    && selectedCount === $('.profile-reference-option-checkbox').length
                );
            };

            listenClick('.addProfileReferenceOptionModal', function () {
                $('#addProfileReferenceOptionModal').appendTo('body').modal('show');
            });

            listenChange('#profileReferenceSelectAll', function () {
                $('.profile-reference-option-checkbox').prop('checked', this.checked);
                refreshProfileReferenceBulkDeleteState();
            });

            listenChange('.profile-reference-option-checkbox', function () {
                refreshProfileReferenceBulkDeleteState();
            });

            listenClick('#profileReferenceBulkDeleteBtn', function () {
                const ids = selectedProfileReferenceOptionIds();

                if (ids.length === 0) {
                    return;
                }

                swal({
                    title: Lang.get('js.delete') + ' !',
                    text: Lang.get('js.are_you_sure') + ' "Selected Reference Options" ?',
                    buttons: {
                        confirm: Lang.get('js.yes_delete'),
                        cancel: Lang.get('js.no_cancel'),
                    },
                    reverseButtons: true,
                    confirmButtonColor: '#F62947',
                    cancelButtonColor: '#ADB5BD',
                    icon: 'warning',
                }).then(function (willDelete) {
                    if (!willDelete) {
                        return;
                    }

                    $.ajax({
                        url: route('profileReferenceOptions.bulkDestroy', [scope, type]),
                        type: 'DELETE',
                        data: { ids },
                        success: function (result) {
                            if (result.success) {
                                displaySuccessMessage(result.message);
                                location.reload();
                            }
                        },
                        error: function (result) {
                            displayErrorMessage(result.responseJSON.message);
                        },
                    });
                });
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
