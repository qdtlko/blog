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
        // Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        // Run once a minute
        $schedule->command('queue:work')->cron('* * * * * *');

//        // 每5分钟运行一次
//        $schedule->command('queue:work')->everyFiveMinutes();
//
//        // 一天运行一次
//        $schedule->command('queue:work')->daily();
//
//        // 每个星期一早上8:15运行
//        $schedule->command('queue:work')->weeklyOn(1, '8:15');
    }
}
