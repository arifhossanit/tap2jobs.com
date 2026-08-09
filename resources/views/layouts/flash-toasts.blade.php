@php
    $flashNotifications = session('flash_notification', collect())->toArray();
    session()->forget('flash_notification');
@endphp

@if(count($flashNotifications))
    <script>
        (function () {
            const showFlashToasts = function () {
                @foreach($flashNotifications as $message)
                    @if(in_array($message['level'], ['success']))
                        displaySuccessMessage(@json($message['message']));
                    @elseif(in_array($message['level'], ['danger', 'error']))
                        displayErrorMessage(@json($message['message']));
                    @elseif($message['level'] === 'warning')
                        toastr.warning(@json($message['message']));
                    @else
                        toastr.info(@json($message['message']));
                    @endif
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
