<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Site;
use Illuminate\Support\Facades\Artisan;

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
            Artisan::call('zbot:sitemap:site', [
                'site_id' => $site->id
            ]);
        }
    }
}
