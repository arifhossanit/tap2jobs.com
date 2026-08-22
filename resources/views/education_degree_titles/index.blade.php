@extends('layouts.app')
@section('title')
    Degree Titles
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            <livewire:education-degree-title-table lazy/>
        </div>
        @include('education_degree_titles.add_modal')
        @include('education_degree_titles.edit_modal')
        @include('education_degree_titles.import_modal')
    </div>
@endsection
