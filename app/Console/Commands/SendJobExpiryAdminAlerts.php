<?php

namespace App\Console\Commands;

use App\Models\Job;
use App\Models\Notification;
use App\Models\NotificationSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendJobExpiryAdminAlerts extends Command
{
    protected $signature = 'jobs:send-expiry-admin-alerts';

    protected $description = 'Send admin notifications for job deadlines today, tomorrow, and in 7 days.';

    public function handle(): int
    {
        $setting = NotificationSetting::where('key', 'JOB_EXPIRY_ALERT')->first();

        if ($setting && ! (bool) $setting->value) {
            $this->info('Job expiry admin alerts are disabled.');

            return self::SUCCESS;
        }

        $today = Carbon::today();
        $alerts = [
            [
                'key' => 'deadline_today',
                'date' => $today->copy(),
                'title' => 'Job deadline today',
                'text' => 'job(s) have deadline today.',
                'url' => route('admin.jobs.index', ['deadline' => 'today']),
            ],
            [
                'key' => 'deadline_tomorrow',
                'date' => $today->copy()->addDay(),
                'title' => 'Job deadline tomorrow',
                'text' => 'job(s) have deadline tomorrow.',
                'url' => route('admin.jobs.index', ['deadline' => 'tomorrow']),
            ],
            [
                'key' => 'deadline_7_days',
                'date' => $today->copy()->addDays(7),
                'title' => 'Job deadline in 7 days',
                'text' => 'job(s) have deadline in 7 days.',
                'url' => route('admin.jobs.index', ['deadline' => '7_days']),
            ],
        ];

        $adminUsers = User::role('Admin')->get();

        if ($adminUsers->isEmpty()) {
            $this->warn('No admin user found for job expiry alerts.');

            return self::SUCCESS;
        }

        $sentCount = 0;

        foreach ($alerts as $alert) {
            $expiryDate = $alert['date']->toDateString();
            $jobCount = Job::whereDate('job_expiry_date', $expiryDate)
                ->where('status', Job::STATUS_OPEN)
                ->where('is_suspended', Job::NOT_SUSPENDED)
                ->count();

            if ($jobCount === 0) {
                continue;
            }

            foreach ($adminUsers as $adminUser) {
                $alreadySent = Notification::where('type', Notification::JOB_EXPIRY_ALERT_ADMIN)
                    ->where('notification_for', Notification::ADMIN)
                    ->where('user_id', $adminUser->id)
                    ->where('title', $alert['title'].' - '.$today->toDateString())
                    ->exists();

                if ($alreadySent) {
                    continue;
                }

                Notification::create([
                    'type' => Notification::JOB_EXPIRY_ALERT_ADMIN,
                    'user_id' => $adminUser->id,
                    'notification_for' => Notification::ADMIN,
                    'title' => $alert['title'].' - '.$today->toDateString(),
                    'text' => $jobCount.' '.$alert['text'],
                    'meta' => [
                        'deadline_alert_key' => $alert['key'],
                        'expiry_date' => $expiryDate,
                        'job_count' => $jobCount,
                        'url' => $alert['url'],
                    ],
                ]);

                $sentCount++;
            }
        }

        $this->info($sentCount.' job expiry admin alert notification(s) sent.');

        return self::SUCCESS;
    }
}
