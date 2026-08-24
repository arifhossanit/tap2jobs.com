<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class NotificationController extends AppBaseController
{
    public function latestNotifications(): JsonResponse
    {
        $notificationFor = Notification::ADMIN;

        if (getLoggedInUser()->hasRole('Candidate')) {
            $notificationFor = Notification::CANDIDATE;
        } elseif (getLoggedInUser()->hasRole('Employer')) {
            $notificationFor = Notification::EMPLOYER;
        }

        $notifications = Notification::whereNotificationFor($notificationFor)
            ->where('user_id', getLoggedInUserId())
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        $unreadCount = $notifications->whereNull('read_at')->count();

        $notificationItems = $notifications->map(function (Notification $notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->title,
                'text' => $notification->text,
                'icon' => getNotificationIcon($notification->type),
                'url' => getNotificationUrl($notification),
                'created_at' => $notification->created_at->diffForHumans(null, true),
                'is_read' => ! empty($notification->read_at),
            ];
        });

        return $this->sendResponse([
            'count' => $unreadCount,
            'notifications' => $notificationItems,
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
