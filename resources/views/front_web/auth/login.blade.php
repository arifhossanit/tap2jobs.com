@extends('front_web.layouts.app')
@section('title')
    {{ __('web.login') }}
@endsection
@section('content')
    <div class="login-page">
        <!-- start hero section -->
        <section class="hero-section position-relative bg-gradient pt-15 pb-40">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-6  text-center mb-lg-0 mb-md-5 mb-sm-4 ">
                        <div class="hero-content">
                            <h1 class=" text-secondary mb-3">
                                {{ __('web.login') }}
                            </h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb  justify-content-center mb-0">
                                    <li class="breadcrumb-item "><a href="{{ route('front.home') }}"
                                            class="fs-18 text-gray">@lang('web.home') </a>
                                    </li>
                                    <li class="breadcrumb-item text-primary fs-18" aria-current="page">@lang('web.login')
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end hero section -->

        <!-- start candidate login section -->
        <section class="py-100">
            <div class="container-fluid">
                @php
                    $registerLeftAds = getActiveAdsByPosition(\App\Models\Ad::POSITION_REGISTER_LEFT, \App\Models\Ad::PAGE_CANDIDATE_LOGIN);
                    $registerRightAds = getActiveAdsByPosition(\App\Models\Ad::POSITION_REGISTER_RIGHT, \App\Models\Ad::PAGE_CANDIDATE_LOGIN);
                    $hasRegisterSideAds = $registerLeftAds->isNotEmpty() || $registerRightAds->isNotEmpty();
                @endphp
                <div class="row align-items-start justify-content-center front-auth-ad-layout">
                    @if ($hasRegisterSideAds)
                        <div class="col-xl-3 col-lg-3 d-none d-lg-block mb-4 text-start front-auth-side-ads front-auth-side-ads--left">
                            @include('front_web.common.register_side_ad', ['ads' => $registerLeftAds])
                        </div>
                        <div class="col-xl-4 col-lg-6 front-auth-form-col">
                    @else
                        <div class="col-xl-4 col-lg-6 mx-auto">
                    @endif
                        @include('flash::message')
                        <form method="POST" action="{{ route('front.login') }}" id="frontLoginForm"
                            class="py-40 px-40 bg-gray shadow rounded border">
                            <div class="row">
                                @csrf
                                <div id="candidateValidationErrBox">
                                    @include('layouts.errors')
                                </div>

                                <div class="col-md-12 mb-4">
                                    <div class="form-group">
                                        <label for="" class="fs-16 text-secondary mb-3">{{ __('web.common.email') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" class="form-control fs-14 text-gray bg-white  br-10 p-3"
                                            name="email" id="email"
                                            value="{{ Cookie::get('email') !== null ? Cookie::get('email') : '' }}"
                                            autofocus placeholder="@lang('web.login_menu.enter_email')" required>
                                    </div>
                                </div>

                                <div class="col-md-12 position-relative">
                                    <div class="form-group mb-md-4 mb-3 ">
                                        <label for=""
                                            class="fs-16 text-secondary mb-3">{{ __('web.common.password') }}
                                            <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control fs-14 text-gray bg-white  br-10 p-3"
                                            name="password" id="password" placeholder="@lang('web.login_menu.enter_password')"
                                            value="{{ Cookie::get('password') !== null ? Cookie::get('password') : '' }}"
                                            required>
                                        <span
                                            class="position-absolute d-flex align-items-center top-1 bottom-0 {{ getFrontSelectLanguage() == 'ar' ? 'start-0' : 'end-0' }} me-6 input-icon input-password-hide cursor-pointer text-gray-600 change-type">
                                            <i class="fas fa-eye-slash"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div class="form-check">
                                        <input type="checkbox" name="remember" class="form-check-input" id="remember"
                                            {{ Cookie::get('remember') !== null ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">
                                            {{ __('web.login_menu.remember_me') }}
                                        </label>
                                    </div>
                                    <a href="{{ route('password.request') }}"
                                        class="text-primary">{{ __('web.login_menu.forget_password') }}</a>
                                </div>
                            </div>
                            <div class="col-12 d-grid my-4">
                                <button type="submit"
                                    class="btn btn-secondary btn-secondary-login">{{ __('web.login') }}</button>
                            </div>
                        </form>
                    </div>
                    @if ($hasRegisterSideAds)
                        <div class="col-xl-3 col-lg-3 d-none d-lg-block mb-4 text-end front-auth-side-ads front-auth-side-ads--right">
                            @include('front_web.common.register_side_ad', ['ads' => $registerRightAds])
                        </div>
                        <div class="col-12 d-lg-none mt-4">
                            <div class="row">
                                @if ($registerLeftAds->isNotEmpty())
                                    <div class="col-sm-6 mb-3">
                                        @include('front_web.common.register_side_ad', ['ads' => $registerLeftAds])
                                    </div>
                                @endif
                                @if ($registerRightAds->isNotEmpty())
                                    <div class="col-sm-6 mb-3">
                                        @include('front_web.common.register_side_ad', ['ads' => $registerRightAds])
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
        <!-- end candidate login section -->
    </div>
@endsection


{{-- @section('page_scripts') --}}
{{--    <script> --}}
{{--        let registerSaveUrl = "{{ route('front.save.register') }}"; --}}
{{--    </script> --}}
{{--    <script src="{{asset('assets/js/auto_fill/auto_fill.js')}}"></script> --}}
{{-- @endsection --}}
