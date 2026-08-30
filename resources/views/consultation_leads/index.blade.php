@extends('layouts.app')

@section('title')
    Leads
@endsection

@section('content')
    <div class="container-fluid">
        @include('flash::message')
        <div class="d-flex flex-column">
            <livewire:consultation-lead-table lazy/>
        </div>
    </div>

    @include('consultation_leads.show_modal')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function text(value) {
                return value || 'N/A';
            }

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

            $(document).off('click.consultationLeadView').on('click.consultationLeadView', '.consultation-lead-view-btn', function (event) {
                event.preventDefault();
                event.stopPropagation();

                $.ajax({
                    url: route('consultation-leads.show', event.currentTarget.dataset.id),
                    type: 'GET',
                    success: function (result) {
                        if (!result.success) {
                            return;
                        }

                        var lead = result.data;
                        $('#consultationLeadId').val(lead.id);
                        $('#consultationLeadName').text(text(lead.name));
                        $('#consultationLeadPhone').text(text(lead.phone));
                        $('#consultationLeadEmail').text(text(lead.email));
                        $('#consultationLeadCompany').text(text(lead.company_name));
                        $('#consultationLeadDesignation').text(text(lead.designation));
                        $('#consultationLeadWebsite').text(text(lead.company_website));
                        $('#consultationLeadSize').text(text(lead.company_size ? lead.company_size.size : null));
                        $('#consultationLeadCategory').text(text(lead.company_category ? lead.company_category.name : null));
                        $('#consultationLeadType').text(text(lead.consultation_type_label));
                        $('#consultationLeadContactMethod').text(text(lead.preferred_contact_method_label));
                        $('#consultationLeadContactTime').text(text(lead.preferred_contact_time));
                        $('#consultationLeadMessage').text(text(lead.message));
                        $('#consultationLeadAd').text(text(lead.ad ? lead.ad.title : null));
                        $('#consultationLeadSource').text(text(lead.source_page));
                        $('#consultationLeadUtm').text([lead.utm_source, lead.utm_medium, lead.utm_campaign].filter(Boolean).join(' / ') || 'N/A');
                        $('#consultationLeadStatus').val(lead.status);
                        $('#consultationLeadNotes').val(lead.admin_notes || '');
                        $('#showConsultationLeadModal').appendTo('body').modal('show');
                    },
                    error: function (result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                });
            });

            listenSubmit('#consultationLeadUpdateForm', function (event) {
                event.preventDefault();
                processingBtn('#consultationLeadUpdateForm', '#consultationLeadBtnSave', 'loading');
                $.ajax({
                    url: route('consultation-leads.update', $('#consultationLeadId').val()),
                    type: 'PUT',
                    data: $(this).serialize(),
                    success: function (result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            $('#showConsultationLeadModal').modal('hide');
                            location.reload();
                        }
                    },
                    error: function (result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                    complete: function () {
                        processingBtn('#consultationLeadUpdateForm', '#consultationLeadBtnSave');
                    },
                });
            });

            $(document).off('click.consultationLeadArchive').on('click.consultationLeadArchive', '.consultation-lead-delete-btn', function (event) {
                event.preventDefault();
                event.stopPropagation();

                confirmLeadAction({
                    url: route('consultation-leads.destroy', event.currentTarget.dataset.id),
                    type: 'DELETE',
                    title: 'Archive Lead?',
                    text: 'This lead will move to Archived Leads.',
                    confirmButtonText: 'Yes, Archive',
                });
            });
        });
    </script>
@endsection
