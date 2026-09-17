@extends('layouts.app')
@section('title')
    {{ __('messages.job_categories') }}
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('css/header-padding.css') }}">
@endpush
@push('scripts')
    <link rel="stylesheet" href="{{ asset('css/tagify.css') }}">
@endpush
@section('content')
    <style>
        .job-category-tagify.tagify {
            width: 100%;
            --tags-focus-border-color: #e4e6ef;
            min-height: 95px;
            overflow-y: auto;
            padding: .45rem .55rem;
        }

        .job-category-tagify.tagify--focus {
            border-color: #ced4da;
            box-shadow: none;
        }

        .job-category-tagify .tagify__tag {
            max-width: 100%;
            overflow: hidden;
            border: 1px solid #c9dcff;
            border-radius: .35rem;
            background: #edf4ff;
            color: #2456a6;
            transition: background-color .15s ease, border-color .15s ease;
        }

        .job-category-tagify .tagify__tag > div {
            padding-right: .35rem;
            border-radius: 0;
            background: transparent;
            color: inherit;
        }

        .job-category-tagify .tagify__tag > div::before {
            display: none;
        }

        .job-category-tagify .tagify__tag:hover {
            background: #e3efff;
        }

        .job-category-tagify .tagify__tag-text {
            overflow-wrap: anywhere;
        }

        .job-category-tagify .tagify__input {
            margin: 0;
            padding: .3rem .2rem;
        }

        .job-category-tagify .tagify__tag__removeBtn {
            order: 5;
            z-index: 2;
            width: 18px;
            height: 18px;
            margin: 0 .35rem 0 0;
            border: 0;
            border-radius: .2rem;
            background: transparent;
            color: #2456a6;
            font-size: 15px;
            line-height: 1;
        }

        .job-category-tagify .tagify__tag__removeBtn:hover {
            color: #fff;
            background: #d85b69;
        }
    </style>
<div class="container-fluid">
    <div class="d-flex flex-column ">
        @include('flash::message')
        <livewire:job-category-table lazy/>
    </div>
</div>
@include('job_categories.add_modal')
@include('job_categories.edit_modal')
@include('job_categories.show_modal')
{{ Form::hidden('default-document-image-url', asset('front_web/images/job-categories.png'), ['id' => 'defaultDocumentImageUrl']) }}
{{ Form::hidden('indexJobCategory', true, ['id' => 'indexJobCategoryData']) }}
@endsection
