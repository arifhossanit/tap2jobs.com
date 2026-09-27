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
        <section class="py-100 login-form-section">
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
                                        <label for="" class="fs-16 text-secondary mb-2">{{ __('web.common.email') }}
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
                                            class="fs-16 text-secondary mb-2">{{ __('web.common.password') }}
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
                            <div class="border-top pt-4 text-center">
                                <p class="text-secondary mb-3">
                                    {{ __("web.login_menu.don't_have_an_account") }}
                                    <span class="fw-semibold">{{ __('web.register_menu.create_account') }}</span>
                                </p>
                                <div class="d-flex gap-3 login-register-actions">
                                    <a href="{{ route('candidate.register') }}" class="btn btn-outline-primary flex-fill">
                                        <i class="fas fa-user me-2" aria-hidden="true"></i>{{ __('web.register_menu.candidate') }}
                                    </a>
                                    <a href="{{ route('employer.register') }}" class="btn btn-outline-primary flex-fill">
                                        <i class="fas fa-building me-2" aria-hidden="true"></i>{{ __('web.register_menu.employer') }}
                                    </a>
                                </div>
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

@section('page_css')
    <style>
        @media (max-width: 575.98px) {
            .login-page .login-form-section {
                padding-top: 24px !important;
            }

            .login-page .login-register-actions {
                flex-direction: row;
                gap: 10px !important;
            }

            .login-page .login-register-actions .btn {
                align-items: center;
                display: inline-flex;
                font-size: 13px;
                justify-content: center;
                min-width: 0;
                padding-left: 10px;
                padding-right: 10px;
                white-space: nowrap;
            }

            .login-page .login-register-actions .btn i {
                margin-right: 6px !important;
            }
        }

        .sweet-alert.login-instruction-popup {
            border: 1px solid rgba(32, 151, 118, 0.16);
            border-radius: 20px;
            box-shadow: 0 24px 70px rgba(15, 23, 42, 0.22);
            max-width: 470px;
            overflow: hidden;
            padding: 10px 12px 12px;
        }

        .login-instruction-popup .sa-icon {
            border-color: rgba(32, 151, 118, 0.28);
            margin-bottom: 20px;
            margin-top: 30px;
        }

        .login-instruction-popup .sa-icon.sa-info {
            border-color: rgba(32, 151, 118, 0.38);
            color: #209776;
        }

        .login-instruction-popup .sa-icon.sa-info::before,
        .login-instruction-popup .sa-icon.sa-info::after {
            background-color: #209776;
        }

        .login-instruction-popup h2 {
            color: #17233c;
            font-size: 24px;
            font-weight: 700;
            line-height: 1.3;
            margin: 0;
            padding: 0 24px;
        }

        .login-instruction-popup p {
            color: #667085;
            font-size: 17px;
            line-height: 1.75;
            margin-top: 14px;
            text-align: center;
        }

        .login-instruction-popup .sa-button-container {
            margin-top: 24px;
            padding: 0 24px 18px;
            text-align: center;
        }

        .login-instruction-popup button.confirm {
            background: linear-gradient(135deg, #209776 0%, #167a61 100%);
            border: 0;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(32, 151, 118, 0.24);
            font-size: 14px;
            font-weight: 600;
            min-width: 180px;
            padding: 12px 24px;
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }

        .login-instruction-popup button.confirm:not([disabled]):hover,
        .login-instruction-popup button.confirm:not([disabled]):focus {
            background: linear-gradient(135deg, #1c896b 0%, #126b54 100%);
            box-shadow: 0 10px 24px rgba(32, 151, 118, 0.3);
            transform: translateY(-1px);
        }

        .login-instruction-popup button.confirm:focus {
            outline: 3px solid rgba(32, 151, 118, 0.18);
        }

        @media (max-width: 575.98px) {
            .sweet-alert.login-instruction-popup {
                border-radius: 16px;
                margin: 0 18px;
                width: auto;
            }

            .login-instruction-popup h2 {
                font-size: 21px;
            }

            .login-instruction-popup p {
                font-size: 14px;
                padding: 0 8px;
            }

            .login-instruction-popup button.confirm {
                width: 100%;
            }
        }
    </style>
@endsection

@if (! $errors->any() && ! session()->has('registration_verification_message') && request()->boolean('show_login_info'))
    @section('page_scripts')
        <script>
        (function () {
            function showLoginInstruction() {
                if (typeof window.swal !== 'function') {
                    window.alert(@json(__('auth.login_instruction_text')));
                    return;
                }

                window.swal({
                    customClass: 'login-instruction-popup',
                    type: 'info',
                    title: @json(__('auth.login_instruction_title')),
                    text: @json(__('auth.login_instruction_text')),
                    confirmButtonText: @json(__('auth.continue_to_login')),
                    confirmButtonColor: '#209776',
                    allowOutsideClick: true
                }, function () {
                    document.getElementById('email')?.focus();
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', showLoginInstruction, { once: true });
            } else {
                showLoginInstruction();
            }
        })();
        </script>
    @endsection
@endif


{{-- @section('page_scripts') --}}
{{--    <script> --}}
{{--        let registerSaveUrl = "{{ route('front.save.register') }}"; --}}
{{--    </script> --}}
{{--    <script src="{{asset('assets/js/auto_fill/auto_fill.js')}}"></script> --}}
{{-- @endsection --}}
