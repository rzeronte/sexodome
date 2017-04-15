<?php

namespace App\Console\Commands;

use App\Model\LanguageTag;
use App\Model\Site;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\Model\Host;
use App\Model\Tweet;
use App\rZeBot\TwitterAPIExchange;
use DB;
use Illuminate\Support\Facades\Artisan;


class BotCategorizeSite extends Command
{
    protected $signature = 'zbot:categorize:site {site_id}';

    protected $description = 'Categorize scenes for one site in function his tags';

    public function handle()
    {
        $site_id = $this->argument('site_id');

        $site = Site::find($site_id);

        if (!$site) {
            rZeBotUtils::message("Error el site id: $site_id no existe", "red");
            exit;
        }

        if (!$this->ask('Do you want categorize scenes for ' . $site->getHost() . "?")) {
            return;
        }

        rZeBotUtils::message("Recategorizando ". $site->getHost(), "cyan", false, false);
        $scenes = $site->scenes()->select('id')->get();
        foreach ($scenes as $scene) {
            $exitCodeCmd = Artisan::call('zbot:categorize:scene', [
                'scene_id' => $scene->id,
            ]);

        }
    }
}