<?php

namespace App\Console;

use App\Console\Commands\DeleteExpiredFeaturedCompany;
use App\Console\Commands\SendJobExpiryAdminAlerts;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(DeleteExpiredFeaturedCompany::class)->daily();
        $schedule->command(SendJobExpiryAdminAlerts::class)->dailyAt('08:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
