@php
    $flashNotifications = session('flash_notification', collect())->toArray();
    session()->forget('flash_notification');
@endphp

@if(count($flashNotifications))
    <script>
        (function () {
            const notify = function (level, message) {
                if (level === 'success' && typeof displaySuccessMessage === 'function') {
                    displaySuccessMessage(message);
                    return;
                }

                if (['danger', 'error'].includes(level) && typeof displayErrorMessage === 'function') {
                    displayErrorMessage(message);
                    return;
                }

                if (typeof toastr !== 'undefined') {
                    if (level === 'success') {
                        toastr.success(message);
                    } else if (['danger', 'error'].includes(level)) {
                        toastr.error(message);
                    } else if (level === 'warning') {
                        toastr.warning(message);
                    } else {
                        toastr.info(message);
                    }
                }
            };

            const showFlashToasts = function () {
                @foreach($flashNotifications as $message)
                    notify(@json($message['level']), @json($message['message']));
                @endforeach
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', showFlashToasts, { once: true });
            } else {
                showFlashToasts();
            }
        })();
    </script>
@endif
