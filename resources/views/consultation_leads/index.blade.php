@extends('layouts.app')

@section('title')
    Consultation Leads
@endsection

@section('content')
    <div class="container-fluid">
        @include('flash::message')
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <h3 class="fw-bold m-0">Consultation Leads</h3>
                </div>
                <div class="card-toolbar">
                    <form method="GET" class="d-flex gap-2">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            @foreach ($statuses as $value => $label)
                                <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <select name="company_category_id" class="form-select">
                            <option value="">All Categories</option>
                            @foreach ($companyCategories as $id => $name)
                                <option value="{{ $id }}" {{ (string) request('company_category_id') === (string) $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </form>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Company</th>
                            <th>Size</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th class="text-center">{{ __('messages.common.action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($consultationLeads as $lead)
                            <tr>
                                <td>{{ $lead->name }}</td>
                                <td>
                                    <div>{{ $lead->phone }}</div>
                                    <div class="text-muted small">{{ $lead->email }}</div>
                                </td>
                                <td>{{ $lead->company_name ?: '-' }}</td>
                                <td>{{ $lead->companySize?->size ?: '-' }}</td>
                                <td>{{ $lead->companyCategory?->name ?: $lead->companySize?->companyCategory?->name ?: '-' }}</td>
                                <td>{{ $lead->consultation_type_label }}</td>
                                <td>{{ $lead->status_label }}</td>
                                <td>{{ $lead->created_at?->format('d M Y h:i A') }}</td>
                                <td class="text-center">
                                    <button type="button"
                                            class="btn px-2 text-primary fs-3 consultation-lead-view-btn"
                                            data-id="{{ $lead->id }}"
                                            title="{{ __('messages.common.view') }}"
                                            data-bs-toggle="tooltip">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <button type="button"
                                            class="btn px-2 text-danger fs-3 consultation-lead-delete-btn"
                                            data-id="{{ $lead->id }}"
                                            title="{{ __('messages.common.delete') }}"
                                            data-bs-toggle="tooltip">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">{{ __('messages.common.no_data_available') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $consultationLeads->links() }}
            </div>
        </div>
    </div>

    @include('consultation_leads.show_modal')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var statuses = @json($statuses);

            function text(value) {
                return value || '-';
            }

            listenClick('.consultation-lead-view-btn', function (event) {
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
                        $('#consultationLeadUtm').text([lead.utm_source, lead.utm_medium, lead.utm_campaign].filter(Boolean).join(' / ') || '-');
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

            listenClick('.consultation-lead-delete-btn', function (event) {
                deleteItem(route('consultation-leads.destroy', event.currentTarget.dataset.id), 'Consultation Lead', null, 'location.reload()');
            });
        });
    </script>
@endsection
