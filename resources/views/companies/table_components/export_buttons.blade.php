@php
    $query = [];

    if ((string) $component->featured !== (string) \App\Models\Company::ALL) {
        $query['featured'] = $component->featured;
    }

    if ((string) $component->status !== (string) \App\Models\Company::ALL) {
        $query['status'] = $component->status;
    }

    if ($component->createdBy !== '') {
        $query['created_by'] = $component->createdBy;
    }
@endphp

<div class="d-flex align-items-center gap-2 flex-wrap">
    <div class="btn-group flex-nowrap company-export-actions" role="group" aria-label="Employer export actions">
        <a href="{{ route('company.export', ['format' => 'csv'] + $query) }}"
           class="btn btn-primary"
           title="Export CSV"
           data-bs-toggle="tooltip">
            <i class="fa-solid fa-file-csv"></i>
        </a>
        <a href="{{ route('company.export', ['format' => 'excel'] + $query) }}"
           class="btn btn-success"
           title="Export Excel"
           data-bs-toggle="tooltip">
            <i class="fa-solid fa-file-excel"></i>
        </a>
        <a href="{{ route('company.export', ['format' => 'pdf'] + $query) }}"
           class="btn btn-danger"
           title="Export PDF"
           data-bs-toggle="tooltip">
            <i class="fa-solid fa-file-pdf"></i>
        </a>
        <a href="{{ route('company.print', $query) }}"
           class="btn"
           style="background-color: #6f42c1; color: #fff;"
           target="_blank"
           title="Print"
           data-bs-toggle="tooltip">
            <i class="fa-solid fa-print"></i>
        </a>
    </div>

    <div class="menu-item">
        <a href="{{ route('company.create') }}" type="button" class="btn btn-primary">
            {{ __('messages.common.add') }}
        </a>
    </div>
</div>

<style>
    .company-export-actions .btn {
        align-items: center;
        display: inline-flex;
        height: 42px;
        justify-content: center;
        padding: 0;
        width: 42px;
    }

    .company-export-actions .btn i {
        font-size: 18px !important;
    }
</style>
