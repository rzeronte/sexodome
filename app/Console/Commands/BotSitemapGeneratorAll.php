<?php

namespace App\Console\Commands;

use App\rZeBot\rZeBotUtils;
use Illuminate\Console\Command;
use Roumen\Sitemap\Sitemap;
use Illuminate\Support\Facades\App;
use App\Model\Language;
use App\Model\Scene;
use Request;
use App\Model\Site;
use Artisan;

class BotSitemapGeneratorAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zbot:sitemap:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate all sitemaps';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sites = Site::all();

        foreach($sites as $site) {
            $siteSitemapGeneratorCode = Artisan::call('zbot:sitemap:site', [
                'site_id' => $site->id
            ]);
        }
    }
}
