<?php

namespace App\Console\Commands;

use App\Model\LanguageTag;
use App\Model\Site;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\Model\Host;
use App\Model\Scene;
use DB;

class BotDownloadThumbnails extends Command
{
    protected $signature = 'zbot:thumbnails:downloadAll';

    protected $description = 'Download all thumbnails';

    public function handle()
    {
        $sites = Site::all();

        foreach($sites as $site) {
            rZeBotUtils::message("[DOWNLOAD FOR ".$site->getHost()."]", "cyan", true, true);

            $scenes = Scene::select("id", "preview")->where("site_id", $site->id)->get();

            $i = 0;
            foreach($scenes as $scene) {
                $i++;
                rZeBotUtils::downloadThumbnail($scene->preview, $i);
            }
        }
    }
}