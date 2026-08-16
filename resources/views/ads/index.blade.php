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
    {{ Form::hidden('ad_choose_media_text', __('messages.ad.choose_media'), ['id' => 'adChooseMediaText']) }}
    {{ Form::hidden('ad_no_media_selected_text', __('messages.ad.no_media_selected'), ['id' => 'adNoMediaSelectedText']) }}
    {{ Form::hidden('view', __('messages.common.view'), ['id' => 'view']) }}
    {{ Form::hidden('ad-extension-message', __('messages.image_slider.image_extension_message'), ['id' => 'adExtensionMessage']) }}
    <script src="{{ asset('assets/js/ads/ads.js') }}"></script>
@endsection
