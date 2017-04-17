<?php

namespace App\Console\Commands;

use App\Model\Category;
use App\Model\LanguageTag;
use App\Model\Site;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\Model\Host;
use App\Model\Tweet;
use App\rZeBot\TwitterAPIExchange;
use DB;
use Artisan;

class BotSiteInit extends Command
{
    protected $signature = 'zbot:site:init {site_id}';

    protected $description = 'Load JSON categories, taggerize categories, categorize scenes, generate thumbnails and recount scenes for a site';

    public function handle()
    {
        $site_id = $this->argument('site_id');

        $site = Site::find($site_id);

        if (!$site) {
            rZeBotUtils::message("El site_id indicado no existe", "red");
            exit;
        }

        if (!$this->ask('Do you delete categories for site ' . $site->getHost() . "?")) {
            Category::where('site_id', $site->id)->delete();
        }

        Artisan::call('zbot:categories:json', [
            'site_id' => $site->id,
        ]);

        Artisan::call('zbot:categories:taggerize', [
            'site_id' => $site->id,
        ]);

        Artisan::call('zbot:categorize:site', [
            'site_id' => $site->id,
        ]);

        Artisan::call('zbot:categories:thumbnails', [
            '--site_id' => $site->id,
        ]);

        Artisan::call('zbot:categories:recount', [
            'site_id' => $site->id,
        ]);

    }
}