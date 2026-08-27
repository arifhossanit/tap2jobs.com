@extends('candidate.layouts.app')
@section('title')
    {{ __('messages.favourite_companies') }}
@endsection
@section('css')
    <style>
        /* Favourite Companies - responsive card layout for mobile & tablet.
           Desktop (>= lg, 992px) keeps the original table design exactly. */
        @media (max-width: 991.98px) {
            .candidate-favourite-companies-page .table-responsive {
                overflow: visible;
                background: transparent;
            }

            .candidate-favourite-companies-page .laravel-livewire-table {
                width: 100%;
                border: 0;
            }

            .candidate-favourite-companies-page .laravel-livewire-table thead {
                display: none;
            }

            .candidate-favourite-companies-page .laravel-livewire-table tbody {
                display: block;
                width: 100%;
            }

            .candidate-favourite-companies-page .laravel-livewire-table tbody tr {
                display: block;
                width: 100%;
                margin-bottom: 0.9rem;
                padding: 0.5rem 0.9rem;
                border: 1px solid #e9ecef;
                border-radius: 0.5rem;
                background: #ffffff;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            }

            .candidate-favourite-companies-page .laravel-livewire-table tbody tr:last-child {
                margin-bottom: 0;
            }

            .candidate-favourite-companies-page .laravel-livewire-table tbody tr td {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 0.75rem;
                width: 100%;
                padding: 0.45rem 0;
                border: 0;
                text-align: right;
            }

            .candidate-favourite-companies-page .laravel-livewire-table tbody tr td::before {
                content: attr(data-label);
                flex-shrink: 0;
                padding-right: 0.75rem;
                font-weight: 600;
                font-size: 0.85rem;
                color: #6c757d;
            }
        }
    </style>
@endsection
@section('content')
    <div class="d-flex flex-column candidate-favourite-companies-page">
        <livewire:favourite-company-table lazy/>
    </div>
@endsection
