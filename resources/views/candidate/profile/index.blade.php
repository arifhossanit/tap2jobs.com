@extends('candidate.layouts.app')
@section('title')
    {{ __('messages.profile') }}
@endsection
@section('content')
    @include('flash::message')
    @include('layouts.errors')
    <div class="mb-3 mb-xl-3 candidate-profile-menu-shell">
        <div class="py-0">
            @include('candidate.profile.profile_menu')
        </div>
    </div>
    <div>
        <div class="py-0">
                @yield('section')
            </div>
        </div>
@endsection

@if(! empty($data['profileReturnUrl']))
    @push('scripts')
        <script>
            (function () {
                const returnUrl = @json($data['profileReturnUrl']);
                let submittedProfileForm = false;
                let redirectScheduled = false;
                const originalDisplaySuccessMessage = window.displaySuccessMessage;

                function redirectAfterSuccessMessage(message) {
                    if (redirectScheduled) {
                        return;
                    }

                    redirectScheduled = true;
                    const alertResult = typeof originalDisplaySuccessMessage === 'function'
                        ? originalDisplaySuccessMessage(message)
                        : Promise.resolve();

                    Promise.resolve(alertResult).then(function () {
                        window.location.assign(returnUrl);
                    });
                }

                window.displaySuccessMessage = function (message) {
                    if (! submittedProfileForm) {
                        return originalDisplaySuccessMessage(message);
                    }

                    return redirectAfterSuccessMessage(message);
                };

                document.addEventListener('submit', function () {
                    submittedProfileForm = true;
                }, true);

                $(document).ajaxSuccess(function (event, xhr, settings) {
                    const method = String(settings.type || settings.method || 'GET').toUpperCase();

                    if (submittedProfileForm && ! redirectScheduled && ['POST', 'PUT', 'PATCH'].includes(method)) {
                        const response = xhr.responseJSON || {};
                        redirectAfterSuccessMessage(response.message || @json(__('messages.flash.candidate_profile')));
                    }
                });
            })();
        </script>
    @endpush
@endif
