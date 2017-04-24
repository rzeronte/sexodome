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
        \App\Console\Commands\BotFeedFetcher::class,
        \App\Console\Commands\BotTranslateLanguage::class,
        \App\Console\Commands\BotTranslateVideo::class,
        \App\Console\Commands\BotAnalytics::class,
        \App\Console\Commands\BotUpdateCategoriesThumbnails::class,
        \App\Console\Commands\BotUpdateDumps::class,
        \App\Console\Commands\BotSitesList::class,
        \App\Console\Commands\BotSitemapGenerator::class,
        \App\Console\Commands\BotSitemapGeneratorAll::class,
        \App\Console\Commands\BotCategoriesRecount::class,
        \App\Console\Commands\BotDeleteAll::class,
        \App\Console\Commands\BotCronJobs::class,
        \App\Console\Commands\BotCacheOrder::class,
        \App\Console\Commands\BotFeedRemover::class,
        \App\Console\Commands\BotGetProxies::class,
        \App\Console\Commands\BotRankingGoogle::class,
        \App\Console\Commands\BotClicker::class,
        \App\Console\Commands\BotDownloadThumbnails::class,
        \App\Console\Commands\BotCss::class,
        \App\Console\Commands\BotSpinScene::class,
        \App\Console\Commands\BotSiteCopy::class,
        \App\Console\Commands\BotCheckDuplicatedScenes::class,
        \App\Console\Commands\BotTest::class,
        \App\Console\Commands\BotCategorizeVideo::class,
        \App\Console\Commands\BotCategorizeSite::class,
        \App\Console\Commands\BotLoadJSONCategories::class,
        \App\Console\Commands\BotTaggerizeCategories::class,
        \App\Console\Commands\BotSiteInfo::class,
        \App\Console\Commands\BotScenesTaggerize::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
