<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\rZeBot\GoogleScrapper;

class BotRankingGoogle extends Command
{
    protected $signature = 'zbot:ranking:google {site_url} {keyword}';

    protected $description = 'Check ranking for keyword for a site';

    public function handle()
    {

        $keyword = $this->argument('keyword');
        $url = $this->argument('site_url');

        rZeBotUtils::message("[GOOGLE RANKING] $url -> $keyword", "green", true, true);

        $position = GoogleScrapper::scrape($keyword, array($url));

        if ($position == 0) {
            rZeBotUtils::message("No encontrados resultados para $url con '$keyword'", "red", true, true);
        } else {
            rZeBotUtils::message("Posición de $url para '$keyword': " . $position, "green", true, true);
        }
    }
}