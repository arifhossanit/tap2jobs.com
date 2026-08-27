@extends('candidate.layouts.app')
@section('title')
    {{ __('messages.applied_job.applied_jobs') }}
@endsection
@push('css')
    <style>
        .candidate-applied-jobs {
            min-width: 0;
        }

        .candidate-applied-jobs__toolbar .form-control,
        .candidate-applied-jobs__toolbar .select2-container {
            min-height: 44px;
            width: 100% !important;
        }

        .candidate-applied-jobs__toolbar .select2-selection--single {
            align-items: center;
            display: flex;
            min-height: 44px;
        }

        .candidate-applied-jobs__toolbar .select2-selection__arrow {
            top: 9px !important;
        }

        .candidate-applied-jobs__grid {
            margin-top: 0 !important;
        }

        .candidate-applied-job-card {
            border: 1px solid #e7edf5 !important;
            border-radius: 8px;
            min-width: 0;
        }

        .candidate-applied-job-card__header,
        .candidate-applied-job-card__heading {
            min-width: 0;
        }

        .candidate-applied-job-card__heading > div {
            min-width: 0;
            width: 100%;
        }

        .candidate-applied-job-card__title {
            max-width: none !important;
            min-width: 0;
        }

        .candidate-applied-job-card__title a {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .candidate-applied-job-card__action {
            align-items: center;
            display: inline-flex;
            flex: 0 0 36px;
            height: 36px;
            justify-content: center;
            width: 36px;
        }

        .candidate-applied-job-card .dropdown-menu {
            max-width: calc(100vw - 32px);
            min-width: 170px;
        }

        .candidate-applied-job-card__info-row {
            align-items: center;
            display: grid !important;
            gap: 12px;
            grid-template-columns: minmax(0, 1fr) minmax(0, auto);
        }

        .candidate-applied-job-card__value {
            max-width: 100%;
            overflow-wrap: anywhere;
            text-align: right;
        }

        .candidate-applied-jobs__pagination .pagination {
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 0;
            row-gap: 8px;
        }

        @media (max-width: 991.98px) {
            .candidate-applied-jobs__toolbar {
                margin-bottom: 20px !important;
            }

            .candidate-applied-job-card .card-body {
                padding: 20px !important;
            }

            #scheduleSlotBookModal .modal-dialog,
            #showModal .modal-dialog {
                margin: 16px auto !important;
                max-width: calc(100% - 32px) !important;
            }
        }

        @media (max-width: 767.98px) {
            .candidate-applied-jobs__toolbar {
                gap: 12px 0;
            }

            .candidate-applied-jobs__grid {
                --bs-gutter-y: 14px;
            }

            .candidate-applied-jobs__empty {
                margin-bottom: 32px !important;
                margin-top: 32px !important;
                padding: 0 16px;
                text-align: center;
            }

            #scheduleSlotBookModal .modal-body,
            #showModal .modal-body {
                padding: 16px !important;
            }

            #scheduleSlotBookModal .modal-footer,
            #showModal .modal-footer {
                gap: 8px;
                padding: 12px 16px !important;
            }
        }

        @media (max-width: 575.98px) {
            .candidate-applied-job-card .card-body {
                padding: 16px !important;
            }

            .candidate-applied-job-card__title a {
                font-size: 14px !important;
            }

            .candidate-applied-job-card__info-row {
                gap: 8px;
            }

            .candidate-applied-job-card__info-row > span {
                font-size: 12px;
            }

            .candidate-applied-jobs__pagination .page-link {
                align-items: center;
                display: inline-flex;
                font-size: 12px;
                justify-content: center;
                min-height: 36px;
                min-width: 36px;
                padding: 6px 9px;
            }

            #scheduleSlotBookModal .modal-dialog,
            #showModal .modal-dialog {
                margin: 8px auto !important;
                max-width: calc(100% - 16px) !important;
            }

            #scheduleSlotBookModal .modal-footer .btn,
            #showModal .modal-footer .btn {
                flex: 1 1 auto;
                margin: 0 !important;
                min-height: 42px;
            }
        }
    </style>
@endpush
@section('content')
    @include('flash::message')
    <div class="d-flex flex-column">
        {{-- @livewire('applied-jobs') --}}
        <livewire:applied-jobs/>
    </div>
    @include('candidate.applied_job.show_applied_jobs_modal')
    @include('candidate.applied_job.templates.templates')
    @include('candidate.applied_job.schedule_slot_book')

@endsection
