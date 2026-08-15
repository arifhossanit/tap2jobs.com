@extends('candidate.layouts.app')

@section('title')
    {{ __('messages.user.change_password') }}
@endsection

@push('css')
    <style>
        .candidate-password-page {
            --password-primary: #2563eb;
            --password-border: #e5ebf3;
            --password-muted: #64748b;
        }

        .candidate-password-hero {
            background: linear-gradient(135deg, #eef6ff 0%, #ffffff 58%, #f5fbf8 100%);
            border: 1px solid var(--password-border);
            border-radius: 12px;
            padding: 24px;
        }

        .candidate-password-icon {
            align-items: center;
            background: #ffffff;
            border: 1px solid var(--password-border);
            border-radius: 12px;
            color: var(--password-primary);
            display: flex;
            flex: 0 0 56px;
            height: 56px;
            justify-content: center;
            width: 56px;
        }

        .candidate-password-card {
            border: 1px solid var(--password-border);
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.05);
        }

        .candidate-password-alerts .alert {
            align-items: flex-start;
            border: 0;
            border-radius: 10px;
            display: flex;
            gap: 12px;
            margin-bottom: 18px;
            padding: 14px 16px;
        }

        .candidate-password-alerts .alert ul {
            margin-bottom: 0;
            padding-left: 18px;
        }

        .candidate-password-field {
            position: relative;
        }

        .candidate-password-input {
            background-color: #f8fafc;
            border-color: var(--password-border);
            min-height: 48px;
            padding-right: 48px;
        }

        .candidate-password-input:focus {
            background-color: #ffffff;
            border-color: #93c5fd;
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.12);
        }

        .candidate-password-input.is-valid,
        .candidate-password-input.is-invalid {
            background-image: none;
            padding-right: 48px;
        }

        .candidate-password-toggle {
            align-items: center;
            background: transparent;
            border: 0;
            color: #64748b;
            cursor: pointer;
            display: flex;
            height: 48px;
            justify-content: center;
            position: absolute;
            right: 0;
            top: 0;
            width: 48px;
            z-index: 10;
        }

        .candidate-password-toggle:hover {
            color: var(--password-primary);
        }

        .candidate-password-match {
            display: none;
            margin-top: 8px;
        }

        .candidate-password-match.is-visible {
            display: block;
        }

        .candidate-password-tip {
            align-items: flex-start;
            border-bottom: 1px solid #edf2f7;
            display: flex;
            gap: 12px;
            padding: 14px 0;
        }

        .candidate-password-tip:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .candidate-password-tip i {
            color: #16a34a;
            flex: 0 0 18px;
            line-height: 1;
            margin-top: 3px;
            text-align: center;
        }

        @media (max-width: 575.98px) {
            .candidate-password-hero {
                padding: 20px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="candidate-password-page d-flex flex-column">
        <div class="candidate-password-hero d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-5 mb-7">
            <div class="d-flex gap-4">
                <div class="candidate-password-icon">
                    <i class="fas fa-shield-alt fs-2"></i>
                </div>
                <div>
                    <h1 class="fs-2 fw-bold text-gray-900 mb-2">{{ __('messages.user.change_password') }}</h1>
                    <div class="text-gray-600 fs-6">{{ getLoggedInUser()->email }}</div>
                </div>
            </div>
            <!-- <a href="{{ route('candidate.profile') }}" class="btn btn-light-primary">
                <i class="fas fa-arrow-left me-2"></i>{{ __('messages.common.back') }}
            </a> -->
        </div>

        <div class="row g-6">
            <div class="col-xl-7 col-lg-8">
                <div class="card candidate-password-card">
                    <div class="card-body p-8">
                        <div class="candidate-password-alerts">
                            @include('layouts.errors')
                            @include('flash::message')
                        </div>

                        {{ Form::open(['route' => 'candidate.change-password', 'method' => 'post', 'id' => 'candidateChangePasswordPageForm']) }}
                            <div class="mb-5">
                                {{ Form::label('password_current', __('messages.company.current_password').':', ['class' => 'form-label required']) }}
                                <div class="candidate-password-field">
                                    <input class="form-control candidate-password-input" id="candidateCurrentPassword" type="password"
                                           name="password_current" autocomplete="current-password" required autofocus>
                                    <button type="button" class="candidate-password-toggle" data-password-toggle="candidateCurrentPassword"
                                            aria-label="{{ __('messages.user.show_password') }}" title="{{ __('messages.user.show_password') }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-5">
                                {{ Form::label('password', __('messages.company.new_password').':', ['class' => 'form-label required']) }}
                                <div class="candidate-password-field">
                                    <input class="form-control candidate-password-input" id="candidateNewPassword" type="password"
                                           name="password" autocomplete="new-password" minlength="6" maxlength="20" required>
                                    <button type="button" class="candidate-password-toggle" data-password-toggle="candidateNewPassword"
                                            aria-label="{{ __('messages.user.show_password') }}" title="{{ __('messages.user.show_password') }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text">{{ __('messages.user.password_length_hint') }}</div>
                            </div>

                            <div class="mb-7">
                                {{ Form::label('password_confirmation', __('messages.company.confirm_password').':', ['class' => 'form-label required']) }}
                                <div class="candidate-password-field">
                                    <input class="form-control candidate-password-input" id="candidateConfirmPassword" type="password"
                                           name="password_confirmation" autocomplete="new-password" minlength="6" maxlength="20" required>
                                    <button type="button" class="candidate-password-toggle" data-password-toggle="candidateConfirmPassword"
                                            aria-label="{{ __('messages.user.show_password') }}" title="{{ __('messages.user.show_password') }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="candidate-password-match text-danger fs-7" id="candidatePasswordMatchError">
                                    <i class="fas fa-circle-exclamation me-1"></i>{{ __('messages.user.passwords_do_not_match') }}
                                </div>
                                <div class="candidate-password-match text-success fs-7" id="candidatePasswordMatchSuccess">
                                    <i class="fas fa-check-circle me-1"></i>{{ __('messages.user.passwords_match') }}
                                </div>
                            </div>

                            <div class="d-flex flex-column flex-sm-row justify-content-end gap-3">
                                <a href="{{ route('dashboard') }}" class="btn btn-light">{{ __('messages.common.cancel') }}</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>{{ __('messages.common.save') }}
                                </button>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>

            <div class="col-xl-5 col-lg-4">
                <div class="card candidate-password-card h-100">
                    <div class="card-body p-8">
                        <h3 class="fs-4 fw-bold text-gray-900 mb-5">{{ __('messages.user.password_checklist') }}</h3>

                        <div class="candidate-password-tip">
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <div class="fw-semibold text-gray-900 mb-1">{{ __('messages.user.fresh_password') }}</div>
                                <div class="text-gray-600 fs-7">{{ __('messages.user.fresh_password_hint') }}</div>
                            </div>
                        </div>
                        <div class="candidate-password-tip">
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <div class="fw-semibold text-gray-900 mb-1">{{ __('messages.user.memorable_password') }}</div>
                                <div class="text-gray-600 fs-7">{{ __('messages.user.memorable_password_hint') }}</div>
                            </div>
                        </div>
                        <div class="candidate-password-tip">
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <div class="fw-semibold text-gray-900 mb-1">{{ __('messages.user.confirm_password_carefully') }}</div>
                                <div class="text-gray-600 fs-7">{{ __('messages.user.confirm_password_carefully_hint') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            const passwordToggleLabels = {
                show: @json(__('messages.user.show_password')),
                hide: @json(__('messages.user.hide_password')),
            };

            function updatePasswordMatchState() {
                const password = document.getElementById('candidateNewPassword');
                const confirmPassword = document.getElementById('candidateConfirmPassword');
                const mismatchMessage = document.getElementById('candidatePasswordMatchError');
                const matchMessage = document.getElementById('candidatePasswordMatchSuccess');

                if (!password || !confirmPassword || !mismatchMessage || !matchMessage) {
                    return true;
                }

                const hasConfirmation = confirmPassword.value.length > 0;
                const isMatch = password.value === confirmPassword.value;

                mismatchMessage.classList.toggle('is-visible', hasConfirmation && !isMatch);
                matchMessage.classList.toggle('is-visible', hasConfirmation && isMatch && password.value.length > 0);
                confirmPassword.classList.toggle('is-invalid', hasConfirmation && !isMatch);
                confirmPassword.classList.toggle('is-valid', hasConfirmation && isMatch && password.value.length > 0);

                return !hasConfirmation || isMatch;
            }

            function setupCandidatePasswordPage() {
                const password = document.getElementById('candidateNewPassword');
                const confirmPassword = document.getElementById('candidateConfirmPassword');
                const form = document.getElementById('candidateChangePasswordPageForm');

                if (password && confirmPassword) {
                    password.removeEventListener('input', updatePasswordMatchState);
                    confirmPassword.removeEventListener('input', updatePasswordMatchState);
                    password.addEventListener('input', updatePasswordMatchState);
                    confirmPassword.addEventListener('input', updatePasswordMatchState);
                }

                if (form) {
                    form.addEventListener('submit', function (event) {
                        if (!updatePasswordMatchState()) {
                            event.preventDefault();
                            if (confirmPassword) confirmPassword.focus();
                        }
                    });
                }
            }

            // Global click listener for password toggle buttons (works via event delegation)
            document.addEventListener('click', function (event) {
                const button = event.target.closest('[data-password-toggle]');
                if (!button) return;

                event.preventDefault();
                const targetId = button.getAttribute('data-password-toggle');
                const input = document.getElementById(targetId);
                const icon = button.querySelector('i');

                if (!input) return;

                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';

                if (icon) {
                    if (isPassword) {
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                }

                const label = isPassword ? passwordToggleLabels.hide : passwordToggleLabels.show;
                button.setAttribute('aria-label', label);
                button.setAttribute('title', label);
            });

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', setupCandidatePasswordPage);
            } else {
                setupCandidatePasswordPage();
            }

            document.addEventListener('turbo:load', setupCandidatePasswordPage);
            document.addEventListener('turbolinks:load', setupCandidatePasswordPage);
        })();
    </script>
@endpush
