@extends('layouts.app')
@section('title')
    Government Jobs
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('layouts.flash-toasts')
            <livewire:government-job-table lazy />
        </div>
    </div>
@endsection
