@php
    $flashMessages = session('flash_notification', collect())->map(function ($message) {
        return [
            'level' => $message['level'] ?? 'info',
            'message' => $message['message'] ?? '',
            'title' => $message['title'] ?? null,
        ];
    })->values();
    session()->forget('flash_notification');
@endphp

@if ($flashMessages->isNotEmpty())
    <script>
        (function () {
            const messages = @json($flashMessages);

            function showFlashMessages() {
                if (typeof window.displayAlertMessage !== 'function') return;

                messages.reduce(function (queue, notification) {
                    return queue.then(function () {
                        return window.displayAlertMessage(notification.level, notification.message, notification.title);
                    });
                }, Promise.resolve());
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', showFlashMessages, { once: true });
            } else {
                showFlashMessages();
            }
        })();
    </script>
@endif
