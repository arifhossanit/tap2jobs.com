@php
    $flashNotifications = session('flash_notification', collect())->map(function ($message) {
        return [
            'level' => $message['level'] ?? 'info',
            'message' => $message['message'] ?? '',
            'title' => $message['title'] ?? null,
        ];
    })->values();
    session()->forget('flash_notification');
@endphp

@if ($flashNotifications->isNotEmpty())
    <script>
        (function () {
            const notifications = @json($flashNotifications);

            function showFlashAlerts() {
                if (typeof window.displayAlertMessage !== 'function') return;

                notifications.reduce(function (queue, notification) {
                    return queue.then(function () {
                        return window.displayAlertMessage(notification.level, notification.message, notification.title);
                    });
                }, Promise.resolve());
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', showFlashAlerts, { once: true });
            } else {
                showFlashAlerts();
            }
        })();
    </script>
@endif
