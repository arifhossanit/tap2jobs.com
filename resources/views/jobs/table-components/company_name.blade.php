@if ($row->company)
    <a href="{{ route('company.show', $row->company->id) }}" class="text-decoration-none">
        {{ $row->company->company_name ?: 'N/A' }}
    </a>
@else
    N/A
@endif