@extends('layouts.app')

@section('title')
    Archived Leads
@endsection

@section('content')
    <div class="container-fluid">
        @include('flash::message')
        <div class="mb-5 d-flex justify-content-end">
            <a href="{{ route('consultation-leads.index') }}" class="btn btn-primary">Back</a>
        </div>
        <div class="d-flex flex-column">
            <livewire:consultation-lead-table :archived="true" lazy/>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function confirmLeadAction(options) {
                function sendRequest() {
                    $.ajax({
                        url: options.url,
                        type: options.type || 'POST',
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                displaySuccessMessage(response.message);
                                Livewire.dispatch('refreshDatatable');
                            }
                        },
                        error: function (result) {
                            displayErrorMessage(result.responseJSON.message);
                        },
                    });
                }

                if (typeof swal === 'undefined') {
                    if (confirm(options.title + "\n" + options.text)) {
                        sendRequest();
                    }

                    return;
                }

                swal({
                    title: options.title,
                    text: options.text,
                    icon: 'warning',
                    buttons: {
                        confirm: options.confirmButtonText,
                        cancel: 'Cancel',
                    },
                    reverseButtons: true,
                    confirmButtonColor: options.confirmButtonColor || '#198754',
                    cancelButtonColor: '#ADB5BD',
                }).then(function (confirmed) {
                    if (confirmed) {
                        sendRequest();
                    }
                });
            }

            listenChange('#consultationLeadStatusFilter', function () {
                Livewire.dispatch('changeConsultationLeadStatusFilter', { status: $(this).val() });
            });

            listenChange('#consultationLeadCategoryFilter', function () {
                Livewire.dispatch('changeConsultationLeadCategoryFilter', { companyCategoryId: $(this).val() });
            });

            listenClick('#consultationLeadFilterReset', function () {
                $('#consultationLeadStatusFilter,#consultationLeadCategoryFilter').val('').change();
                Livewire.dispatch('resetConsultationLeadFilters');
            });

            $(document).off('click.consultationLeadForceDelete').on('click.consultationLeadForceDelete', '.consultation-lead-force-delete-btn', function (event) {
                event.preventDefault();
                event.stopPropagation();

                confirmLeadAction({
                    url: route('consultation-leads.force-destroy', event.currentTarget.dataset.id),
                    type: 'DELETE',
                    title: 'Delete Lead Permanently?',
                    text: 'This lead cannot be restored after permanent delete.',
                    confirmButtonText: 'Yes, Delete',
                    confirmButtonColor: '#F62947',
                });
            });

            $(document).off('click.consultationLeadRestore').on('click.consultationLeadRestore', '.consultation-lead-restore-btn', function (event) {
                event.preventDefault();
                event.stopPropagation();

                confirmLeadAction({
                    url: route('consultation-leads.restore', event.currentTarget.dataset.id),
                    title: 'Restore Lead?',
                    text: 'This lead will move back to Leads.',
                    confirmButtonText: 'Yes, Restore',
                });
            });
        });
    </script>
@endsection
