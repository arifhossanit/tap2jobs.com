@extends('candidate.layouts.app')
@section('title')
    {{ __('messages.favourite_jobs') }}
@endsection
@section('content')
    <div class="d-flex flex-column candidate-favourite-jobs-page">
        <livewire:favorite-jobs/>
    </div>
@endsection
@push('scripts')
    {{--    <script src="{{mix('assets/js/candidate/favourite_jobs.js')}}"></script>--}}
@endpush
