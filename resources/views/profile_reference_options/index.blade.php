@extends('layouts.app')
@section('title')
    {{ $title }}
@endsection
@section('content')
    @php
        $useEducationBoardLayout = filled($dedicatedRouteName);
    @endphp
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            @if($useEducationBoardLayout)
                <livewire:profile-reference-option-table :scope="$scope" :type="$type" lazy/>
            @else
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
                        <table class="table table-striped">
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
                                <th class="text-center">{{ __('messages.common.action') }}</th>
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
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center">
                                            <a href="javascript:void(0)"
                                               title="{{ __('messages.common.edit') }}"
                                               class="btn px-2 text-primary fs-3 profile-reference-option-edit-btn"
                                               data-id="{{ $option->id }}"
                                               data-bs-toggle="tooltip">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <button type="button"
                                                    title="{{ __('messages.common.delete') }}"
                                                    class="profile-reference-option-delete-btn btn px-2 text-danger fs-3"
                                                    data-id="{{ $option->id }}"
                                                    data-bs-toggle="tooltip">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
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
            @endif
        </div>
        @include('profile_reference_options.add_modal')
        @include('profile_reference_options.edit_modal')
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const scope = @json($scope);
            const type = @json($type);
            const useEducationBoardLayout = @json($useEducationBoardLayout);
            const dedicatedRouteName = @json($dedicatedRouteName);
            const routeFor = function (action, id = null) {
                if (dedicatedRouteName) {
                    if (id === null) {
                        return route(dedicatedRouteName + '.' + action);
                    }

                    return route(dedicatedRouteName + '.' + action, id);
                }

                if (id === null) {
                    return route('profileReferenceOptions.' + action, [scope, type]);
                }

                return route('profileReferenceOptions.' + action, [scope, type, id]);
            };
            const bulkDeleteBtn = $('#profileReferenceBulkDeleteBtn');
            const bulkActionsDropdown = $('#profileReferenceBulkActionsDropdown');
            const selectedCountBadge = $('#profileReferenceSelectedCount');
            const selectAllCheckbox = $('#profileReferenceSelectAll');

            if (! useEducationBoardLayout) {
            const selectedProfileReferenceOptionIds = function () {
                return $('.profile-reference-option-checkbox:checked').map(function () {
                    return this.value;
                }).get();
            };

            const refreshProfileReferenceBulkDeleteState = function () {
                const selectedCount = selectedProfileReferenceOptionIds().length;
                bulkDeleteBtn.prop('disabled', selectedCount === 0);

                if (useEducationBoardLayout) {
                    bulkActionsDropdown.prop('disabled', selectedCount === 0);
                    selectedCountBadge.text(selectedCount).toggleClass('d-none', selectedCount === 0);
                } else {
                    bulkDeleteBtn.toggleClass('d-none', selectedCount === 0);
                }

                const visibleCheckboxes = $('.profile-reference-option-checkbox:visible');
                const selectedVisibleCount = visibleCheckboxes.filter(':checked').length;
                selectAllCheckbox.prop(
                    'checked',
                    visibleCheckboxes.length > 0
                    && selectedVisibleCount === visibleCheckboxes.length
                );
            };

            listenKeyup('#profileReferenceSearch', function () {
                const search = this.value.toLowerCase().trim();

                $('#profileReferenceOptionsTable tbody tr[data-search]').each(function () {
                    const row = $(this);
                    const matched = row.data('search').includes(search);

                    row.toggle(matched);

                    if (!matched) {
                        row.find('.profile-reference-option-checkbox').prop('checked', false);
                    }
                });

                refreshProfileReferenceBulkDeleteState();
            });

            listenChange('#profileReferenceSelectAll', function () {
                const checkboxes = useEducationBoardLayout
                    ? $('.profile-reference-option-checkbox:visible')
                    : $('.profile-reference-option-checkbox');

                checkboxes.prop('checked', this.checked);
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
                        url: routeFor('bulkDestroy'),
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
            }

            listenClick('.addProfileReferenceOptionModal', function () {
                $('#addProfileReferenceOptionModal').appendTo('body').modal('show');
            });

            listenClick('.profile-reference-option-edit-btn', function (event) {
                const optionId = event.currentTarget.dataset.id;
                $.ajax({
                    url: routeFor('edit', optionId),
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
                    routeFor('destroy', event.currentTarget.dataset.id),
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
                    url: routeFor('store'),
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
                    url: routeFor('update', optionId),
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
