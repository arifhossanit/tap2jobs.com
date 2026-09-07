@extends('layouts.app')
@section('title', 'Government Jobs')
@section('content')
<div class="container-fluid">
    @include('flash::message')
    <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center mb-5">
        <form class="d-flex gap-2" method="GET"><input class="form-control" name="search" value="{{ request('search') }}" placeholder="Search circulars"><button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button></form>
        <a href="{{ route('admin.government-jobs.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Add Government Job</a>
    </div>
    <div class="card"><div class="card-body p-0"><div class="table-responsive"><table class="table table-striped align-middle mb-0">
        <thead><tr><th>Title</th><th>Organization</th><th>Source</th><th>Published</th><th>Deadline</th><th>Status</th><th class="text-end">Action</th></tr></thead>
        <tbody>@forelse($governmentJobs as $job)<tr>
            <td>{{ $job->title }}</td><td>{{ $job->organization_name }}</td><td>{{ $job->source_name ?: 'N/A' }}</td>
            <td>{{ $job->published_at?->format('d M Y') ?: 'N/A' }}</td><td>{{ $job->application_deadline?->format('d M Y') ?: 'N/A' }}</td>
            <td><span class="badge bg-{{ $job->is_published ? 'success' : 'secondary' }}">{{ $job->is_published ? 'Published' : 'Draft' }}</span></td>
            <td class="text-end"><a href="{{ route('front.government-jobs.show', $job) }}" target="_blank" class="btn btn-sm btn-light-info" title="View"><i class="fas fa-eye"></i></a> <a href="{{ route('admin.government-jobs.edit', $job) }}" class="btn btn-sm btn-light-primary" title="Edit"><i class="fas fa-edit"></i></a>
                <form method="POST" action="{{ route('admin.government-jobs.destroy', $job) }}" class="d-inline" onsubmit="return confirm('Delete this government job?')">@csrf @method('DELETE')<button class="btn btn-sm btn-light-danger" title="Delete"><i class="fas fa-trash"></i></button></form></td>
        </tr>@empty<tr><td colspan="7" class="text-center py-8">No government jobs found.</td></tr>@endforelse</tbody>
    </table></div></div></div><div class="mt-4">{{ $governmentJobs->links() }}</div>
</div>
@endsection
