@extends('front_web.layouts.app')
@section('title', 'Government Jobs')
@section('content')
<section class="py-5 bg-light"><div class="container">
    <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center mb-4">
        <div><h1 class="fs-2 mb-1">Government Jobs</h1><p class="text-muted mb-0">Latest government job circulars</p></div>
        <form method="GET" class="d-flex gap-2"><input name="search" value="{{ request('search') }}" class="form-control" placeholder="Search organization or circular"><button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button></form>
    </div>
    <div class="row g-4">@forelse($governmentJobs as $job)<div class="col-lg-6">
        <article class="bg-white border rounded p-4 h-100">
            <a href="{{ route('front.government-jobs.show', $job) }}" class="text-decoration-none"><h2 class="fs-5 text-primary mb-2">{{ $job->title }}</h2></a>
            <h3 class="fs-6 mb-3">{{ $job->organization_name }}</h3>
            <div class="d-flex flex-wrap gap-3 text-muted fs-14">
                @if($job->source_name)<span><i class="fas fa-newspaper me-1"></i>{{ $job->source_name }}</span>@endif
                @if($job->published_at)<span><i class="fas fa-calendar me-1"></i>{{ $job->published_at->format('d M Y') }}</span>@endif
                @if($job->application_deadline)<span class="ms-lg-auto"><strong>Deadline:</strong> {{ $job->application_deadline->format('d M Y') }}</span>@endif
            </div>
        </article>
    </div>@empty<div class="col-12"><div class="bg-white border rounded p-5 text-center">No government job circulars found.</div></div>@endforelse</div>
    <div class="mt-4">{{ $governmentJobs->links() }}</div>
</div></section>
@endsection
