@extends('layouts.app')
@section('title')
    {{ __('messages.education_boards') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            <livewire:education-board-table lazy/>
        </div>
        @include('education_boards.add_modal')
        @include('education_boards.edit_modal')
    </div>
@endsection
