@extends('layouts.app')
@section('title')
    {{ __('messages.expired_jobs') }}
@endsection
@include('flash::message')
@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column ">
        @include('flash::message')
        <livewire:job-expired-table :expiry-alert="request('expiry_alert')" lazy/>
    </div>
</div>
@endsection
{{--@push('scripts')--}}
{{--    <script src="{{mix('assets/js/job_expired/job_expired.js')}}"></script>--}}
{{--@endpush--}}

