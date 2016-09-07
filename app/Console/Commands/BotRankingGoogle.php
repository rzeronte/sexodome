<?php

namespace App\Console\Commands;

use App\Model\LanguageTag;
use App\Model\Site;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use DB;
use App\rZeBot\GoogleScrapper;

class BotRankingGoogle extends Command
{
    protected $signature = 'zbot:ranking:google {site_id} {keyword}';

    protected $description = 'Check ranking for keyword for a site';

    public function handle()
    {

        $keyword = $this->argument('keyword');
        $site_id = $this->argument('site_id');

        $site = Site::find($site_id);

        if (!$site) {
            rZeBotUtils::message("Site $site_id not found", "red", true, true);
            exit;
        }

        $url = $site->getHost();
        rZeBotUtils::message("[GOOGLE RANKING] $url -> $keyword", "green", true, true);

        $position = GoogleScrapper::scrape($keyword, array($url));

        if ($position == 0) {
            rZeBotUtils::message("No encontrados resultados para $url con '$keyword'", "red", true, true);
        } else {
            rZeBotUtils::message("Posición de $url para '$keyword': " . $position, "green", true, true);
        }
    }
}