<a href="{{ route('company.show', $row->id) }}" class="text-decoration-none fs-6">
    {{ $row->company_name ?: 'N/A' }}
</a>
