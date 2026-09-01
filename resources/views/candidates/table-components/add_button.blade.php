@php
    $query = [];

    if ((string) $component->status !== (string) \App\Models\Candidate::ALL) {
        $query['status'] = $component->status;
    }

    if ((string) $component->immediate !== (string) \App\Models\Candidate::ALL) {
        $query['immediate'] = $component->immediate;
    }
@endphp

<div class="d-flex align-items-center gap-2 flex-wrap">
    @if(Auth::user()->hasRole('Admin'))
        <div class="btn-group flex-nowrap candidate-export-actions" role="group" aria-label="Candidate export actions">
            <a href="{{ route('candidates.export', ['format' => 'csv'] + $query) }}"
               class="btn btn-primary"
               title="Export CSV"
               data-bs-toggle="tooltip">
                <i class="fa-solid fa-file-csv"></i>
            </a>
            <a href="{{ route('candidates.export', ['format' => 'excel'] + $query) }}"
               class="btn btn-success"
               title="Export Excel"
               data-bs-toggle="tooltip">
                <i class="fa-solid fa-file-excel"></i>
            </a>
            <a href="{{ route('candidates.export', ['format' => 'pdf'] + $query) }}"
               class="btn btn-danger"
               title="Export PDF"
               data-bs-toggle="tooltip">
                <i class="fa-solid fa-file-pdf"></i>
            </a>
            <a href="{{ route('candidates.print', $query) }}"
               class="btn"
               style="background-color: #6f42c1; color: #fff;"
               target="_blank"
               title="Print"
               data-bs-toggle="tooltip">
                <i class="fa-solid fa-print"></i>
            </a>
        </div>
    @endif

    <div class="menu-item">
        <a href="{{ route('candidates.create') }}" type="button" class="btn btn-primary">
            {{ __('messages.common.add') }}
        </a>
    </div>
</div>

<style>
    .candidate-export-actions .btn {
        align-items: center;
        display: inline-flex;
        height: 42px;
        justify-content: center;
        padding: 0;
        width: 42px;
    }

    .candidate-export-actions .btn i {
        font-size: 18px !important;
    }
</style>
