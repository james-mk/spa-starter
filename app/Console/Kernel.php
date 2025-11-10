<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\CheckExpiringDriverDocuments::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Check for expiring documents daily at 8 AM
        $schedule->command('drivers:check-expiring-documents --digest')
            ->dailyAt('08:00')
            ->timezone('Africa/Nairobi');

        // Alternative: Check every Monday at 9 AM
        // $schedule->command('drivers:check-expiring-documents --digest')
        //     ->weeklyOn(1, '09:00')
        //     ->timezone('Africa/Nairobi');

        // Check for critical documents (expiring within 7 days) daily
        $schedule->command('drivers:check-expiring-documents --days=7')
            ->dailyAt('09:00')
            ->timezone('Africa/Nairobi');
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
