@extends('layouts.app')
@section('title', 'Create Government Job')
@section('content')
<div class="container-fluid">
    <div class="card"><div class="card-header"><h3 class="card-title">Create Government Job</h3></div>
        <div class="card-body"><form method="POST" action="{{ route('admin.government-jobs.store') }}" enctype="multipart/form-data">@include('government_jobs.partials.form')</form></div>
    </div>
</div>
@endsection
