<table>
    <thead>
    <tr>
        <th>Company Name</th>
        <th>Employer Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Featured</th>
        <th>Email Verified</th>
        <th>Status</th>
        <th>Created By</th>
        <th>Last Change By</th>
        <th>Industry</th>
        <th>Company Size</th>
        <th>Website</th>
        <th>Location</th>
        <th>Created At</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($companies as $company)
        <tr>
            <td>{{ $company->company_name ?: 'N/A' }}</td>
            <td>{{ $company->contact_person_name ?: ($company->user?->full_name ?: 'N/A') }}</td>
            <td>{{ $company->user?->email ?: 'N/A' }}</td>
            <td>{{ $company->user?->phone ?: 'N/A' }}</td>
            <td>{{ $company->featured ? __('messages.common.yes') : __('messages.common.no') }}</td>
            <td>{{ $company->user?->email_verified_at ? __('messages.common.yes') : __('messages.common.no') }}</td>
            <td>{{ $company->user?->is_active ? __('messages.common.active') : __('messages.common.de_active') }}</td>
            <td>{{ $company->created_by_label }}</td>
            <td>{{ $company->admin?->full_name ?: 'N/A' }}</td>
            <td>{{ $company->industry?->name ?: 'N/A' }}</td>
            <td>{{ $company->companySize?->size ?: 'N/A' }}</td>
            <td>{{ $company->website ?: 'N/A' }}</td>
            <td>{{ $company->location ?: 'N/A' }}</td>
            <td>{{ $company->created_at?->format('d M Y h:i A') ?: 'N/A' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
