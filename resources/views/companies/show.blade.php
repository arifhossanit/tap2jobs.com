@extends('layouts.app')
@section('title')
    {{ __('messages.company.employer_details') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-5">
            <h1 class="mb-0">@yield('title')</h1>
            <div class="text-end mt-4 mt-md-0">
                <a href="{{ route('company.edit',$company->id) }}" class="btn btn-primary me-4">
                    <i class="fas fa-edit me-2"></i>{{ __('messages.common.edit') }}
                </a>
                <a href="{!! URL::previous() !!}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('messages.common.back') }}
                </a>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="card">
                <div class="card-body p-0">
                    @include('companies.show_fields')
                </div>
            </div>
        </div>
    </div>
@endsection
