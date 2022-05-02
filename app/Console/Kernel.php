<?php

namespace App\Console;

use App\Console\Commands\BotAnalytics;
use App\Console\Commands\BotCacheOrder;
use App\Console\Commands\BotCategoriesLanguageCopy;
use App\Console\Commands\BotCategoriesRecount;
use App\Console\Commands\BotCategorizeSite;
use App\Console\Commands\BotCategorizeVideo;
use App\Console\Commands\BotCheckDuplicatedScenes;
use App\Console\Commands\BotCronJobs;
use App\Console\Commands\BotCss;
use App\Console\Commands\BotDeleteAll;
use App\Console\Commands\BotDownloadThumbnails;
use App\Console\Commands\BotFeedFetcher;
use App\Console\Commands\BotFeedRemover;
use App\Console\Commands\BotLoadJSONCategories;
use App\Console\Commands\BotRankingGoogle;
use App\Console\Commands\BotScenesTaggerize;
use App\Console\Commands\BotSiteCopy;
use App\Console\Commands\BotSiteInfo;
use App\Console\Commands\BotSitemapGenerator;
use App\Console\Commands\BotSitemapGeneratorAll;
use App\Console\Commands\BotSitesList;
use App\Console\Commands\BotTaggerizeCategories;
use App\Console\Commands\BotTest;
use App\Console\Commands\BotTranslateLanguage;
use App\Console\Commands\BotTranslateVideo;
use App\Console\Commands\BotUpdateCategoriesThumbnails;
use App\Console\Commands\BotUpdateDumps;
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
        BotFeedFetcher::class,
        BotTranslateLanguage::class,
        BotTranslateVideo::class,
        BotAnalytics::class,
        BotUpdateCategoriesThumbnails::class,
        BotUpdateDumps::class,
        BotSitesList::class,
        BotSitemapGenerator::class,
        BotSitemapGeneratorAll::class,
        BotCategoriesRecount::class,
        BotDeleteAll::class,
        BotCronJobs::class,
        BotCacheOrder::class,
        BotFeedRemover::class,
        BotRankingGoogle::class,
        BotDownloadThumbnails::class,
        BotCss::class,
        BotSiteCopy::class,
        BotCheckDuplicatedScenes::class,
        BotTest::class,
        BotCategorizeVideo::class,
        BotCategorizeSite::class,
        BotLoadJSONCategories::class,
        BotTaggerizeCategories::class,
        BotSiteInfo::class,
        BotScenesTaggerize::class,
        BotCategoriesLanguageCopy::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  Schedule  $schedule
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
