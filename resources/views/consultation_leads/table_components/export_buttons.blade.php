@php
    $query = [];

    if ($component->status !== '') {
        $query['status'] = $component->status;
    }

    if ($component->companyCategoryId !== '') {
        $query['company_category_id'] = $component->companyCategoryId;
    }
@endphp

<div class="btn-group flex-nowrap consultation-lead-export-actions" role="group" aria-label="Consultation lead export actions">
    <a href="{{ route('consultation-leads.export', ['format' => 'csv'] + $query) }}"
       class="btn btn-primary"
       title="Export CSV"
       data-bs-toggle="tooltip">
        <i class="fa-solid fa-file-csv"></i>
    </a>
    <a href="{{ route('consultation-leads.export', ['format' => 'excel'] + $query) }}"
       class="btn btn-success"
       title="Export Excel"
       data-bs-toggle="tooltip">
        <i class="fa-solid fa-file-excel"></i>
    </a>
    <a href="{{ route('consultation-leads.export', ['format' => 'pdf'] + $query) }}"
       class="btn btn-danger"
       title="Export PDF"
       data-bs-toggle="tooltip">
        <i class="fa-solid fa-file-pdf"></i>
    </a>
    <a href="{{ route('consultation-leads.print', $query) }}"
       class="btn"
       style="background-color: #6f42c1; color: #fff;"
       target="_blank"
       title="Print"
       data-bs-toggle="tooltip">
        <i class="fa-solid fa-print"></i>
    </a>
</div>

<style>
    .consultation-lead-export-actions .btn {
        align-items: center;
        display: inline-flex;
        height: 42px;
        justify-content: center;
        padding: 0;
        width: 42px;
    }
    
    .consultation-lead-export-actions .btn i {
        font-size: 18px !important;
    }
</style>
