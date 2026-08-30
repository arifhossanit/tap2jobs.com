<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Contact</th>
        <th>Company</th>
        <th>Size</th>
        <th>Category</th>
        <th>Type</th>
        <th>Lead Source</th>
        <th>Status</th>
        <th>Submitted</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($leads as $lead)
        <tr>
            <td>{{ $lead->name }}</td>
            <td>
                {{ $lead->phone }}
                @if ($lead->email)
                    <br>{{ $lead->email }}
                @endif
            </td>
            <td>{{ $lead->company_name }}</td>
            <td>{{ $lead->companySize?->size }}</td>
            <td>{{ $lead->companyCategory?->name ?: $lead->companySize?->companyCategory?->name }}</td>
            <td>{{ $lead->consultation_type_label }}</td>
            <td>{{ $leadSource }}</td>
            <td>{{ $lead->status_label }}</td>
            <td>{{ $lead->created_at?->format('d M Y h:i A') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
