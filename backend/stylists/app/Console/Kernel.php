<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Inspire::class,
        Commands\Fetcher::class,
        Commands\Importer::class,
        Commands\FetchNicobarProduct::class,
        Commands\SendBookingReminder::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('inspire')->hourly();
        $schedule->command('fetch')->dailyAt('23:00');
        $schedule->command('import')->dailyAt('23:30');
        $schedule->command('SendBookingReminder')->everyTenMinutes();
    }
}
