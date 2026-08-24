@extends('candidate.layouts.app')
@section('title')
    {{ __('messages.favourite_companies') }}
@endsection
@section('content')
    <div class="d-flex flex-column candidate-favourite-companies-page">
        <livewire:favourite-company-table lazy/>
    </div>
@endsection
