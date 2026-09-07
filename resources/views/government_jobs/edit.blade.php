@extends('layouts.app')
@section('title', 'Edit Government Job')
@section('content')
<div class="container-fluid">
    <div class="card"><div class="card-header"><h3 class="card-title">Edit Government Job</h3></div>
        <div class="card-body"><form method="POST" action="{{ route('admin.government-jobs.update', $governmentJob) }}" enctype="multipart/form-data">@method('PUT') @include('government_jobs.partials.form')</form></div>
    </div>
</div>
@endsection
