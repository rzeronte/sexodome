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
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\BotFeedFetcher::class,
        \App\Console\Commands\BotTranslate::class,
        \App\Console\Commands\BotAnalytics::class,
        \App\Console\Commands\BotUpdateCategoriesThumbnails::class,
        \App\Console\Commands\BotUpdateNumberScenesFromDumps::class,
        \App\Console\Commands\BotSitesList::class,
        \App\Console\Commands\BotSitemapGenerator::class,
        \App\Console\Commands\BotCreateCategoriesFromTags::class,

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();
    }
}
