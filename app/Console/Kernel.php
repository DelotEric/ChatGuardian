<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('reminders:digest today')->dailyAt('07:00');
        $schedule->command('stocks:alert')->dailyAt('06:50');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}
