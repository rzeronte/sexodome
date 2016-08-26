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
        \App\Console\Commands\BotUpdateDumps::class,
        \App\Console\Commands\BotSitesList::class,
        \App\Console\Commands\BotSitemapGenerator::class,
        \App\Console\Commands\BotSitemapGeneratorAll::class,
        \App\Console\Commands\BotCreateCategoriesFromTags::class,
        \App\Console\Commands\BotCategoriesRecount::class,
        \App\Console\Commands\BotCheckDatabase::class,
        \App\Console\Commands\BotDeleteAll::class,
        \App\Console\Commands\BotCronJobs::class,
        \App\Console\Commands\BotCacheOrder::class,
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
