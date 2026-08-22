@extends('layouts.app')
@section('title')
    Major / Groups
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            <livewire:education-major-group-table lazy/>
        </div>
        @include('education_major_groups.add_modal')
        @include('education_major_groups.edit_modal')
        @include('education_major_groups.import_modal')
    </div>
@endsection
