@extends('layouts.app')
@section('title')
    Company Categories
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="fw-bold m-0">Company Categories</h3>
                    </div>
                    <div class="card-toolbar">
                        <a class="btn btn-primary addCompanyCategoryModal">{{ __('messages.marital_status.add') }}</a>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Sort</th>
                                <th>Status</th>
                                <th>Company Sizes</th>
                                <th class="text-center">{{ __('messages.common.action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($companyCategories as $companyCategory)
                                <tr>
                                    <td>{{ $companyCategory->name }}</td>
                                    <td>{{ $companyCategory->sort_order }}</td>
                                    <td>{{ $companyCategory->is_active ? __('messages.common.active') : __('messages.common.deactive') }}</td>
                                    <td>{{ $companyCategory->company_sizes_count }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center">
                                            <a href="javascript:void(0)"
                                               title="{{ __('messages.common.edit') }}"
                                               class="btn px-2 text-primary fs-3 company-category-edit-btn"
                                               data-id="{{ $companyCategory->id }}"
                                               data-bs-toggle="tooltip">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <button type="button"
                                                    title="{{ __('messages.common.delete') }}"
                                                    class="company-category-delete-btn btn px-2 text-danger fs-3"
                                                    data-id="{{ $companyCategory->id }}"
                                                    data-bs-toggle="tooltip">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
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
    </div>

    @include('company_categories.add_modal')
    @include('company_categories.edit_modal')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            listenClick('.addCompanyCategoryModal', function () {
                $('#addCompanyCategoryModal').appendTo('body').modal('show');
            });

            listenClick('.company-category-edit-btn', function (event) {
                $.ajax({
                    url: route('companyCategories.edit', event.currentTarget.dataset.id),
                    type: 'GET',
                    success: function (result) {
                        if (result.success) {
                            $('#editCompanyCategoryId').val(result.data.id);
                            $('#editCompanyCategoryName').val(result.data.name);
                            $('#editCompanyCategorySortOrder').val(result.data.sort_order);
                            $('#editCompanyCategoryIsActive').prop('checked', Boolean(result.data.is_active));
                            $('#editCompanyCategoryModal').appendTo('body').modal('show');
                        }
                    },
                    error: function (result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                });
            });

            listenClick('.company-category-delete-btn', function (event) {
                deleteItem(route('companyCategories.destroy', event.currentTarget.dataset.id), 'Company Category', null, 'location.reload()');
            });

            listenHiddenBsModal('#addCompanyCategoryModal', function () {
                resetModalForm('#addCompanyCategoryForm', '#companyCategoryValidationErrorsBox');
                $('#companyCategoryIsActive').prop('checked', true);
            });

            listenHiddenBsModal('#editCompanyCategoryModal', function () {
                resetModalForm('#editCompanyCategoryForm', '#editCompanyCategoryValidationErrorsBox');
            });

            listenSubmit('#addCompanyCategoryForm', function (event) {
                event.preventDefault();
                processingBtn('#addCompanyCategoryForm', '#companyCategoryBtnSave', 'loading');
                $.ajax({
                    url: route('companyCategories.store'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            $('#addCompanyCategoryModal').modal('hide');
                            location.reload();
                        }
                    },
                    error: function (result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                    complete: function () {
                        processingBtn('#addCompanyCategoryForm', '#companyCategoryBtnSave');
                    },
                });
            });

            listenSubmit('#editCompanyCategoryForm', function (event) {
                event.preventDefault();
                processingBtn('#editCompanyCategoryForm', '#editCompanyCategoryBtnSave', 'loading');
                $.ajax({
                    url: route('companyCategories.update', $('#editCompanyCategoryId').val()),
                    type: 'PUT',
                    data: $(this).serialize(),
                    success: function (result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            $('#editCompanyCategoryModal').modal('hide');
                            location.reload();
                        }
                    },
                    error: function (result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                    complete: function () {
                        processingBtn('#editCompanyCategoryForm', '#editCompanyCategoryBtnSave');
                    },
                });
            });
        });
    </script>
@endsection
