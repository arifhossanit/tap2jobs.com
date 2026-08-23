<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class NotificationController extends AppBaseController
{
    public function latestNotifications(): JsonResponse
    {
        $notifications = getNotification(Notification::ADMIN)->map(function (Notification $notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->title,
                'text' => $notification->text,
                'icon' => getNotificationIcon($notification->type),
                'url' => getNotificationUrl($notification),
                'created_at' => $notification->created_at->diffForHumans(null, true),
            ];
        });

        return $this->sendResponse([
            'count' => $notifications->count(),
            'notifications' => $notifications,
        ], __('messages.notification.notifications'));
    }

    public function readNotification(Notification $notification): JsonResponse
    {
        $notification->read_at = Carbon::now();
        $notification->save();

        return $this->sendResponse([
            'url' => getNotificationUrl($notification),
        ], __('messages.flash.notification_read'));
    }

    public function readAllNotification(): JsonResponse
    {
        Notification::whereReadAt(null)->where('user_id', getLoggedInUserId())->update(['read_at' => Carbon::now()]);

        return $this->sendSuccess(__('messages.flash.all_notification_read'));
    }
}
