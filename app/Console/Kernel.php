<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('app:process-lunwen-tasks')->everyMinute();
        // 生成sitemap 每天生成一次
        $schedule->command('sitemap:generate')->daily();
        // 翻译文章每天翻译一次
        $schedule->command('translate:articles --limit=5 --method=api --source=nl')
            ->daily()
            ->withoutOverlapping();

        $schedule->command('app:process-article-tasks')->everyMinute();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
