@extends('layouts.app')
@section('title')
    Edit Government Job
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-5">
            <h1 class="mb-0">@yield('title')</h1>
            <div class="text-end mt-4 mt-md-0">
                <a href="{{ route('admin.government-jobs.index') }}" class="btn btn-outline-primary">
                    {{ __('messages.common.back') }}
                </a>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        @include('layouts.flash-toasts')
        <div class="row">
            <div class="col-12">@include('layouts.errors')</div>
        </div>
        <div class="card job-create-form-card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.government-jobs.update', $governmentJob) }}" enctype="multipart/form-data">
                    @method('PUT')
                    @include('government_jobs.partials.form')
                </form>
            </div>
        </div>
    </div>
@endsection
