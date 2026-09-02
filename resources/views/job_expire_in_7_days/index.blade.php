@extends('layouts.app')
@section('title')
    Expire in 7 days
@endsection
@include('flash::message')
@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column ">
        @include('flash::message')
        <livewire:job-table expiry-alert="expire_in_7_days" lazy/>
    </div>
</div>
@endsection
