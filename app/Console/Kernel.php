<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\SendBirthdayWish::class,
        \App\Console\Commands\SendWokAnniversaryWish::class,
        \App\Console\Commands\AttendanceCron::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('cron:attendance')->dailyAt('23:30');
        $schedule->command('send:birthdaywish')->dailyAt('08:00');
        $schedule->command('send:workanniversarywish')->dailyAt('08:00');
    }
}
