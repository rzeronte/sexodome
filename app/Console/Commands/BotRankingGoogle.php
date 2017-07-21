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

        rZeBotUtils::message("[BotRankingGoogle] Google Ranking for $url -> $keyword", "info",'kernel');

        $position = GoogleScrapper::scrape($keyword, array($url));

        if ($position == 0) {
            rZeBotUtils::message("[BotRankingGoogle] No encontrados resultados para $url con '$keyword'", "error", 'kernel');
        } else {
            rZeBotUtils::message("[BotRankingGoogle] Posición de '$url' para '$keyword': " . $position, "info", 'kernel');
        }
    }
}