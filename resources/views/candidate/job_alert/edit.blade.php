@extends('candidate.layouts.app')
@section('title')
    {{ __('messages.job.job_alert') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-5 candidate-job-alert-toolbar">
            <h1 class="mb-0 candidate-job-alert-title">@yield('title')</h1>
        </div>
    </div>
@endsection
@section('content')
    @include('flash::message')
    @include('layouts.errors')
    <div class="card candidate-job-alert-card">
        <div class="card-body">
            {{ Form::open(['route' => 'candidate.job.alert.update']) }}
            <div
                class="candidate-job-alert-row candidate-job-alert-row--primary col-lg-12 col-md-6 mb-5 d-flex justify-content-start form-check form-switch">
                <label class="candidate-job-alert-switch">
                    <input type="checkbox" name="job_alert" value="1"
                           class="form-check-input" {{ ($candidate->job_alert) ? 'checked' : '' }}>
                    <span class=""></span>
                </label>
                <span class="candidate-job-alert-label fs-6 text-gray-600 {{ checkLanguageSession() == 'ar' ? 'me-15' : '' }}">{{ __('messages.candidate.job_alert_message') }}</span>
            </div>
            <div class="form-group candidate-job-alert-options {{ checkLanguageSession() == 'ar' ? 'me-19' : 'ms-19' }}">
                <div class="custom-switches-stacked">
                    @foreach($jobTypes as $jobType)
                        <div class="candidate-job-alert-row col-lg-12 col-md-6 mb-2 d-flex justify-content-start form-check form-switch">
                            <label
                                class="candidate-job-alert-switch">
                                <input type="checkbox" name="job_types[]" value="{{ $jobType->id }}"
                                       class="form-check-input cursor-pointer" {{ in_array($jobType->id,$jobAlerts) ? 'checked' : '' }}>
                                <span class="custom-switch-indicator"></span>
                            </label>
                            <span
                                class="candidate-job-alert-label fs-6 text-gray-600 {{ checkLanguageSession() == 'ar' ? 'me-15' : '' }}">{{ htmlspecialchars_decode($jobType->name) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- Submit Field -->
            <div class="separator my-5"></div>
            <div class="candidate-job-alert-actions d-flex justify-content-end">
                {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-3 btnSave',]) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection

@section('page_css')
    <style>
        .candidate-job-alert-card .card-body {
            padding: 28px;
        }

        .candidate-job-alert-row {
            align-items: center;
            display: grid !important;
            gap: 14px;
            grid-template-columns: 58px minmax(0, 1fr);
            min-width: 0;
            padding-left: 0;
        }

        .candidate-job-alert-switch {
            align-items: flex-start;
            display: flex;
            flex: 0 0 auto;
            margin: 0;
            padding-top: 0;
            width: 58px;
        }

        .candidate-job-alert-row .form-check-input {
            background-color: #e4e9f1;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-5 -5 10 10'%3e%3ccircle r='4.3' fill='%23ffffff'/%3e%3c/svg%3e") !important;
            background-position: left 4px center;
            background-size: 22px 22px;
            border: 1px solid #c8d2df;
            box-shadow: inset 0 1px 3px rgba(15, 23, 42, 0.12);
            cursor: pointer;
            float: none;
            height: 30px;
            margin-left: 0;
            margin-top: 0;
            transition: background-color 0.2s ease, background-position 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
            width: 54px;
        }

        .candidate-job-alert-row .form-check-input:focus {
            border-color: #209776;
            box-shadow: 0 0 0 4px rgba(32, 151, 118, 0.14);
        }

        .candidate-job-alert-row .form-check-input:checked {
            background-color: #209776;
            background-position: right 4px center;
            border-color: #209776;
            box-shadow: 0 6px 14px rgba(32, 151, 118, 0.22);
        }

        .candidate-job-alert-label {
            display: block;
            line-height: 1.55;
            margin-top: 0;
            min-width: 0;
            overflow-wrap: anywhere;
        }

        .candidate-job-alert-options {
            max-width: 760px;
        }

        .candidate-job-alert-actions .btn {
            min-width: 118px;
        }

        @media (max-width: 767.98px) {
            .candidate-front-layout-main {
                padding-bottom: 88px !important;
                padding-top: 22px !important;
            }

            .candidate-job-alert-toolbar {
                margin-bottom: 16px !important;
            }

            .candidate-job-alert-title {
                font-size: 24px;
                line-height: 1.25;
            }

            .candidate-job-alert-card {
                border-radius: 8px;
                margin-bottom: 12px;
                margin-left: 0;
                margin-right: 0;
            }

            .candidate-job-alert-card .card-body {
                padding: 20px 16px;
            }

            .candidate-job-alert-row {
                align-items: center;
                gap: 12px;
                grid-template-columns: 58px minmax(0, 1fr);
                margin-left: 0 !important;
                margin-right: 0 !important;
                padding-left: 0;
                padding-right: 0;
                width: 100%;
            }

            .candidate-job-alert-switch {
                width: 58px;
            }

            .candidate-job-alert-row .form-check-input {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            .candidate-job-alert-row--primary {
                margin-bottom: 20px !important;
            }

            .candidate-job-alert-options {
                margin-left: 0 !important;
                margin-right: 0 !important;
                max-width: none;
            }

            .candidate-job-alert-label {
                font-size: 14px !important;
            }

            .candidate-job-alert-actions {
                justify-content: stretch !important;
            }

            .candidate-job-alert-actions .btn {
                margin-right: 0 !important;
                width: 100%;
            }
        }

        @media (max-width: 360px) {
            .candidate-job-alert-card .card-body {
                padding-left: 14px;
                padding-right: 14px;
            }

            .candidate-job-alert-title {
                font-size: 22px;
            }
        }

        html[dir='rtl'] .candidate-job-alert-row {
            padding-left: 0;
            padding-right: 0;
        }

        html[dir='rtl'] .candidate-job-alert-row .form-check-input {
            float: none;
            margin-left: 0;
            margin-right: 0;
        }

        @media (max-width: 767.98px) {
            html[dir='rtl'] .candidate-job-alert-options {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
        }
    </style>
@endsection
