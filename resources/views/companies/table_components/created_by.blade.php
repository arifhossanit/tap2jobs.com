@php
    $badgeClass = $row->created_by === \App\Models\Company::CREATED_BY_ADMIN_DEMO
        ? 'bg-light-info'
        : ($row->created_by === \App\Models\Company::CREATED_BY_EMPLOYER ? 'bg-light-success' : 'bg-light-primary');
@endphp

<div class="text-center">
    <span class="badge {{ $badgeClass }}">{{ $row->created_by_label }}</span>
</div>
