<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Contact</th>
        <th>Company</th>
        <th>Category</th>
        <th>Type</th>
        <th>Lead Type</th>
        <th>Lead Source</th>
        <th>Status</th>
        <th>Submitted</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($leads as $lead)
        <tr>
            <td>{{ $lead->name ?: 'N/A' }}</td>
            <td>
                {{ $lead->phone ?: 'N/A' }}
                @if ($lead->email)
                    <br>{{ $lead->email }}
                @endif
            </td>
            <td>{{ $lead->company_name ?: 'N/A' }}</td>
            <td>{{ $lead->company_category_name ?: $lead->companyCategory?->name ?: $lead->companySize?->companyCategory?->name ?: 'N/A' }}</td>
            <td>{{ $lead->consultation_type ? $lead->consultation_type_label : 'N/A' }}</td>
            <td>{{ $lead->lead_from_label }}</td>
            <td>{{ $lead->source_page ?: $leadSource ?: 'N/A' }}</td>
            <td>{{ $lead->status_label ?: 'N/A' }}</td>
            <td>{{ $lead->created_at?->format('d M Y h:i A') ?: 'N/A' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
