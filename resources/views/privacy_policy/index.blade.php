@extends('layouts.app')
@section('title')
    {{ __('messages.setting.' . $sectionName) }}
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('css/header-padding.css') }}">
@endpush
@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column">
        @include('flash::message')
        <div class="card">
            <div class="card-body">
                @include('privacy_policy.privacy_policy')
            </div>
        </div>
    </div>
</div>
@if ($sectionName === 'privacy_policy')
    {{ Form::hidden('privacyPolicyData', $privacyPolicy['privacy_policy'], ['id' => 'privacyPolicyData']) }}
@else
    {{ Form::hidden('termConditionData', $privacyPolicy['terms_conditions'], ['id' => 'termConditionData']) }}
@endif
@endsection
@push('scripts')
{{--    <script src="{{ mix('assets/js/privacy_policy/privacy_policy.js') }}"></script>--}}
@endpush
