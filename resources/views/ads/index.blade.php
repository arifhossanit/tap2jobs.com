@extends('layouts.app')
@section('title')
    {{ __('messages.ads') }}
@endsection
@section('content')
    <div class="container-fluid">
        @include('flash::message')
        <div class="d-flex flex-column">
            <livewire:ad-table lazy/>
        </div>
    </div>
    @include('ads.add_modal')
    @include('ads.edit_modal')

    {{ Form::hidden('default_document_imageUrl', '', ['id' => 'defaultDocumentImageUrl']) }}
    {{ Form::hidden('view', __('messages.common.view'), ['id' => 'view']) }}
    {{ Form::hidden('ad-extension-message', __('messages.image_slider.image_extension_message'), ['id' => 'adExtensionMessage']) }}
    <script src="{{ asset('assets/js/ads/ads.js') }}"></script>
@endsection
