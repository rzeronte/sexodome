<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Site;
use Illuminate\Support\Facades\Artisan;

class BotSitemapGeneratorAll extends Command
{
    protected $signature = 'zbot:sitemap:all';

    protected $description = 'Generate all sitemaps';

    public function handle()
    {
        $sites = Site::all();

        foreach($sites as $site) {
            Artisan::call('zbot:sitemap:site', [
                'site_id' => $site->id
            ]);
        }
    }
}
