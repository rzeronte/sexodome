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
//        \App\Console\Commands\rZeBotExport::class,
        \App\Console\Commands\rZeBotFeedFetcher::class,
        \App\Console\Commands\rZeBotTranslate::class,
//        \App\Console\Commands\rZeBotSyncRemoteWithUniverso::class,
        \App\Console\Commands\rZeBotAnalytics::class,
//        \App\Console\Commands\rZeBotSynonyms::class,
//        \App\Console\Commands\rZeBotScrapper::class,
//        \App\Console\Commands\rZeBotSpinner::class,
//        \App\Console\Commands\rZeBotSpinnerLanguage::class,
        \App\Console\Commands\rZeBotDuplicateLanguage::class,
        \App\Console\Commands\rZeBotScenesPublisher::class,
//        \App\Console\Commands\rZeBotSyncFeeds::class,
        \App\Console\Commands\rZeBotUpdateNumberScenesFromDumps::class,
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
